<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Spatie\Permission\Middlewares\RoleMiddleware;

// ======================================================
// Public Pages
// ======================================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/shop', [PageController::class, 'shop'])->name('shop.index');
Route::get('/shop/{product}', [PageController::class, 'showProduct'])->name('shop.single');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// ======================================================
// Authentication Pages (Views Only)
// ======================================================
Route::view('/register', 'auth.register');
Route::view('/login', 'auth.login');

// ======================================================
// Authentication Logic (Controller Actions)
// ======================================================
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

// ======================================================
// Admin Routes (Protected)
// ======================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/dashboard', '/admin/products')->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
    });


// ======================================================
// Cart Routes
// ======================================================
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/', [CartController::class, 'store'])->name('store');
    Route::put('/{productId}', [CartController::class, 'update'])->name('update');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::delete('/{productId}', [CartController::class, 'destroy'])->name('destroy');
});

// ======================================================
// Checkout & Orders Routes (Auth Required)
// ======================================================
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
