<x-dashboard-layout
    :title="'Manajemen Toko'"
    section="Panel Admin"
    heading="Kelola Toko"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('admin.dashboard'), 'active' => 'admin.dashboard'],
        ['label' => 'Toko', 'icon' => '🏪', 'url' => route('admin.stores.index'), 'active' => 'admin.stores.*'],
        ['label' => 'Produk', 'icon' => '📦', 'url' => route('admin.products.index'), 'active' => 'admin.products.*'],
        ['label' => 'Kategori', 'icon' => '🗂️', 'url' => route('admin.categories.index'), 'active' => 'admin.categories.*'],
        ['label' => 'Pembayaran', 'icon' => '💳', 'url' => route('admin.payments.index'), 'active' => 'admin.payments.*'],
        ['label' => 'Pengguna', 'icon' => '👥', 'url' => route('admin.users.index'), 'active' => 'admin.users.*'],
    ]"
>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-5 py-3">Toko</th>
                    <th class="px-5 py-3">Pemilik</th>
                    <th class="px-5 py-3 text-center">Produk</th>
                    <th class="px-5 py-3 text-right">Ongkir</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($stores as $store)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-900">{{ $store->name }}</p>
                            <p class="text-xs text-gray-500">{{ $store->city }}, {{ $store->province }}</p>
                        </td>
                        <td class="px-5 py-3">{{ $store->owner->name }}<br>
                            <span class="text-xs text-gray-500">{{ $store->owner->email }}</span>
                        </td>
                        <td class="px-5 py-3 text-center text-gray-700">{{ $store->products_count }}</td>
                        <td class="px-5 py-3 text-right">{{ rupiah($store->shipping_cost) }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $store->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $store->is_active ? 'Aktif' : 'Menunggu / Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-1.5">
                                @if ($store->is_active)
                                    <form method="POST" action="{{ route('admin.stores.deactivate', $store) }}">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100">Nonaktifkan</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.stores.approve', $store) }}">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100">✓ Verifikasi & Aktifkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $stores->links() }}</div>
</x-dashboard-layout>