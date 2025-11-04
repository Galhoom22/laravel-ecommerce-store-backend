<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;

/**
 * Handles public-facing pages such as home, shop, about, and contact.
 */
class PageController extends Controller
{
    /**
     * Display the home page.
     *
     * @return View
     */
    public function home(): View
    {
        return view('index');
    }

    /**
     * Display the about page.
     *
     * @return View
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Display the shop page with product listing, pagination, and search.
     *
     * @param  Request  $request
     * @return View
     */
    public function shop(Request $request): View
    {
        // Base query for products
        $query = Product::query();

        // Optional search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
        }

        // Paginate products (15 per page)
        $products = $query->latest()->paginate(15);

        // Preserve search term in pagination links
        $products->appends($request->only('search'));

        // Return view with paginated results
        return view('shop', compact('products'));
    }

    /**
     * Display a single product page.
     *
     * @param  Product  $product
     * @return View
     */
    public function showProduct(Product $product): View
    {
        return view('shop-single', compact('product'));
    }

    /**
     * Display the contact page.
     *
     * @return View
     */
    public function contact(): View
    {
        return view('contact');
    }
}
