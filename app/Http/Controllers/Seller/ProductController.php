<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private function storeOrAbort(): \App\Models\Store
    {
        $store = auth()->user()->store;

        abort_if(!$store, 403, 'Anda belum memiliki toko.');

        return $store;
    }

    public function index()
    {
        $store = $this->storeOrAbort();

        $products = $store->products()
            ->with(['images', 'category'])
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('store', 'products'));
    }

    public function create()
    {
        $store = $this->storeOrAbort();
        $categories = Category::where('is_active', true)->get();

        return view('seller.products.form', compact('store', 'categories'));
    }

    public function store(Request $request)
    {
        $store = $this->storeOrAbort();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $product = $store->products()->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(5)),
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $store->is_active,
        ]);

        foreach ($request->file('images') as $index => $file) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $file->store('products', 'public'),
                'is_primary' => $index === 0,
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $store = $this->storeOrAbort();
        abort_unless($store->id === $product->store_id, 403);

        $categories = Category::where('is_active', true)->get();
        $product->load('images');

        return view('seller.products.form', compact('store', 'product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $store = $this->storeOrAbort();
        abort_unless($store->id === $product->store_id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $file->store('products', 'public'),
                ]);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $store = $this->storeOrAbort();
        abort_unless($store->id === $product->store_id, 403);

        foreach ($product->images as $img) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($img->path);
        }

        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }

    public function toggle(Product $product)
    {
        $store = $this->storeOrAbort();
        abort_unless($store->id === $product->store_id, 403);

        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Status produk diperbarui.');
    }
}