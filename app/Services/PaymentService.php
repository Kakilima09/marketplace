<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\SubOrder;

class PaymentService
{
    /**
     * Tandai pembayaran sukses & geser sub-order ke status 'processing'.
     */
    public static function markAsSettled(Payment $payment, ?string $gatewayReference = null, ?int $verifiedBy = null): void
    {
        $payment->update([
            'status' => $verifiedBy ? 'confirmed' : 'paid',
            'gateway_reference' => $gatewayReference ?: $payment->gateway_reference,
            'verified_by' => $verifiedBy ?: $payment->verified_by,
            'verified_at' => $verifiedBy ? now() : $payment->verified_at,
            'paid_at' => now(),
        ]);

        OrderService::markSubOrderProcessing($payment->subOrder);
    }

    /**
     * Tandai selesai (untuk COD saat barang diterima).
     */
    public static function markCodPaid(SubOrder $subOrder): void
    {
        $payment = $subOrder->payment;

        if ($payment && $payment->method === 'cod' && $payment->status === 'pending') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
    }
}