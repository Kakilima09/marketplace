@extends('layouts.app')

@section('title', 'Semua Produk')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="text-sm text-gray-500 mb-4">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a>
            <span class="mx-1">/</span>
            <span class="text-gray-800 font-medium">Produk</span>
        </nav>

        <div class="grid gap-6 lg:grid-cols-4">
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-4">
                    <p class="font-bold text-gray-900 mb-3">Kategori</p>
                    <div class="space-y-1">
                        <a href="{{ route('products.index', array_merge(request()->query(), ['category' => ''])) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ !$activeCategory ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span>Semua</span>
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.index', array_merge(request()->except(['category', 'page']), ['category' => $category->slug])) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $activeCategory === $category->slug ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span>{{ $category->icon }} {{ $category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-3">
                <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Semua Produk</h1>
                        <p class="text-sm text-gray-500">{{ $products->total() }} produk ditemukan</p>
                    </div>
                    <form method="GET" class="flex gap-2">
                        @foreach (request()->except(['sort', 'page']) as $key => $value)
                            @if ($value !== null && $value !== '')
                                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                            @endif
                        @endforeach
                        <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm">
                            <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                            <option value="popular" @selected($sort === 'popular')>Terlaris</option>
                            <option value="rating" @selected($sort === 'rating')>Rating Tertinggi</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>Harga Terendah</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>Harga Tertinggi</option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                        <p class="text-4xl mb-3">🔍</p>
                        <p class="font-semibold text-gray-900">Produk tidak ditemukan</p>
                        <p class="text-sm text-gray-500 mt-1">Coba kata kunci atau filter lain.</p>
                        <a href="{{ route('products.index') }}" class="inline-block mt-4 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg">Reset Filter</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection