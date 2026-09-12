<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();

        $featured = Product::with(['store', 'images'])
            ->where('is_active', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->orderByDesc('sold_count')
            ->take(8)
            ->get();

        $stores = Store::where('is_active', true)
            ->withCount('products')
            ->orderBy('created_at')
            ->take(6)
            ->get();

        $newArrivals = Product::with(['store', 'images'])
            ->where('is_active', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->take(8)
            ->get();

        return view('home.index', compact('categories', 'featured', 'stores', 'newArrivals'));
    }
}