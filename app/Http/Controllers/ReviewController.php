<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\SubOrder;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'sub_order_id' => ['required', 'exists:sub_orders,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();

        $subOrder = SubOrder::where('id', $data['sub_order_id'])
            ->where('status', 'delivered')
            ->whereHas('order', fn ($q) => $q->where('user_id', $user->id))
            ->whereHas('items', fn ($q) => $q->where('product_id', $data['product_id']))
            ->whereDoesntHave('reviews', fn ($q) => $q->where('user_id', $user->id)->where('product_id', $data['product_id']))
            ->firstOrFail();

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'sub_order_id' => $data['sub_order_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $avg = (float) $product->reviews()->avg('rating');
        $product->update([
            'rating_avg' => round($avg, 2),
            'rating_count' => $product->reviews()->count(),
        ]);

        return back()->with('success', 'Terima kasih, ulasan Anda telah disimpan.');
    }
}