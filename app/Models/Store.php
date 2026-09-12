<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'logo', 'banner', 'description',
        'address', 'city', 'province', 'shipping_cost', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'shipping_cost' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Store $store) {
            $store->slug = $store->slug ?: Str::slug($store->name).'-'.Str::lower(Str::random(5));
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->products()->where('is_active', true);
    }

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/'.$this->logo) : null;
    }

    public function getRatingAvgAttribute()
    {
        return (float) $this->products()
            ->where('rating_count', '>', 0)
            ->avg('rating_avg') ?? 0;
    }
}