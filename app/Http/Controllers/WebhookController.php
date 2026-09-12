<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Notifikasi pembayaran Midtrans.
     * Untuk integrasi produksi: verifikasi signature key dari config('midtrans.server_key').
     */
    public function midtrans(Request $request)
    {
        logger()->info('Midtrans webhook', $request->all());

        $orderId = $request->input('order_id');
        $status = $request->input('transaction_status');
        $ref = $request->input('transaction_id');

        if (!$orderId) {
            return response()->json(['message' => 'order_id required'], 422);
        }

        $payment = Payment::where('payment_code', $orderId)
            ->orWhere('gateway_reference', $orderId)
            ->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if (in_array($status, ['capture', 'settlement', 'success']) && $payment->status === 'pending') {
            PaymentService::markAsSettled($payment, $ref);
        } elseif (in_array($status, ['cancel', 'deny', 'expire', 'failure'])) {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'ok']);
    }
}