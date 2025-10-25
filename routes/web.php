<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/shop', function () {
    return 'Shop Page Placeholder'; // Temporary placeholder
})->name('shop.index');

Route::get('/contact', function () {
    return 'Contact Page Placeholder'; // Temporary placeholder
})->name('contact');
