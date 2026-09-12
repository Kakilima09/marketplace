<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between gap-4 h-16">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-extrabold text-lg">U</span>
                            <span class="font-bold text-lg leading-tight">
                                UMKM<span class="text-emerald-600">Market</span>
                            </span>
                        </a>

                        <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex flex-1 max-w-xl">
                            <input
                                type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari produk, mis. "kopi", "beras"..."
                                class="w-full rounded-l-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                            >
                            <button type="submit" class="px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-r-lg text-sm font-semibold">Cari</button>
                        </form>

                        <nav class="flex items-center gap-1.5 sm:gap-3">
                            <a href="{{ route('products.index') }}" class="hidden sm:inline-flex px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-700 rounded-lg">Produk</a>
                            <a href="{{ route('stores.index') }}" class="hidden sm:inline-flex px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-700 rounded-lg">Toko</a>

                            @auth
                                @if (auth()->user()->isSeller())
                                    <a href="{{ route('seller.dashboard') }}" class="hidden sm:inline-flex px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 rounded-lg">Dashboard Seller</a>
                                @endif

                                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-emerald-700 rounded-lg" title="Keranjang">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                    </svg>
                                    @if (auth()->user()->cartItems()->count())
                                        <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold">{{ auth()->user()->cartItems()->count() }}</span>
                                    @endif
                                </a>

                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100">
                                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center font-bold text-sm uppercase">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-lg ring-1 ring-black/5 py-1.5 z-50">
                                        <div class="px-4 py-2 border-b border-gray-100">
                                            <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                        </div>
                                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pesanan Saya</a>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil</a>
                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Panel Admin</a>
                                        @endif
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-700 rounded-lg">Masuk</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">Daftar</a>
                            @endauth
                        </nav>
                    </div>

                    <form action="{{ route('products.index') }}" method="GET" class="md:hidden pb-3">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    </form>
                </div>
            </header>

            <main class="flex-1">
                @include('partials.alerts')
                @yield('content')
            </main>

            <footer class="bg-white border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid gap-8 md:grid-cols-3">
                    <div>
                        <p class="font-bold text-lg">UMKM<span class="text-emerald-600">Market</span></p>
                        <p class="mt-2 text-sm text-gray-500">Platform marketplace untuk para pelaku UMKM Indonesia. Satu checkout, banyak toko.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-900">Jelajahi</p>
                        <ul class="mt-2 space-y-1.5 text-sm text-gray-600">
                            <li><a href="{{ route('products.index') }}" class="hover:text-emerald-700">Semua Produk</a></li>
                            <li><a href="{{ route('stores.index') }}" class="hover:text-emerald-700">Daftar Toko</a></li>
                            @auth
                                @if (auth()->user()->isSeller())
                                    <li><a href="{{ route('seller.dashboard') }}" class="hover:text-emerald-700">Dashboard Seller</a></li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-900">Bantuan</p>
                        <ul class="mt-2 space-y-1.5 text-sm text-gray-600">
                            <li><a href="{{ route('orders.index') }}" class="hover:text-emerald-700">Status Pesanan</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="hover:text-emerald-700">Akun Saya</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-100 py-4 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Dibuat untuk para penggerak UMKM.
                </div>
            </footer>
        </div>
    </body>
</html>