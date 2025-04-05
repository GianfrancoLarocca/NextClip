<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autenticazione (API Sanctum)
|--------------------------------------------------------------------------
|
| Queste route sono pensate per un'API SPA usando Laravel Sanctum.
| Le route "guest" richiedono anche il middleware "web" per la sessione.
|
*/

// ✅ Route per utenti non autenticati (guest)
Route::middleware(['web', 'guest'])->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// ✅ Route per utenti autenticati
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', fn () => request()->user());

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // (Facoltativo) Email verification
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});
