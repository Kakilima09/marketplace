<x-dashboard-layout
    :title="'Detail Pesanan'"
    section="Panel Seller"
    heading="Detail Sub-Pesanan"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('seller.dashboard'), 'active' => 'seller.dashboard'],
        ['label' => 'Profil Toko', 'icon' => '🏪', 'url' => route('seller.store.edit'), 'active' => 'seller.store.*'],
        ['label' => 'Produk Saya', 'icon' => '📦', 'url' => route('seller.products.index'), 'active' => 'seller.products.*'],
        ['label' => 'Pesanan Masuk', 'icon' => '🧾', 'url' => route('seller.orders.index'), 'active' => 'seller.orders.*'],
    ]"
>
    <div class="mb-4 flex items-center gap-3 flex-wrap">
        <a href="{{ route('seller.orders.index') }}" class="text-sm text-gray-500 hover:text-emerald-700">← Kembali</a>
        <span class="font-bold text-gray-900">{{ $subOrder->sub_order_code }}</span>
        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold text-white bg-{{ $subOrder->status_badge }}-500">{{ $subOrder->status_label }}</span>
        <span class="text-xs text-gray-500">{{ $subOrder->created_at->format('d M Y, H:i') }}</span>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100">
                    <h2 class="font-bold text-gray-900 text-sm">Alamat Pengiriman Pembeli</h2>
                </div>
                <div class="p-5 text-sm">
                    <p class="font-semibold text-gray-900">{{ $subOrder->order->receiver_name }} • {{ $subOrder->order->receiver_phone }}</p>
                    <p class="text-gray-600 mt-1">{{ $subOrder->order->shipping_address }}</p>
                    <p class="text-gray-500">{{ $subOrder->order->city }}, {{ $subOrder->order->province }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100">
                    <h2 class="font-bold text-gray-900 text-sm">Item Pesanan</h2>
                </div>
                <ul class="divide-y divide-gray-100">
                    @foreach ($subOrder->items as $item)
                        <li class="px-5 py-3 flex items-center gap-3 text-sm">
                            @if ($item->product?->image_url)
                                <img src="{{ $item->product->image_url }}" class="w-12 h-12 rounded-lg object-cover">
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-500">{{ rupiah($item->price) }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold">{{ rupiah($item->subtotal) }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="px-5 py-4 border-t border-gray-100 flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span><span>{{ rupiah($subOrder->subtotal) }}</span>
                </div>
                <div class="px-5 py-2 flex justify-between text-sm">
                    <span class="text-gray-500">Ongkir</span><span>{{ rupiah($subOrder->shipping_cost) }}</span>
                </div>
                <div class="px-5 py-4 border-t border-gray-100 flex justify-between font-bold">
                    <span>Total</span><span class="text-emerald-700">{{ rupiah($subOrder->total) }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h2 class="font-bold text-gray-900 text-sm mb-3">Pembayaran</h2>
                <div class="space-y-2 text-sm">
                    <p class="flex justify-between"><span class="text-gray-500">Metode</span><span class="font-semibold">{{ $subOrder->method_label }}</span></p>
                    <p class="flex justify-between"><span class="text-gray-500">Kode</span><span class="font-semibold">{{ $subOrder->payment?->payment_code }}</span></p>
                    <p class="flex justify-between"><span class="text-gray-500">Status</span>
                        <span class="font-semibold text-white text-xs rounded-full px-2 py-0.5 bg-{{ $subOrder->payment?->status_badge ?? 'gray' }}-500">{{ $subOrder->payment?->status_label ?? '—' }}</span>
                    </p>
                    @if ($subOrder->payment?->method === 'transfer' && $subOrder->payment?->proof_image)
                        <img src="{{ asset('storage/'.$subOrder->payment->proof_image) }}" class="mt-2 rounded-lg border border-gray-200 max-h-40" alt="Bukti transfer">
                        <p class="text-xs text-gray-500">{{ $subOrder->payment->bank_name }} • {{ $subOrder->payment->account_name }} • {{ $subOrder->payment->account_number }}</p>
                    @endif
                </div>
                <p class="mt-3 text-xs text-gray-500">
                    @if ($subOrder->payment?->method === 'cod')
                        COD: uang diterima saat barang diantar.
                    @elseif (!$subOrder->payment_settled)
                        Menunggu pembayaran buyer / konfirmasi admin.
                    @else
                        Pembayaran lunas — silakan proses pesanan.
                    @endif
                </p>
            </div>

            @if ($subOrder->status === 'payment_pending' && $subOrder->payment?->method === 'cod')
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h2 class="font-bold text-gray-900 text-sm mb-1">Pesanan COD</h2>
                    <p class="text-sm text-gray-600">Pesanan COD bisa langsung diproses tanpa menunggu pembayaran online.</p>
                </div>
            @endif

            @if (in_array($subOrder->status, ['processing', 'payment_pending']))
                <form method="POST" action="{{ route('seller.orders.ship', $subOrder) }}" class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3">
                    @csrf
                    <h2 class="font-bold text-gray-900 text-sm">Kirim Pesanan</h2>
                    @if ($subOrder->status === 'payment_pending' && $subOrder->payment?->method !== 'cod')
                        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                            ⏳ Belum bisa dikirim — pembayaran belum lunas.
                        </p>
                    @endif
                    <div>
                        <label class="text-xs font-medium text-gray-600">Kurir / Ekspedisi</label>
                        <input type="text" name="courier" placeholder="mis. JNE, J&T, SiCepat" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600">No. Resi</label>
                        <input type="text" name="tracking_number" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl" {{ $subOrder->status === 'payment_pending' && $subOrder->payment?->method !== 'cod' ? 'disabled' : '' }}>
                        🚚 Tandai Dikirim
                    </button>
                </form>
            @elseif ($subOrder->status === 'shipped')
                <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3">
                    <h2 class="font-bold text-gray-900 text-sm">Status Pengiriman</h2>
                    <p class="text-sm text-gray-600">Kurir <strong>{{ $subOrder->courier }}</strong> • Resi <strong>{{ $subOrder->tracking_number }}</strong></p>
                    <form method="POST" action="{{ route('seller.orders.deliver', $subOrder) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl">
                            ✅ Tandai Sudah Diterima Pembeli
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>