<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/shop', function () {
    return view('shop');
})->name('shop.index');

Route::get('/shop/{product}', function ($product) {
    return view('shop-single');
})->name('shop.single');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
