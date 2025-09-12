<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');
    Route::get('/dashboard/children', [DashboardController::class, 'children'])->name('dashboard.children');
    Route::get('/dashboard/location-logs', [DashboardController::class, 'locationLogs'])->name('dashboard.locationLogs');
    Route::get('/dashboard/presence-calls', [DashboardController::class, 'presenceCalls'])->name('dashboard.presenceCalls');
    Route::post('/dashboard/send-presence-call', [DashboardController::class, 'sendPresenceCall'])->name('dashboard.sendPresenceCall');

    // Provide signed verification link as JSON for EmailJS integration (dev-friendly)
    Route::get('/email/verification-link.json', [AuthController::class, 'verificationLinkJson'])->name('verification.link.json');

    // Email verification routes (EmailJS only)
    Route::get('/email/verify', function () {
        return view('verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard');
    })->middleware(['signed'])->name('verification.verify');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});