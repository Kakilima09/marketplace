<x-dashboard-layout
    :title="'Produk Saya'"
    section="Panel Seller"
    heading="Produk Saya"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('seller.dashboard'), 'active' => 'seller.dashboard'],
        ['label' => 'Profil Toko', 'icon' => '🏪', 'url' => route('seller.store.edit'), 'active' => 'seller.store.*'],
        ['label' => 'Produk Saya', 'icon' => '📦', 'url' => route('seller.products.index'), 'active' => 'seller.products.*'],
        ['label' => 'Pesanan Masuk', 'icon' => '🧾', 'url' => route('seller.orders.index'), 'active' => 'seller.orders.*'],
    ]"
>
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">{{ $products->total() }} produk</p>
        <a href="{{ route('seller.products.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl">+ Tambah Produk</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if ($products->isEmpty())
            <p class="px-5 py-14 text-center text-sm text-gray-500">Belum ada produk. Klik "Tambah Produk" untuk memulai.</p>
        @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3">Produk</th>
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
                            <td class="px-5 py-3 text-gray-600">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right font-semibold">{{ rupiah($product->price) }}</td>
                            <td class="px-5 py-3 text-center {{ $product->stock > 5 ? 'text-gray-700' : 'text-red-600 font-semibold' }}">{{ $product->stock }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <form method="POST" action="{{ route('seller.products.toggle', $product) }}">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $product->is_active ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('seller.products.edit', $product) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100">Edit</a>
                                    <form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</x-dashboard-layout>