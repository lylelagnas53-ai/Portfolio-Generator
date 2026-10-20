<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function (): void {
    Route::get('/portfolios', [PortfolioController::class, 'index'])
        ->name('portfolios.index');
    Route::get('/portfolios/create', [PortfolioController::class, 'create'])
        ->name('portfolios.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])
        ->name('portfolios.store');

    Route::get('/portfolios/{portfolio}/templates', [PortfolioController::class, 'templates'])
        ->whereNumber('portfolio')
        ->name('templates.index');
    Route::post('/templates/select', [PortfolioController::class, 'selectTemplate'])
        ->name('templates.select');

    Route::get('/portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])
        ->whereNumber('portfolio')
        ->name('portfolios.edit');
    Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])
        ->whereNumber('portfolio')
        ->name('portfolios.update');
    Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])
        ->whereNumber('portfolio')
        ->name('portfolios.destroy');
});

Route::get('/portfolios/{portfolio}/preview', [PortfolioController::class, 'preview'])
    ->whereNumber('portfolio')
    ->name('portfolios.preview');

Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show'])
    ->whereNumber('portfolio')
    ->name('portfolios.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.submit');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('password.update');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
