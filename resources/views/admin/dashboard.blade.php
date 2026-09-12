<x-dashboard-layout
    :title="'Dashboard Admin'"
    section="Panel Admin"
    heading="Dashboard Admin"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('admin.dashboard'), 'active' => 'admin.dashboard'],
        ['label' => 'Toko', 'icon' => '🏪', 'url' => route('admin.stores.index'), 'active' => 'admin.stores.*'],
        ['label' => 'Produk', 'icon' => '📦', 'url' => route('admin.products.index'), 'active' => 'admin.products.*'],
        ['label' => 'Kategori', 'icon' => '🗂️', 'url' => route('admin.categories.index'), 'active' => 'admin.categories.*'],
        ['label' => 'Pembayaran', 'icon' => '💳', 'url' => route('admin.payments.index'), 'active' => 'admin.payments.*'],
        ['label' => 'Pengguna', 'icon' => '👥', 'url' => route('admin.users.index'), 'active' => 'admin.users.*'],
    ]"
>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ([
            ['Pengguna', $totals['users'], '👥'],
            ['Seller', $totals['sellers'], '🛍️'],
            ['Toko', $totals['stores'], '🏪'],
            ['Toko Menunggu Verifikasi', $totals['storesPending'], '⏳'],
            ['Produk', $totals['products'], '📦'],
            ['Pesanan', $totals['orders'], '🧾'],
            ['Omset (bruto)', rupiah($totals['revenue']), '💰'],
            ['Pembayaran Perlu Verifikasi', $totals['paymentsToVerify'], '🔎'],
        ] as $card)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xl">{{ $card[2] }}</p>
                <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ $card[1] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $card[0] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-5 lg:grid-cols-2">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Toko Menunggu Verifikasi</h2>
                <a href="{{ route('admin.stores.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Kelola →</a>
            </div>
            @if ($pendingStores->isNotEmpty())
                <ul class="divide-y divide-gray-100">
                    @foreach ($pendingStores as $store)
                        <li class="px-5 py-3.5 flex items-center gap-3">
                            <span class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center font-bold uppercase">{{ strtoupper(substr($store->name, 0, 1)) }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $store->name }}</p>
                                <p class="text-xs text-gray-500">{{ $store->owner->name }} • {{ $store->city }}</p>
                            </div>
                            <a href="{{ route('admin.stores.index') }}" class="text-xs font-semibold text-emerald-700">Verifikasi →</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-5 py-10 text-center text-sm text-gray-500">Tidak ada toko menunggu verifikasi. 🎉</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Pesanan Terbaru</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">Pembayaran →</a>
            </div>
            @if ($recentOrders->isNotEmpty())
                <ul class="divide-y divide-gray-100">
                    @foreach ($recentOrders as $order)
                        <li class="px-5 py-3.5 flex items-center gap-3 text-sm">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900">{{ $order->order_code }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->name }}</p>
                            </div>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-{{ $order->status_badge }}-500">{{ $order->status_label }}</span>
                            <span class="font-semibold text-emerald-700">{{ rupiah($order->grand_total) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-5 py-10 text-center text-sm text-gray-500">Belum ada pesanan.</p>
            @endif
        </div>
    </div>
</x-dashboard-layout>