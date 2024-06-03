<?php

use App\Http\Controllers\ActionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsController;

Route::get('/', [ViewsController::class, 'index'])->name('index');

Route::get('/login', [ViewsController::class, 'login'])
    ->name('login'
    )->middleware('guest');

Route::post('/login', [ActionsController::class, 'login']);

Route::get('/logout', [ActionsController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::get('/basket', [ViewsController::class, 'basket'])
    ->name('basket')
    ->middleware('auth');

Route::post('/cart/add/{product}', [ActionsController::class, 'addToCart'])
    ->name('cart.add')
    ->middleware('auth');

Route::post('/cart/decrease/{product}', [ActionsController::class, 'cart_decrease'])
    ->name('cart.decrease')
    ->middleware('auth');

Route::post('/cart/increase/{product}', [ActionsController::class, 'cart_increase'])
    ->name('cart.increase')
    ->middleware('auth');
Route::post('/cart/checkout', [ActionsController::class, 'cart_checkout'])
    ->name('cart.checkout')
    ->middleware('auth');
