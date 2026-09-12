@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <p class="inline-flex items-center gap-2 text-sm bg-white/10 rounded-full px-3 py-1 mb-4">
                    &nbsp;Marketplace untuk UMKM Indonesia
                </p>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight">
                    Belanja dari banyak toko,<br>sekali checkout.
                </h1>
                <p class="mt-4 text-white/80 text-base sm:text-lg max-w-lg leading-relaxed">
                    Kumpulkan produk dari berbagai UMKM dalam satu keranjang. Checkout otomatis dipecah per toko — setiap seller mengurus pesanannya sendiri.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50">
                        Mulai Belanja
                    </a>
                    <a href="{{ route('stores.index') }}" class="px-6 py-3 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20">
                        Lihat Toko
                    </a>
                </div>
            </div>
            <div class="hidden lg:grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-5 border border-white/10">
                    <p class="text-3xl font-extrabold">{{ $stores->count() }}</p>
                    <p class="text-sm text-white/75 mt-1">Toko UMKM terverifikasi</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-5 border border-white/10 mt-8">
                    <p class="text-3xl font-extrabold">{{ number_format($featured->sum('sold_count')) }}+</p>
                    <p class="text-sm text-white/75 mt-1">Produk terjual</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-5 border border-white/10">
                    <p class="text-3xl font-extrabold">COD</p>
                    <p class="text-sm text-white/75 mt-1">Transfer & E-Wallet</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-5 border border-white/10 mt-8">
                    <p class="text-3xl font-extrabold">1x</p>
                    <p class="text-sm text-white/75 mt-1">Checkout banyak seller</p>
                </div>
            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h2 class="text-xl font-bold text-gray-900">Kategori</h2>
            <div class="mt-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="bg-white rounded-2xl border border-gray-200 p-4 text-center hover:border-emerald-300 hover:shadow-sm transition-all">
                        <span class="text-3xl">{{ $category->icon }}</span>
                        <p class="mt-2 text-sm font-medium text-gray-800 leading-tight">{{ $category->name }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Produk Terlaris</h2>
                <p class="text-sm text-gray-500">Paling banyak dibeli pembeli</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline">Lihat semua →</a>
        </div>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($featured as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">UMKM Bergabung</h2>
                <p class="text-sm text-gray-500">Toko terbaru di marketplace kami</p>
            </div>
            <a href="{{ route('stores.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline">Semua toko →</a>
        </div>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($stores as $store)
                <a href="{{ route('stores.show', $store) }}" class="bg-white rounded-2xl border border-gray-200 p-5 text-center hover:border-emerald-300 hover:shadow-sm transition-all">
                    <div class="mx-auto w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center text-xl font-extrabold uppercase">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                    <p class="mt-3 text-sm font-semibold text-gray-900 line-clamp-1">{{ $store->name }}</p>
                    <p class="text-xs text-gray-500">{{ $store->city }}</p>
                    <p class="mt-1 text-xs text-emerald-700 font-medium">{{ $store->products_count }} produk</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-14">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Baru Masuk</h2>
                <p class="text-sm text-gray-500">Produk terbaru dari para seller</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline">Lihat semua →</a>
        </div>
        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endsection