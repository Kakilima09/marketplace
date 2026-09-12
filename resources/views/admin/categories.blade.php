<x-dashboard-layout
    :title="'Manajemen Kategori'"
    section="Panel Admin"
    heading="Kelola Kategori"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('admin.dashboard'), 'active' => 'admin.dashboard'],
        ['label' => 'Toko', 'icon' => '🏪', 'url' => route('admin.stores.index'), 'active' => 'admin.stores.*'],
        ['label' => 'Produk', 'icon' => '📦', 'url' => route('admin.products.index'), 'active' => 'admin.products.*'],
        ['label' => 'Kategori', 'icon' => '🗂️', 'url' => route('admin.categories.index'), 'active' => 'admin.categories.*'],
        ['label' => 'Pembayaran', 'icon' => '💳', 'url' => route('admin.payments.index'), 'active' => 'admin.payments.*'],
        ['label' => 'Pengguna', 'icon' => '👥', 'url' => route('admin.users.index'), 'active' => 'admin.users.*'],
    ]"
>
    <div class="grid gap-5 lg:grid-cols-5">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white rounded-2xl border border-gray-200 p-6 lg:col-span-2 h-max space-y-4">
            @csrf
            <h2 class="font-bold text-gray-900">Tambah Kategori</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ikon (emoji, opsional)</label>
                <input type="text" name="icon" value="{{ old('icon') }}" maxlength="10" placeholder="mis. 🍜" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
            </div>
            <button type="submit" class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl">Tambah</button>
        </form>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden lg:col-span-3">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3 text-center">Produk</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="icon" value="{{ $category->icon }}" maxlength="10" class="w-12 rounded-lg border-gray-300 text-sm">
                                    <input type="text" name="name" value="{{ $category->name }}" required class="flex-1 rounded-lg border-gray-300 text-sm">
                                    <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100">Simpan</button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-center text-gray-700">{{ $category->products_count }}</td>
                            <td class="px-5 py-3 text-right">
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>