<x-dashboard-layout
    :title="'Dashboard Seller'"
    section="Panel Seller"
    heading="Dashboard Seller"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('seller.dashboard'), 'active' => 'seller.dashboard'],
        ['label' => 'Profil Toko', 'icon' => '🏪', 'url' => route('seller.store.edit'), 'active' => 'seller.store.*'],
        ['label' => 'Produk Saya', 'icon' => '📦', 'url' => route('seller.products.index'), 'active' => 'seller.products.*'],
        ['label' => 'Pesanan Masuk', 'icon' => '🧾', 'url' => route('seller.orders.index'), 'active' => 'seller.orders.*'],
    ]"
>
    @if (!$store->is_active)
        <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm mb-5">
            ⏳ Toko Anda masih <strong>menunggu verifikasi admin</strong>. Produk belum bisa dilihat pembeli hingga disetujui.
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold">Produk Aktif</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($productCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold">Total Pesanan</p>
            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($orderCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold">Menunggu Dibayar</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($pendingPayments) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 font-semibold">Perlu Diproses</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($incomingCount) }}</p>
        </div>
    </div>

    <div class="mt-4 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl p-6 text-white flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-sm text-white/75">Omset dari pesanan lunas</p>
            <p class="text-3xl font-extrabold mt-1">{{ rupiah($revenue) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('seller.products.create') }}" class="px-5 py-2.5 bg-white text-emerald-700 font-bold rounded-xl hover:bg-emerald-50">+ Tambah Produk</a>
            <a href="{{ route('seller.orders.index') }}" class="px-5 py-2.5 bg-white/10 border border-white/30 font-semibold rounded-xl hover:bg-white/20">Kelola Pesanan</a>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Pesanan Terbaru</h2>
        </div>
        @if ($recentSubOrders->isNotEmpty())
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Pembeli</th>
                        <th class="px-5 py-3">Metode</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($recentSubOrders as $sub)
                        <tr>
                            <td class="px-5 py-3"><a href="{{ route('seller.orders.show', $sub) }}" class="font-semibold text-emerald-700 hover:underline">{{ $sub->sub_order_code }}</a></td>
                            <td class="px-5 py-3">{{ $sub->order->receiver_name }}</td>
                            <td class="px-5 py-3">{{ $sub->method_label }}</td>
                            <td class="px-5 py-3"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-{{ $sub->status_badge }}-500">{{ $sub->status_label }}</span></td>
                            <td class="px-5 py-3 text-right font-semibold">{{ rupiah($sub->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="px-5 py-10 text-center text-sm text-gray-500">Belum ada pesanan masuk.</p>
        @endif
    </div>
</x-dashboard-layout>