<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/children', [DashboardController::class, 'children'])->name('dashboard.children');
    Route::get('/dashboard/location-logs', [DashboardController::class, 'locationLogs'])->name('dashboard.locationLogs');
    Route::get('/dashboard/presence-calls', [DashboardController::class, 'presenceCalls'])->name('dashboard.presenceCalls');
    Route::post('/dashboard/send-presence-call', [DashboardController::class, 'sendPresenceCall'])->name('dashboard.sendPresenceCall');
    Route::post('/dashboard/children', [DashboardController::class, 'createChild'])->name('dashboard.createChild');
    Route::get('/session/check', [AuthController::class, 'checkSession'])->name('session.check');

    // Email verification disabled
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Verification handler for EmailJS links (no auth middleware)
Route::get('/emailjs/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    if (! $request->hasValidSignature()) {
        abort(403);
    }

    $user = \App\Models\User::findOrFail($id);
    if (sha1($user->email) !== $hash) {
        abort(403);
    }

    if (is_null($user->email_verified_at)) {
        $user->forceFill(['email_verified_at' => now()])->save();
    }

    return redirect()->route('login')->with('status', 'Email verified. You can now sign in.');
})->name('emailjs.verify')->middleware('signed');