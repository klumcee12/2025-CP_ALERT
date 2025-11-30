<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function checkSession(Request $request)
    {
        // Note: This endpoint will touch the session (Laravel middleware does this automatically)
        // So we only call this when user activity is detected, not on a timer
        if (Auth::check()) {
            $sessionLifetime = config('session.lifetime', 15);
            
            return response()->json([
                'authenticated' => true,
                'session_lifetime' => $sessionLifetime * 60, // in seconds
                'message' => 'Session is active',
            ]);
        }
        
        return response()->json([
            'authenticated' => false,
            'time_remaining' => 0,
            'message' => 'Session expired',
        ], 401);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required','string','max:255'],
            'middle_name' => ['nullable','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $nameParts = [
            $validated['first_name'],
            $validated['middle_name'] ?? null,
            $validated['last_name'],
        ];

        $fullName = trim(implode(' ', array_filter($nameParts)));

        $user = User::create([
            'name' => $fullName,
            'middle_name' => $validated['middle_name'] ?? null,
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

         // Do not log in; frontend (EmailJS) will send email, then user returns to login
         if ($request->wantsJson() || $request->ajax()) {
             $expiresAt = Carbon::now()->addMinutes(30);
             $verificationUrl = URL::temporarySignedRoute(
                 'emailjs.verify',
                 $expiresAt,
                 [
                     'id' => $user->getKey(),
                     'hash' => sha1($user->email),
                 ]
             );

             return response()->json([
                 'ok' => true,
                 'email' => $user->email,
                 'name' => $fullName,
                 'link' => $verificationUrl,
                 'expires_at' => $expiresAt->toIso8601String(),
             ]);
         }

         return redirect()->route('login')->with('status', 'Registration successful. Please check your email.');
    }

    public function getProfile()
    {
        $user = Auth::user();
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'middle_name' => $user->middle_name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'created_at' => $user->created_at->toIso8601String(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $validated['name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'email' => $validated['email'],
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'middle_name' => $user->middle_name,
                'email' => $user->email,
            ],
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Password updated successfully',
        ]);
    }

    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect') ?: url('/auth/google/callback');
        
        if (!$clientId) {
            return redirect()->route('login')->withErrors(['error' => 'Google OAuth is not configured. Please set GOOGLE_CLIENT_ID in your .env file.']);
        }
        
        // Ensure redirect URI doesn't have trailing slash
        $redirectUri = rtrim($redirectUri, '/');
        
        $scope = 'openid email profile';
        
        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scope,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');
        $error = $request->get('error');
        
        if ($error) {
            return redirect()->route('login')->withErrors(['error' => 'Google authentication failed: ' . $error]);
        }
        
        if (!$code) {
            return redirect()->route('login')->withErrors(['error' => 'Google authentication failed. No authorization code received.']);
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect') ?: url('/auth/google/callback');
        
        // Ensure redirect URI doesn't have trailing slash
        $redirectUri = rtrim($redirectUri, '/');
        
        if (!$clientId || !$clientSecret) {
            return redirect()->route('login')->withErrors(['error' => 'Google OAuth is not properly configured.']);
        }

        // Exchange code for access token
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ]);

        if (!$tokenResponse->successful()) {
            return redirect()->route('login')->withErrors(['error' => 'Failed to authenticate with Google.']);
        }

        $tokenData = $tokenResponse->json();
        $accessToken = $tokenData['access_token'];

        // Get user info from Google
        $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');
        
        if (!$userResponse->successful()) {
            return redirect()->route('login')->withErrors(['error' => 'Failed to retrieve user information from Google.']);
        }

        $googleUser = $userResponse->json();

        // Find or create user
        $user = User::where('google_id', $googleUser['id'])
            ->orWhere('email', $googleUser['email'])
            ->first();

        if ($user) {
            // Update existing user with Google ID if not set
            if (!$user->google_id) {
                $user->google_id = $googleUser['id'];
                $user->save();
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $googleUser['name'] ?? 'User',
                'email' => $googleUser['email'],
                'google_id' => $googleUser['id'],
                'email_verified_at' => now(), // Google emails are verified
                'password' => null, // No password for Google users
            ]);
        }

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function handleGoogleToken(Request $request)
    {
        $request->validate([
            'credential' => 'required|string',
        ]);

        $credential = $request->input('credential');
        
        // Decode the JWT token (Google One Tap)
        $parts = explode('.', $credential);
        if (count($parts) !== 3) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
        
        if (!$payload || !isset($payload['sub']) || !isset($payload['email'])) {
            return response()->json(['error' => 'Invalid token payload'], 400);
        }

        $googleId = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'] ?? 'User';
        $emailVerified = isset($payload['email_verified']) && $payload['email_verified'];

        // Find or create user
        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            // Update existing user with Google ID if not set
            if (!$user->google_id) {
                $user->google_id = $googleId;
                if ($emailVerified && !$user->email_verified_at) {
                    $user->email_verified_at = now();
                }
                $user->save();
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'email_verified_at' => $emailVerified ? now() : null,
                'password' => null,
            ]);
        }

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard'),
        ]);
    }
}


