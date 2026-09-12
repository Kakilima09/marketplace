<x-dashboard-layout
    :title="isset($product) ? 'Edit Produk' : 'Tambah Produk'"
    section="Panel Seller"
    :heading="isset($product) ? 'Edit Produk' : 'Tambah Produk'"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('seller.dashboard'), 'active' => 'seller.dashboard'],
        ['label' => 'Profil Toko', 'icon' => '🏪', 'url' => route('seller.store.edit'), 'active' => 'seller.store.*'],
        ['label' => 'Produk Saya', 'icon' => '📦', 'url' => route('seller.products.index'), 'active' => 'seller.products.*'],
        ['label' => 'Pesanan Masuk', 'icon' => '🧾', 'url' => route('seller.orders.index'), 'active' => 'seller.orders.*'],
    ]"
>
    <form method="POST" enctype="multipart/form-data"
          action="{{ isset($product) ? route('seller.products.update', $product) : route('seller.products.store') }}">
        @csrf
        @if (isset($product)) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" step="50" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" min="0" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
                    <textarea name="description" rows="5" class="mt-1 w-full rounded-lg border-gray-300 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Foto Produk ({{ isset($product) ? 'tambahan' : 'wajib minimal 1' }})
                        <span class="text-xs text-gray-400">— gambar pertama jadi gambar utama</span>
                    </label>
                    @if (isset($product) && $product->images->isNotEmpty())
                        <div class="mt-2 flex gap-2 flex-wrap">
                            @foreach ($product->images as $img)
                                <img src="{{ asset('storage/'.$img->path) }}" class="w-16 h-16 rounded-lg border border-gray-200 object-cover">
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="images[]" accept="image/*" {{ isset($product) ? '' : 'required' }} multiple class="mt-2 block w-full text-sm file:mr-3 file:px-4 file:py-2 file:rounded-lg file:bg-emerald-600 file:text-white file:font-semibold">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.products.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl">Simpan</button>
            </div>
        </div>
    </form>
</x-dashboard-layout>