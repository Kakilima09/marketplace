@extends('layouts.app')

@section('title', 'Daftar Toko')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-xl font-bold text-gray-900">Daftar Toko</h1>
        <p class="text-sm text-gray-500">{{ $stores->total() }} toko UMKM terverifikasi</p>

        @if ($stores->isEmpty())
            <div class="mt-6 bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <p class="text-4xl mb-3">🏪</p>
                <p class="font-semibold text-gray-900">Belum ada toko</p>
            </div>
        @else
            <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($stores as $store)
                    <a href="{{ route('stores.show', $store) }}" class="bg-white rounded-2xl border border-gray-200 p-5 text-center hover:border-emerald-300 hover:shadow-md transition-all">
                        <div class="mx-auto w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 inline-flex items-center justify-center text-2xl font-extrabold uppercase">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                        <p class="mt-3 font-bold text-gray-900 line-clamp-1">{{ $store->name }}</p>
                        <p class="text-xs text-gray-500">📍 {{ $store->city }}, {{ $store->province }}</p>
                        <p class="mt-1 text-xs text-emerald-700 font-medium">{{ $store->products_count }} produk</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">{{ $stores->links() }}</div>
        @endif
    </section>
@endsection