<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['store', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.products', compact('products'));
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Status produk diperbarui.');
    }
}