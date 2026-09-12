<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'receiver_name', 'receiver_phone',
        'shipping_address', 'city', 'province', 'total_items', 'grand_total', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'warning',
            'shipped' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu',
        };
    }
}