@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <div class="text-center mb-6">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-extrabold text-xl">U</span>
                <h1 class="mt-3 text-xl font-bold text-gray-900">Masuk ke {{ config('app.name') }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-emerald-700 font-semibold hover:underline">Daftar dulu</a>
                </p>
            </div>

            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm mb-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="mt-1 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                </div>

                <div class="mt-4 flex items-center justify-between gap-3">
                    <label class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-emerald-700 font-medium hover:underline">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="mt-6 w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-colors">
                    Masuk
                </button>
            </form>
        </div>
    </section>
@endsection