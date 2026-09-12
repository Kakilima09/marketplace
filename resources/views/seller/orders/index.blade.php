<x-dashboard-layout
    :title="'Pesanan Masuk'"
    section="Panel Seller"
    heading="Pesanan Toko"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('seller.dashboard'), 'active' => 'seller.dashboard'],
        ['label' => 'Profil Toko', 'icon' => '🏪', 'url' => route('seller.store.edit'), 'active' => 'seller.store.*'],
        ['label' => 'Produk Saya', 'icon' => '📦', 'url' => route('seller.products.index'), 'active' => 'seller.products.*'],
        ['label' => 'Pesanan Masuk', 'icon' => '🧾', 'url' => route('seller.orders.index'), 'active' => 'seller.orders.*'],
    ]"
>
    <div class="mb-5 flex flex-wrap gap-2">
        <a href="{{ route('seller.orders.index') }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ !request('status') ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">Semua</a>
        @foreach (['payment_pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $st)
            <a href="{{ route('seller.orders.index', ['status' => $st]) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold {{ request('status') === $st ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                {{ str_replace('_', ' ', ucfirst($st)) }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if ($subOrders->isEmpty())
            <p class="px-5 py-14 text-center text-sm text-gray-500">Tidak ada pesanan dengan status ini.</p>
        @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3">Kode Sub-Pesanan</th>
                        <th class="px-5 py-3">Pembeli</th>
                        <th class="px-5 py-3">Metode</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        <th class="px-5 py-3 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($subOrders as $sub)
                        <tr>
                            <td class="px-5 py-3 font-semibold text-gray-900">{{ $sub->sub_order_code }}</td>
                            <td class="px-5 py-3">{{ $sub->order->receiver_name }}</td>
                            <td class="px-5 py-3">{{ $sub->method_label }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-{{ $sub->status_badge }}-500">{{ $sub->status_label }}</span>
                            </td>
                            <td class="px-5 py-3 text-right font-semibold">{{ rupiah($sub->total) }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('seller.orders.show', $sub) }}" class="inline-block px-3 py-1.5 bg-sky-50 text-sky-700 rounded-lg text-xs font-semibold hover:bg-sky-100">Kelola</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="mt-4">{{ $subOrders->links() }}</div>
</x-dashboard-layout>