<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    protected $fillable = [
        'order_id', 'store_id', 'sub_order_code', 'subtotal', 'shipping_cost',
        'total', 'status', 'courier', 'tracking_number',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getMethodLabelAttribute(): string
    {
        $method = $this->payment?->method;

        return match ($method) {
            'transfer' => 'Transfer Bank',
            'midtrans' => 'Midtrans / E-Wallet',
            'cod' => 'COD',
            default => '—',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'payment_pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses Seller',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'payment_pending' => 'warning',
            'processing' => 'secondary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentSettledAttribute(): bool
    {
        return in_array($this->payment?->status, ['paid', 'confirmed']);
    }
}