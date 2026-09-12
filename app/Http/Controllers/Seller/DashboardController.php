<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('seller.store.edit')->with('info', 'Daftarkan toko Anda terlebih dahulu.');
        }

        $subOrders = SubOrder::where('store_id', $store->id)->latest()->take(5)->get()->load(['order', 'payment']);

        return view('seller.dashboard', [
            'store' => $store,
            'productCount' => $store->products()->where('is_active', true)->count(),
            'orderCount' => SubOrder::where('store_id', $store->id)->count(),
            'pendingPayments' => SubOrder::where('store_id', $store->id)
                ->where('status', 'payment_pending')->count(),
            'incomingCount' => SubOrder::where('store_id', $store->id)
                ->where('status', 'processing')->count(),
            'revenue' => $store->subOrders()
                ->whereHas('payment', fn ($q) => $q->whereIn('status', ['paid', 'confirmed']))
                ->sum('total'),
            'recentSubOrders' => $subOrders,
        ]);
    }
}