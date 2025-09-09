<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Auth\VerifyEmails;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Auth\VerifyResetOtp;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('verify-reset-otp', VerifyResetOtp::class)->name('password.verify-otp');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
    Route::get('verify-emails', VerifyEmails::class)->name('verify.emails');
    Route::get('verify-otp', VerifyOtp::class)->name('verify.otp');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', ConfirmPassword::class)
        ->name('password.confirm');
});

// Route::post('logout', App\Livewire\Actions\Logout::class)
//     ->name('logout');
