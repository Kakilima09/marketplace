<x-dashboard-layout
    :title="'Manajemen Produk'"
    section="Panel Admin"
    heading="Kelola Produk"
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
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3">Toko</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3 text-right">Harga</th>
                    <th class="px-5 py-3 text-center">Stok</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" class="w-11 h-11 rounded-lg object-cover">
                                @else
                                    <span class="w-11 h-11 rounded-lg bg-gray-100 inline-flex items-center justify-center">📦</span>
                                @endif
                                <span class="font-medium text-gray-900 line-clamp-1 max-w-[200px]">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $product->store?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-right font-semibold">{{ rupiah($product->price) }}</td>
                        <td class="px-5 py-3 text-center {{ $product->stock > 5 ? 'text-gray-700' : 'text-red-600 font-semibold' }}">{{ $product->stock }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                @csrf
                                <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $product->is_active ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                    {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</x-dashboard-layout>