<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()
            ->with(['product.store', 'product.images'])
            ->latest()
            ->get()
            ->filter(fn ($i) => $i->product && $i->product->store)
            ->groupBy(fn ($i) => $i->product->store_id);

        $stores = \App\Models\Store::whereIn('id', $items->keys())->get()->keyBy('id');

        return view('cart.index', compact('items', 'stores'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::with('store')->findOrFail($data['product_id']);

        if (!$product->store->is_active) {
            return back()->with('error', 'Toko sedang non-aktif, produk tidak bisa ditambahkan.');
        }

        $existing = auth()->user()->cartItems()->where('product_id', $product->id)->first();

        if ($existing) {
            $newQty = $existing->quantity + $data['quantity'];
            abort_if($newQty > $product->stock, 422, 'Stok tidak mencukupi.');
            $existing->update(['quantity' => $newQty]);
        } else {
            abort_if($data['quantity'] > $product->stock, 422, 'Stok tidak mencukupi.');
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function addAndCheckout(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::with('store')->findOrFail($data['product_id']);
        if (!$product->store->is_active) {
            return back()->with('error', 'Toko sedang non-aktif.');
        }

        $existing = auth()->user()->cartItems()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $data['quantity']]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
            ]);
        }

        return redirect()->route('checkout.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => ['required', 'exists:cart_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $item = auth()->user()->cartItems()->findOrFail($request->cart_item_id);
        abort_if($request->quantity > $item->product->stock, 422, 'Stok tidak mencukupi.');

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function remove(Request $request)
    {
        $request->validate(['cart_item_id' => ['required', 'exists:cart_items,id']]);
        auth()->user()->cartItems()->findOrFail($request->cart_item_id)->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}