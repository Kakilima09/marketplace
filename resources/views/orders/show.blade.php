@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="text-sm text-gray-500 mb-4">
            <a href="{{ route('orders.index') }}" class="hover:text-emerald-700">← Kembali ke Pesanan</a>
        </nav>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-5">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Pesanan #{{ $order->order_code }}</h1>
                    <p class="text-sm text-gray-500">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="inline-flex px-3 py-1.5 rounded-full text-sm font-semibold text-white bg-{{ $order->status_badge }}-500">{{ $order->status_label }}</span>
            </div>

            <div class="mt-5 grid sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-xs uppercase font-semibold text-gray-400">Penerima</p>
                    <p class="font-medium text-gray-900">{{ $order->receiver_name }}</p>
                    <p class="text-gray-500">{{ $order->receiver_phone }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs uppercase font-semibold text-gray-400">Alamat Pengiriman</p>
                    <p class="text-gray-700">{{ $order->shipping_address }}</p>
                    <p class="text-gray-500">{{ $order->city }}, {{ $order->province }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            @foreach ($order->subOrders as $sub)
                @php
                    $payable = $sub->payment && in_array($sub->payment->status, ['pending']) && $sub->status === 'payment_pending';
                    $needProof = $sub->payment?->method === 'transfer' && $payable;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100 flex items-center gap-3 flex-wrap">
                        <a href="{{ route('stores.show', $sub->store) }}" class="font-semibold text-gray-900 text-sm hover:text-emerald-700">{{ $sub->store->name }}</a>
                        <span class="text-xs text-gray-500">{{ $sub->sub_order_code }}</span>
                        <span class="ml-auto inline-flex px-2.5 py-1 rounded-full text-xs font-semibold text-white bg-{{ $sub->status_badge }}-500">{{ $sub->status_label }}</span>
                    </div>

                    <ul class="divide-y divide-gray-50 px-5">
                        @foreach ($sub->items as $item)
                            <li class="py-3 flex gap-3 items-center" id="sub-item-{{ $item->id }}">
                                @if ($item->product)
                                    <a href="{{ route('products.show', $item->product) }}">
                                        @if ($item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" class="w-14 h-14 rounded-xl object-cover">
                                        @else
                                            <span class="w-14 h-14 rounded-xl bg-gray-100 inline-flex items-center justify-center">📦</span>
                                        @endif
                                    </a>
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                    <p class="text-xs text-gray-500">{{ rupiah($item->price) }} × {{ $item->quantity }}</p>
                                    @if ($item->product)
                                        @php
                                            $canReview = $sub->status === 'delivered';
                                            $alreadyReviewed = auth()->user()->reviews()->where('product_id', $item->product_id)->where('sub_order_id', $sub->id)->exists();
                                        @endphp
                                        @if ($canReview && !$alreadyReviewed)
                                            <button type="button" x-data x-on:click="$el.closest('li').querySelector('.review-form').classList.remove('hidden')" class="mt-1 text-xs font-semibold text-emerald-700 hover:underline">Beri ulasan</button>
                                        @elseif ($alreadyReviewed)
                                            <span class="mt-1 inline-block text-xs text-gray-400">★ Sudah diulas</span>
                                        @endif

                                        @if ($canReview && !$alreadyReviewed)
                                            <form method="POST" action="{{ route('reviews.store') }}" class="review-form hidden mt-2 bg-gray-50 rounded-xl p-3 space-y-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="sub_order_id" value="{{ $sub->id }}">
                                                <div class="flex items-center gap-2 text-xl" x-data="{ rating: 5 }">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <button type="button" x-on:click="rating = {{ $i }}" x-text="rating >= {{ $i }} ? '★' : '☆'" class="text-amber-400"></button>
                                                    @endfor
                                                    <input type="hidden" name="rating" :value="rating">
                                                </div>
                                                <textarea name="comment" rows="2" placeholder="Komentar Anda (opsional)..." class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700">Kirim Ulasan</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-gray-900">{{ rupiah($item->subtotal) }}</p>
                            </li>
                        @endforeach
                    </ul>

                    @if ($sub->courier)
                        <div class="mx-5 my-3 bg-sky-50 border border-sky-100 rounded-xl px-4 py-3 text-sm text-sky-800">
                            🚚 Dikirim via <strong>{{ $sub->courier }}</strong> — No. Resi: <strong>{{ $sub->tracking_number }}</strong>
                        </div>
                    @endif

                    <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
                        <div class="text-sm space-y-0.5">
                            <p class="text-gray-500">Subtotal <span class="float-right ml-8">{{ rupiah($sub->subtotal) }}</span></p>
                            <p class="text-gray-500">Ongkir <span class="float-right ml-8">{{ rupiah($sub->shipping_cost) }}</span></p>
                            <p class="font-bold text-gray-900 text-base">Total <span class="float-right ml-8">{{ rupiah($sub->total) }}</span></p>
                            <p class="pt-1 text-xs text-gray-500">Pembayaran: <strong>{{ $sub->method_label }}</strong> —
                                @php
                                    $ps = $sub->payment?->status;
                                    $label = match ($ps) {
                                        'paid' => 'Sudah dibayar',
                                        'confirmed' => 'Dikonfirmasi admin',
                                        'failed' => 'Gagal',
                                        default => 'Menunggu pembayaran',
                                    };
                                @endphp
                                <span class="{{ $ps === 'paid' || $ps === 'confirmed' ? 'text-emerald-700' : ($ps === 'failed' ? 'text-red-600' : 'text-amber-600') }}">{{ $label }}</span>
                            </p>
                        </div>
                        <div>
                            @if ($payable && $needProof)
                                <a href="{{ route('payments.show', $sub->payment) }}" class="inline-flex px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700">Bayar Sekarang</a>
                            @elseif ($payable)
                                <a href="{{ route('payments.show', $sub->payment) }}" class="inline-flex px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700">Lihat Pembayaran</a>
                            @else
                                <span class="inline-flex px-4 py-2 text-xs text-gray-400">Tidak ada tindakan</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($order->grand_total > 0)
            <div class="mt-6 bg-white rounded-2xl border border-gray-200 p-6 text-right">
                <p class="text-sm text-gray-500">Total keseluruhan ({{ $order->subOrders->count() }} toko)</p>
                <p class="text-2xl font-extrabold text-emerald-700">{{ rupiah($order->grand_total) }}</p>
            </div>
        @endif
    </section>
@endsection