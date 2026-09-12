@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="text-sm text-gray-500 mb-5">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a>
            <span class="mx-1">/</span>
            @if ($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-emerald-700">{{ $product->category->name }}</a>
                <span class="mx-1">/</span>
            @endif
            <span class="text-gray-800 font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-2">
            <div>
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
                    @else
                        <div class="w-full aspect-square flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400 text-6xl">📦</div>
                    @endif
                </div>
                @if ($product->images->count() > 1)
                    <div class="mt-3 flex gap-2 overflow-x-auto">
                        @foreach ($product->images as $img)
                            <img src="{{ asset('storage/'.$img->path) }}" class="w-20 h-20 rounded-xl border object-cover {{ $img->is_primary ? 'border-emerald-500' : 'border-gray-200' }}">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">{{ $product->store->name }}</p>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
                    <div class="mt-2 flex items-center gap-3 text-sm">
                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 rounded-full px-2.5 py-0.5 font-semibold">
                            ★ {{ $product->rating_avg > 0 ? number_format($product->rating_avg, 1) : 'Baru' }}
                        </span>
                        <span class="text-gray-500">{{ $product->rating_count }} ulasan</span>
                        <span class="text-gray-500">{{ $product->sold_count }} terjual</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <p class="text-2xl sm:text-3xl font-extrabold text-emerald-700">{{ rupiah($product->price) }}</p>
                    <p class="mt-2 text-sm text-gray-500">
                        Stok: <span class="font-semibold text-gray-800 {{ $product->stock > 0 ? '' : 'text-red-600' }}">{{ $product->stock > 0 ? number_format($product->stock) : 'Habis' }}</span>
                    </p>

                    <form method="POST" action="{{ route('cart.add') }}" class="mt-4" x-data="{ qty: 1 }">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex items-center gap-3">
                            <label class="text-sm text-gray-600">Jumlah:</label>
                            <select name="quantity" x-model="qty" class="rounded-lg border-gray-300 text-sm w-24">
                                @for ($i = 1; $i <= min(99, max($product->stock, 1)); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        @auth
                            @if ($product->stock > 0)
                                <div class="mt-4 flex flex-col sm:flex-row gap-2">
                                    <button type="submit" class="flex-1 px-5 py-3 bg-white border-2 border-emerald-600 text-emerald-700 font-bold rounded-xl hover:bg-emerald-50">
                                        + Keranjang
                                    </button>
                                </div>
                            @else
                                <div class="mt-4 px-5 py-3 bg-gray-100 text-center text-gray-500 font-semibold rounded-xl">Stok Habis</div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="mt-4 block text-center px-5 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700">
                                Masuk untuk Beli
                            </a>
                        @endauth

                        @auth
                            @if ($product->stock > 0)
                                <button type="submit" formaction="{{ route('cart.addAndCheckout') }}" class="mt-2 w-full px-5 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700">
                                    Beli Sekarang
                                </button>
                            @endif
                        @endauth
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h2 class="font-bold text-gray-900">Deskripsi</h2>
                    <p class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->description ?: 'Belum ada deskripsi.' }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center font-extrabold text-lg uppercase">{{ strtoupper(substr($product->store->name, 0, 1)) }}</div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-sm">{{ $product->store->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->store->city }}, {{ $product->store->province }}</p>
                            <p class="text-xs text-emerald-700 mt-0.5">Ongkir flat {{ rupiah($product->store->shipping_cost) }}</p>
                        </div>
                        <a href="{{ route('stores.show', $product->store) }}" class="text-sm font-semibold text-emerald-700 hover:underline">Kunjungi toko</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <h2 class="text-lg font-bold text-gray-900">Ulasan Pembeli</h2>

            @if ($product->reviews->isNotEmpty())
                <div class="mt-4 space-y-3">
                    @foreach ($product->reviews as $review)
                        <div class="bg-white rounded-2xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 inline-flex items-center justify-center font-bold uppercase">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $review->user->name }}</p>
                                        <p class="text-xs text-amber-500">
                                            @for ($i = 1; $i <= 5; $i++)
                                                {{ $i <= $review->rating ? '★' : '☆' }}
                                            @endfor
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                            @if ($review->comment)
                                <p class="mt-3 text-sm text-gray-700">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-4 bg-white rounded-2xl border border-gray-200 p-8 text-center text-sm text-gray-500">Belum ada ulasan untuk produk ini.</p>
            @endif
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-lg font-bold text-gray-900">Produk lain dari toko ini</h2>
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($related as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection