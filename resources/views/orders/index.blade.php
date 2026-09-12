@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-xl font-bold text-gray-900 mb-5">Pesanan Saya</h1>

        @if ($orders->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
                <p class="text-5xl mb-4">🧾</p>
                <p class="font-semibold text-gray-900 text-lg">Belum ada pesanan</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-5 px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700">Mulai Belanja</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-md transition-all overflow-hidden">
                        <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100 flex items-center gap-3 flex-wrap">
                            <span class="font-bold text-gray-900 text-sm">{{ $order->order_code }}</span>
                            <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <span class="ml-auto inline-flex px-2.5 py-1 rounded-full text-xs font-semibold text-white bg-{{ $order->status_badge }}-500">{{ $order->status_label }}</span>
                        </div>
                        <div class="px-5 py-4 flex items-center justify-between flex-wrap gap-3">
                            <div class="flex -space-x-3">
                                @foreach ($order->subOrders as $i => $sub)
                                    @if ($i < 4)
                                        <span class="w-9 h-9 rounded-full border-2 border-white bg-emerald-100 text-emerald-700 inline-flex items-center justify-center text-xs font-bold uppercase">{{ strtoupper(substr($sub->store->name, 0, 1)) }}</span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $order->subOrders->count() }} toko • {{ $order->total_items }} item</p>
                                <p class="font-bold text-emerald-700">{{ rupiah($order->grand_total) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">{{ $orders->links() }}</div>
        @endif
    </section>
@endsection