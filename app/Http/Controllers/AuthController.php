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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Keep user logged in but require verification (EmailJS handles sending)
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('verification.notice');
    }

    public function verificationLinkJson(Request $request)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $expiresAt = Carbon::now()->addMinutes(15);
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            $expiresAt,
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        return response()->json([
            'email' => $user->email,
            'name' => $user->name,
            'link' => $verificationUrl,
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }
}


