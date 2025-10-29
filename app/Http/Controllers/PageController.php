<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the home page.
     */
    public function home(): View
    {
        return view('index');
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Display the shop page.
     */
    public function shop(): View
    {
        return view('shop');
    }

    /**
     * Display a single product page.
     */
    public function showProduct(string $product): View
    {
        return view('shop-single', compact('product'));
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        return view('contact');
    }
}
