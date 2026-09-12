<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = auth()->user()->cartItems()
            ->with(['product.store', 'product.images'])
            ->latest()
            ->get()
            ->filter(fn ($i) => $i->product && $i->product->store)
            ->groupBy(fn ($i) => $i->product->store_id);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Keranjang Anda kosong.');
        }

        $stores = \App\Models\Store::whereIn('id', $items->keys())->get()->keyBy('id');

        $totals = [];
        foreach ($items as $storeId => $storeItems) {
            $subtotal = (float) $storeItems->sum(fn ($i) => (float) $i->product->price * $i->quantity);
            $totals[$storeId] = [
                'subtotal' => $subtotal,
                'shipping' => (float) $stores[$storeId]->shipping_cost,
                'total' => $subtotal + (float) $stores[$storeId]->shipping_cost,
            ];
        }

        return view('checkout.index', compact('items', 'stores', 'totals'));
    }

    public function store(Request $request)
    {
        $items = auth()->user()->cartItems()->with('product.store')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Keranjang Anda kosong.');
        }

        $storeIds = $items->map(fn ($i) => $i->product->store_id)->unique();

        $data = $request->validate([
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:25'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'payment' => ['required', 'array'],
            'payment.*' => ['required', 'in:cod,transfer,midtrans'],
        ]);

        foreach ($storeIds as $storeId) {
            if (!isset($request->payment[$storeId])) {
                throw ValidationException::withMessages([
                    'payment' => 'Pilih metode pembayaran untuk setiap toko.',
                ]);
            }
        }

        try {
            $order = OrderService::createFromSelection(
                user: auth()->user(),
                lines: $items->pluck('id')->all(),
                shipping: $request->only(['receiver_name', 'receiver_phone', 'shipping_address', 'city', 'province']),
                payments: is_array($request->payment) ? $request->payment : [],
            );

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}