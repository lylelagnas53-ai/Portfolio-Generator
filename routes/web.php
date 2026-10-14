<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

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
Route::get('/portfolios/{portfolio}/preview', [PortfolioController::class, 'preview'])
    ->whereNumber('portfolio')
    ->name('portfolios.preview');

Route::get('/portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])
    ->whereNumber('portfolio')
    ->name('portfolios.edit');
Route::put('/portfolios/{portfolio}', [PortfolioController::class, 'update'])
    ->whereNumber('portfolio')
    ->name('portfolios.update');
Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])
    ->whereNumber('portfolio')
    ->name('portfolios.destroy');
Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show'])
    ->whereNumber('portfolio')
    ->name('portfolios.show');
