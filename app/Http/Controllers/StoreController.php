<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->paginate(12);

        return view('stores.index', compact('stores'));
    }

    public function show(Store $store)
    {
        abort_if(!$store->is_active, 404);

        $products = Product::with(['images'])
            ->where('store_id', $store->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('stores.show', compact('store', 'products'));
    }
}