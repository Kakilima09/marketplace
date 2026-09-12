<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totals' => [
                'users' => User::count(),
                'sellers' => User::where('role', 'seller')->count(),
                'stores' => Store::count(),
                'storesPending' => Store::where('is_active', false)->count(),
                'products' => Product::count(),
                'orders' => Order::count(),
                'revenue' => Order::where('status', '!=', 'cancelled')->sum('grand_total'),
                'paymentsPending' => Payment::where('status', 'pending')
                    ->whereHas('subOrder.store')->count(),
                'paymentsToVerify' => Payment::where('method', 'transfer')->where('status', 'pending')->count(),
            ],
            'pendingStores' => Store::where('is_active', false)->with('owner')->latest()->take(5)->get(),
            'recentOrders' => Order::with('user')->latest()->take(5)->get(),
        ]);
    }
}