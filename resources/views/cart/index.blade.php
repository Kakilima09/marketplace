@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-xl font-bold text-gray-900 mb-5">Keranjang Belanja</h1>

        @if ($items->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
                <p class="text-5xl mb-4">🛒</p>
                <p class="font-semibold text-gray-900 text-lg">Keranjang Anda kosong</p>
                <p class="text-sm text-gray-500 mt-1">Yuk isi dengan produk favorit dari banyak toko.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-5 px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700">
                    Jelajahi Produk
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($items as $storeId => $storeItems)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        @php $store = $stores[$storeId]; @endphp
                        <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center font-bold uppercase">{{ strtoupper(substr($store->name, 0, 1)) }}</span>
                            <a href="{{ route('stores.show', $store) }}" class="font-semibold text-gray-900 hover:text-emerald-700">{{ $store->name }}</a>
                            <span class="ml-auto text-xs text-gray-500">Ongkir {{ rupiah($store->shipping_cost) }}</span>
                        </div>

                        <ul class="divide-y divide-gray-100">
                            @foreach ($storeItems as $item)
                                <li class="px-5 py-4 flex items-center gap-4">
                                    @if ($item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" class="w-16 h-16 rounded-xl object-cover">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-2xl">📦</div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.show', $item->product) }}" class="font-medium text-gray-900 text-sm line-clamp-1 hover:text-emerald-700">{{ $item->product->name }}</a>
                                        <p class="text-sm text-emerald-700 font-bold mt-0.5">{{ rupiah($item->product->price) }}</p>
                                        <p class="text-xs text-gray-400">Stok: {{ $item->product->stock }}</p>
                                    </div>

                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                        <select name="quantity" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm w-20">
                                            @for ($i = 1; $i <= min(99, max($item->product->stock, 1)); $i++)
                                                <option value="{{ $i }}" @selected($item->quantity === $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </form>

                                    <p class="text-sm font-bold text-gray-900 w-24 text-right">{{ rupiah($item->subtotal) }}</p>

                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                        <button class="text-gray-400 hover:text-red-600 p-1.5" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 bg-white rounded-2xl border border-gray-200 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">Total item: <span class="font-semibold text-gray-900">{{ auth()->user()->cartItems->sum('quantity') }}</span></p>
                    <p class="text-sm text-gray-500">Total belanja (sebelum ongkir): <span class="font-bold text-xl text-emerald-700">{{ rupiah($items->flatten()->sum('subtotal')) }}</span></p>
                </div>
                <a href="{{ route('checkout.index') }}" class="px-8 py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                    Checkout Sekarang →
                </a>
            </div>
        @endif
    </section>
@endsection