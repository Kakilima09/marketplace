<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public static function makeOrderCode(): string
    {
        return 'ORD-'.date('ymd').'-'.strtoupper(Str::random(6));
    }

    public static function makeSubOrderCode(string $orderCode, int $index): string
    {
        return $orderCode.'-'.($index + 1);
    }

    public static function makePaymentCode(): string
    {
        return 'PAY-'.date('ymd').'-'.strtoupper(Str::random(6));
    }

    /**
     * Convert cart into a parent order with one sub-order per seller.
     *
     * @param  array<int, array{product_id:int, quantity:int}>  $lines
     * @param  array<int|string, string>  $payments  map store_id => method
     */
    public static function createFromSelection(User $user, array $lines, array $shipping, array $payments): Order
    {
        return DB::transaction(function () use ($user, $lines, $shipping, $payments) {
            $cartItems = $user->cartItems()
                ->with('product.store')
                ->whereIn('id', $lines)
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \RuntimeException('Keranjang belanja kosong.');
            }

            $grouped = $cartItems->groupBy(fn ($item) => $item->product->store_id);

            $orderCode = self::makeOrderCode();
            $totalItems = 0;
            $grandTotal = 0;

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user->id,
                'receiver_name' => $shipping['receiver_name'],
                'receiver_phone' => $shipping['receiver_phone'],
                'shipping_address' => $shipping['shipping_address'],
                'city' => $shipping['city'],
                'province' => $shipping['province'],
            ]);

            $index = 0;
            foreach ($grouped as $storeId => $items) {
                /** @var \App\Models\Store $store */
                $store = $items->first()->product->store;

                $subtotal = (float) $items->sum(fn ($i) => (float) $i->product->price * $i->quantity);
                $totalItems += $items->sum('quantity');

                $method = $payments[$store->id] ?? 'transfer';

                $subOrder = SubOrder::create([
                    'order_id' => $order->id,
                    'store_id' => $store->id,
                    'sub_order_code' => self::makeSubOrderCode($orderCode, $index),
                    'subtotal' => $subtotal,
                    'shipping_cost' => (float) $store->shipping_cost,
                    'total' => $subtotal + (float) $store->shipping_cost,
                    // COD langsung bisa diproses seller tanpa menunggu pembayaran online.
                    'status' => $method === 'cod' ? 'processing' : 'payment_pending',
                ]);

                foreach ($items as $item) {
                    $product = $item->product;

                    if ($product->stock < $item->quantity) {
                        throw new \RuntimeException("Stok {$product->name} tidak mencukupi.");
                    }

                    OrderItem::create([
                        'sub_order_id' => $subOrder->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => (float) $product->price,
                        'quantity' => $item->quantity,
                        'subtotal' => (float) $product->price * $item->quantity,
                    ]);

                    $product->decrement('stock', $item->quantity);
                    $product->increment('sold_count', $item->quantity);
                }

                Payment::create([
                    'sub_order_id' => $subOrder->id,
                    'payment_code' => self::makePaymentCode(),
                    'method' => $method,
                    'amount' => (float) $subOrder->total,
                    'status' => 'pending',
                ]);

                $grandTotal += (float) $subOrder->total;
                $index++;
            }

            $order->update([
                'total_items' => $totalItems,
                'grand_total' => $grandTotal,
            ]);

            $user->cartItems()->whereIn('id', $cartItems->pluck('id'))->delete();

            return $order;
        });
    }

    public static function refreshOrderStatus(Order $order): void
    {
        $statuses = $order->subOrders()->pluck('status')->all();

        if (empty($statuses)) {
            return;
        }

        if (collect($statuses)->every(fn ($s) => $s === 'cancelled')) {
            $order->update(['status' => 'cancelled']);
        } elseif (collect($statuses)->every(fn ($s) => $s === 'delivered')) {
            $order->update(['status' => 'completed']);
        } elseif (collect($statuses)->contains('shipped')) {
            $order->update(['status' => 'shipped']);
        } elseif (collect($statuses)->contains('delivered')) {
            $order->update(['status' => 'shipped']);
        } elseif (collect($statuses)->contains(fn ($s) => in_array($s, ['processing', 'payment_pending']))) {
            $order->update(['status' => 'processing']);
        }
    }

    public static function markSubOrderProcessing(SubOrder $subOrder): void
    {
        if ($subOrder->status === 'payment_pending') {
            $subOrder->update(['status' => 'processing']);
        }

        self::refreshOrderStatus($subOrder->order);
    }
}