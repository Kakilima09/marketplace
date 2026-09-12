<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function storeOrAbort(): \App\Models\Store
    {
        $store = auth()->user()->store;
        abort_if(!$store, 403, 'Anda belum memiliki toko.');

        return $store;
    }

    public function index(Request $request)
    {
        $store = $this->storeOrAbort();

        $query = SubOrder::with(['order.user', 'payment'])
            ->where('store_id', $store->id);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $subOrders = $query->latest()->paginate(10)->withQueryString();

        return view('seller.orders.index', compact('store', 'subOrders'));
    }

    public function show(SubOrder $subOrder)
    {
        $store = $this->storeOrAbort();
        abort_unless($subOrder->store_id === $store->id, 403);

        $subOrder->load(['order', 'items.product', 'payment']);

        return view('seller.orders.show', compact('store', 'subOrder'));
    }

    public function ship(Request $request, SubOrder $subOrder)
    {
        $store = $this->storeOrAbort();
        abort_unless($subOrder->store_id === $store->id, 403);

        abort_if($subOrder->status === 'delivered' || $subOrder->status === 'cancelled', 422, 'Pesanan tidak bisa dikirim diperbarui.');

        if (!$subOrder->payment_settled && $subOrder->payment?->method !== 'cod') {
            abort(422, 'Pembayaran belum lunas. Tunggu konfirmasi pembayaran.');
        }

        $data = $request->validate([
            'courier' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:100'],
        ]);

        $subOrder->update([
            'status' => 'shipped',
            'courier' => $data['courier'],
            'tracking_number' => $data['tracking_number'],
        ]);

        OrderService::refreshOrderStatus($subOrder->order);

        return back()->with('success', 'Pesanan ditandai dikirim.');
    }

    public function deliver(SubOrder $subOrder)
    {
        $store = $this->storeOrAbort();
        abort_unless($subOrder->store_id === $store->id, 403);

        abort_if($subOrder->status !== 'shipped', 422, 'Pesanan harus berstatus dikirim terlebih dahulu.');

        $subOrder->update(['status' => 'delivered']);

        // Khusus COD, pembayaran lunas saat barang diterima.
        if ($subOrder->payment?->method === 'cod') {
            PaymentService::markCodPaid($subOrder);
        }

        OrderService::refreshOrderStatus($subOrder->order);

        return back()->with('success', 'Pesanan ditandai selesai diterima pembeli.');
    }
}