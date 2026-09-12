@extends('layouts.app')

@section('title', $store->name)

@section('content')
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a>
                <span class="mx-1">/</span>
                <a href="{{ route('stores.index') }}" class="hover:text-emerald-700">Toko</a>
                <span class="mx-1">/</span>
                <span class="text-gray-800 font-medium">{{ $store->name }}</span>
            </nav>
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-emerald-100 text-emerald-700 inline-flex items-center justify-center text-3xl font-extrabold uppercase">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $store->name }}</h1>
                    <p class="text-sm text-gray-500">📍 {{ $store->city }}, {{ $store->province }} • Ongkir flat {{ rupiah($store->shipping_cost) }}</p>
                    @if ($store->description)
                        <p class="mt-2 text-sm text-gray-600 max-w-2xl">{{ $store->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Produk Toko ({{ $products->total() }})</h2>

        @if ($products->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <p class="text-4xl mb-3">📦</p>
                <p class="font-semibold text-gray-900">Belum ada produk dari toko ini</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-6">{{ $products->links() }}</div>
        @endif
    </section>
@endsection