@props([
    'title' => null,
    'section' => 'Panel Admin',
    'heading' => 'Dashboard',
    'menu' => [],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }} — {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            <aside class="hidden lg:flex flex-col w-64 bg-gray-900 text-gray-300 shrink-0 fixed inset-y-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-6 h-16 border-b border-gray-800">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-500 text-white font-extrabold">U</span>
                    <div>
                        <p class="font-bold text-white leading-tight">UMKMMarket</p>
                        <p class="text-[11px] text-gray-400">{{ $section }}</p>
                    </div>
                </a>

                <nav class="flex-1 py-4 space-y-1 px-3 overflow-y-auto">
                    @foreach ($menu as $mi)
                        <a href="{{ $mi['url'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs($mi['active']) ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <span class="text-lg leading-none">{{ $mi['icon'] }}</span>
                            {{ $mi['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="px-4 py-3 border-t border-gray-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
                            <span class="text-lg leading-none">⇦</span> Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 lg:pl-64">
                <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between gap-4 h-16 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('home') }}" class="lg:hidden inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold">U</a>
                            <h1 class="text-base sm:text-lg font-bold text-gray-900 truncate">{{ $heading }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-emerald-700">
                                <span>Lihat Toko</span>
                                <span aria-hidden="true">→</span>
                            </a>
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center font-bold text-sm uppercase">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl">
                    @include('partials.alerts')
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>