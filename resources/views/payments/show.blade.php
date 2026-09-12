@extends('layouts.app')

@section('title', 'Pembayaran '.$payment->payment_code)

@section('content')
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <nav class="text-sm text-gray-500 mb-4">
            <a href="{{ route('orders.show', $payment->subOrder->order_id) }}" class="hover:text-emerald-700">← Kembali ke Pesanan</a>
        </nav>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-lg font-bold text-gray-900">{{ $payment->method_label }}</h1>
                    <p class="text-sm text-gray-500">{{ $payment->payment_code }} • {{ $payment->subOrder->store->name }}</p>
                </div>
                <span class="inline-flex px-3 py-1.5 rounded-full text-sm font-semibold text-white bg-{{ $payment->status_badge }}-500">{{ $payment->status_label }}</span>
            </div>

            <div class="p-6">
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-center">
                    <p class="text-xs text-emerald-700 uppercase font-semibold">Total yang harus dibayar</p>
                    <p class="text-3xl font-extrabold text-emerald-700 mt-1">{{ rupiah($payment->amount) }}</p>
                </div>

                <div class="mt-6">
                    @if ($payment->status === 'paid' || $payment->status === 'confirmed')
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-4 text-sm">
                            ✅ Pembayaran telah diterima.
                            @if ($payment->verified_at) Dikonfirmasi pada {{ $payment->verified_at->format('d M Y, H:i') }}. @endif
                            Seller akan segera memproses pesanan Anda.
                        </div>
                    @elseif ($payment->status === 'failed')
                        <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-4 text-sm">
                            ❌ Pembayaran ditolak / gagal. Hubungi admin untuk informasi lebih lanjut.
                        </div>
                    @else
                        @if ($payment->method === 'transfer')
                            <div class="rounded-xl bg-gray-50 border border-gray-200 p-5 text-sm space-y-2">
                                <p class="font-semibold text-gray-900">Langkah pembayaran:</p>
                                <ol class="list-decimal list-inside space-y-1 text-gray-600">
                                    <li>Transfer sejumlah <strong>{{ rupiah($payment->amount) }}</strong> ke rekening toko {{ $payment->subOrder->store->name }} (nomor rekening akan dikonfirmasi seller / hubungi seller via chat).</li>
                                    <li>Isi data pengirim dan unggah bukti transfer di bawah ini.</li>
                                    <li>Admin akan memverifikasi pembayaran. Status sub-pesanan otomatis menjadi <strong>Diproses</strong> setelah dikonfirmasi.</li>
                                </ol>
                            </div>

                            @if ($payment->proof_image)
                                <div class="mt-4 rounded-xl bg-gray-50 border border-gray-200 p-4">
                                    <p class="text-sm font-semibold text-gray-900 mb-2">Bukti transfer yang diunggah:</p>
                                    <img src="{{ asset('storage/'.$payment->proof_image) }}" class="rounded-xl max-h-64 border border-gray-200" alt="Bukti transfer">
                                </div>
                            @else
                                <form method="POST" action="{{ route('payments.proof', $payment) }}" enctype="multipart/form-data" class="mt-5 bg-white border border-gray-200 rounded-2xl p-5 space-y-4">
                                    @csrf
                                    <p class="font-semibold text-gray-900 text-sm">Unggah Bukti Transfer</p>
                                    <div class="grid sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="text-xs font-medium text-gray-600">Bank Pengirim</label>
                                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-600">Nama Pengirim</label>
                                            <input type="text" name="account_name" value="{{ old('account_name') }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-600">No. Rekening</label>
                                            <input type="text" name="account_number" value="{{ old('account_number') }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-600">Foto Bukti Transfer (maks 2MB)</label>
                                        <input type="file" name="proof_image" required accept="image/*" class="mt-1 block w-full text-sm file:mr-3 file:px-4 file:py-2 file:rounded-lg file:bg-emerald-600 file:text-white file:font-semibold">
                                    </div>
                                    <button type="submit" class="w-full px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl">Kirim Bukti Pembayaran</button>
                                </form>
                            @endif

                        @elseif ($payment->method === 'midtrans')
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-600 max-w-md mx-auto">
                                    Pembayaran diproses melalui <strong>Midtrans</strong> (VA Bank, QRIS, E-Wallet, dan lainnya).
                                    Di bawah ini adalah simulasi pembayaran untuk keperluan pengembangan.
                                </p>
                                <form method="POST" action="{{ route('payments.simulate', $payment) }}" class="mt-6">
                                    @csrf
                                    <button type="submit" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl">
                                        💳 Simulasi Bayar Berhasil
                                    </button>
                                </form>
                            </div>

                        @elseif ($payment->method === 'cod')
                            <div class="rounded-xl bg-gray-50 border border-gray-200 p-5 text-sm text-gray-600">
                                <p class="font-semibold text-gray-900 mb-1">💵 Bayar di Tempat (COD)</p>
                                <p>
                                    Tidak ada pembayaran di muka. Seller akan memproses & mengirimkan pesanan Anda.
                                    Pembayaran lunas saat barang diterima.
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection