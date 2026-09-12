<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['store', 'images', 'category'])
            ->where('is_active', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true));

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($storeSlug = $request->query('store')) {
            $query->whereHas('store', fn ($q) => $q->where('slug', $storeSlug));
        }

        $sort = $request->query('sort', 'latest');
        $sortMap = [
            'latest' => ['id', 'desc'],
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'popular' => ['sold_count', 'desc'],
            'rating' => ['rating_avg', 'desc'],
        ];
        [$col, $dir] = $sortMap[$sort] ?? ['id', 'desc'];
        $query->orderBy($col, $dir);

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $request->query('category'),
            'sort' => $sort,
        ]);
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active || !$product->store->is_active, 404);

        $product->load(['images', 'store', 'category', 'reviews.user']);

        $related = Product::with(['store', 'images'])
            ->where('store_id', $product->store_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}