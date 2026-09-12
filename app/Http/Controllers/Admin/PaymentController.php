<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['subOrder.order.user', 'subOrder.store'])
            ->latest()
            ->paginate(15);

        return view('admin.payments', compact('payments'));
    }

    public function verify(Payment $payment)
    {
        abort_if($payment->method !== 'transfer', 422, 'Hanya pembayaran transfer yang diverifikasi manual.');
        abort_if($payment->status !== 'pending', 422, 'Pembayaran sudah diproses.');

        PaymentService::markAsSettled($payment, null, auth()->id());

        return back()->with('success', "Pembayaran {$payment->payment_code} dikonfirmasi.");
    }

    public function fail(Payment $payment)
    {
        abort_if($payment->method !== 'transfer', 422, 'Hanya berlaku untuk transfer bank.');
        abort_if($payment->status !== 'pending', 422, 'Pembayaran sudah diproses.');

        $payment->update(['status' => 'failed']);

        return back()->with('success', "Pembayaran {$payment->payment_code} ditolak.");
    }
}