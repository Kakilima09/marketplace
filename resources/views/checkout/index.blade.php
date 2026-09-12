@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-xl font-bold text-gray-900 mb-5">Checkout</h1>

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-5">
                    {{-- Alamat pengiriman --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="font-bold text-gray-900 mb-4">Alamat Pengiriman</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nama Penerima</label>
                                <input type="text" name="receiver_name" value="{{ old('receiver_name', auth()->user()->name) }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">No. HP / WA</label>
                                <input type="text" name="receiver_phone" value="{{ old('receiver_phone', auth()->user()->phone) }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" name="province" value="{{ old('province') }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <input type="text" name="shipping_address" value="{{ old('shipping_address', auth()->user()->address) }}" placeholder="Jl., RT/RW, Kelurahan, Kecamatan, kode pos" class="mt-1 w-full rounded-lg border-gray-300 text-sm" required>
                            </div>
                        </div>
                    </div>

                    {{-- Pesanan per toko + pilihan pembayaran --}}
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <h2 class="font-bold text-gray-900">Pesanan Anda <span class="text-sm font-normal text-gray-500">(dari {{ $items->count() }} toko)</span></h2>
                            <p class="text-xs text-emerald-700 mt-0.5">Checkout ini otomatis dipecah menjadi sub-pesanan per toko.</p>
                        </div>

                        @foreach ($items as $storeId => $storeItems)
                            @php $store = $stores[$storeId]; $t = $totals[$storeId]; @endphp
                            <div class="border-b border-gray-100 last:border-0">
                                <div class="px-6 pt-5 pb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center text-xs font-bold uppercase">{{ strtoupper(substr($store->name, 0, 1)) }}</span>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $store->name }}</p>
                                    </div>
                                </div>

                                <ul class="px-6 divide-y divide-gray-50">
                                    @foreach ($storeItems as $item)
                                        <li class="py-3 flex gap-3">
                                            @if ($item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" class="w-12 h-12 rounded-lg object-cover">
                                            @endif
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                                                <p class="text-xs text-gray-500">{{ rupiah($item->product->price) }} × {{ $item->quantity }}</p>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-900">{{ rupiah($item->subtotal) }}</p>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="px-6 pb-5">
                                    <div class="flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                                        <div>
                                            <label class="text-xs font-medium text-gray-600 block mb-1.5">Metode Pembayaran untuk toko ini:</label>
                                            <div class="flex flex-wrap gap-2" x-data>
                                                @foreach (['cod' => '💵 COD', 'transfer' => '🏦 Transfer Bank', 'midtrans' => '💳 Midtrans / E-Wallet'] as $key => $label)
                                                    <label class="inline-flex items-center gap-1.5 border rounded-xl px-3 py-2 text-sm cursor-pointer {{ old('payment.'.$store->id) === $key ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-gray-200 text-gray-600 hover:border-emerald-300' }}">
                                                        <input type="radio" name="payment[{{ $store->id }}]" value="{{ $key }}" class="sr-only" onchange="this.closest('label').classList.add('border-emerald-500','bg-emerald-50','text-emerald-800'); this.closest('label').classList.remove('border-gray-200','text-gray-600');"
                                                               @if (old('payment.'.$store->id) === $key || (!old('payment.'.$store->id) && $loop->first)) checked @endif>
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="text-sm space-y-0.5 shrink-0">
                                            <p class="flex justify-between gap-8 text-gray-500"><span>Subtotal</span><span>{{ rupiah($t['subtotal']) }}</span></p>
                                            <p class="flex justify-between gap-8 text-gray-500"><span>Ongkir</span><span>{{ rupiah($t['shipping']) }}</span></p>
                                            <p class="flex justify-between gap-8 font-bold text-gray-900"><span>Total</span><span>{{ rupiah($t['total']) }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-20">
                        <h2 class="font-bold text-gray-900 mb-4">Ringkasan Belanja</h2>
                        @php
                            $grandSubtotal = collect($totals)->sum(fn ($t) => $t['subtotal']);
                            $grandShipping = collect($totals)->sum(fn ($t) => $t['shipping']);
                            $grandTotal = $grandSubtotal + $grandShipping;
                        @endphp
                        <div class="space-y-2 text-sm">
                            <p class="flex justify-between text-gray-600"><span>Subtotal ({{ $items->flatten()->sum('quantity') }} item)</span><span>{{ rupiah($grandSubtotal) }}</span></p>
                            <p class="flex justify-between text-gray-600"><span>Total Ongkir ({{ $items->count() }} toko)</span><span>{{ rupiah($grandShipping) }}</span></p>
                            <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-gray-900 text-base">
                                <span>Total</span>
                                <span>{{ rupiah($grandTotal) }}</span>
                            </div>
                        </div>

                        <div class="mt-3 text-xs bg-sky-50 border border-sky-100 text-sky-700 rounded-lg px-3 py-2.5">
                            💡 Setelah checkout, sistem membuat <strong>satu pesanan utama</strong> yang berisi
                            <strong>sub-pesanan per toko</strong>. Setiap sub-pesanan memiliki status & pembayarannya sendiri.
                        </div>

                        <button type="submit" class="mt-5 w-full px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-colors">
                            Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="mt-2 block text-center text-sm text-gray-500 hover:text-red-600">← Kembali ke keranjang</a>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection