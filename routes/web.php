<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;

// Public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/shop', [PageController::class, 'shop'])->name('shop.index');
Route::get('/shop/{product}', [PageController::class, 'showProduct'])->name('shop.single');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Authentication pages (views only)
Route::view('/register', 'auth.register');
Route::view('/login', 'auth.login');

// Authentication actions (logic)
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
});
