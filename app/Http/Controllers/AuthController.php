<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

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
}


