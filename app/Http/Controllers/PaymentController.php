<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Payment $payment)
    {
        $this->authorizePayment($payment);

        $payment->load(['subOrder.order', 'subOrder.store', 'subOrder.items.product']);

        return view('payments.show', compact('payment'));
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        $this->authorizePayment($payment);

        abort_if($payment->method !== 'transfer', 422, 'Metode ini tidak memerlukan bukti transfer.');
        abort_if(!in_array($payment->status, ['pending']), 422, 'Pembayaran sudah diproses.');

        $data = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:30'],
            'proof_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $payment->update([
            'bank_name' => $data['bank_name'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'proof_image' => $request->file('proof_image')->store('proofs', 'public'),
        ]);

        return back()->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }

    /**
     * Simulasi sukses pembayaran gateway untuk pengembangan tanpa API key asli.
     */
    public function simulateSuccess(Payment $payment)
    {
        $this->authorizePayment($payment);

        abort_if($payment->method !== 'midtrans', 422, 'Hanya berlaku untuk pembayaran Midtrans.');
        abort_if(!in_array($payment->status, ['pending']), 422, 'Pembayaran sudah diproses.');

        PaymentService::markAsSettled($payment, 'SIM-'.now()->format('Ymd-Hi'));

        return back()->with('success', 'Pembayaran simulasi berhasil dikonfirmasi.');
    }

    private function authorizePayment(Payment $payment): void
    {
        abort_unless($payment->subOrder->order->user_id === auth()->id(), 403);
    }
}