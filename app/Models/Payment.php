<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sub_order_id', 'payment_code', 'method', 'amount', 'status',
        'bank_name', 'account_name', 'account_number', 'proof_image',
        'gateway_reference', 'paid_at', 'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function subOrder()
    {
        return $this->belongsTo(SubOrder::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'Sudah Dibayar',
            'confirmed' => 'Pembayaran Dikonfirmasi',
            'failed' => 'Gagal',
            default => 'Menunggu Pembayaran',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'success',
            'confirmed' => 'success',
            'failed' => 'danger',
            default => 'warning',
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'transfer' => 'Transfer Bank',
            'midtrans' => 'Midtrans / E-Wallet',
            'cod' => 'COD (Bayar di Tempat)',
            default => $this->method,
        };
    }
}