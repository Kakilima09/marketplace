<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('owner')
            ->withCount('products')
            ->latest()
            ->paginate(15);

        return view('admin.stores', compact('stores'));
    }

    public function approve(Store $store)
    {
        $store->update(['is_active' => true]);

        return back()->with('success', "Toko {$store->name} telah diaktifkan.");
    }

    public function deactivate(Store $store)
    {
        $store->update(['is_active' => false]);

        return back()->with('success', "Toko {$store->name} telah dinonaktifkan.");
    }
}