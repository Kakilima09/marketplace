<x-dashboard-layout
    :title="'Profil Toko'"
    section="Panel Seller"
    heading="Profil Toko"
    :menu="[
        ['label' => 'Dashboard', 'icon' => '🏠', 'url' => route('seller.dashboard'), 'active' => 'seller.dashboard'],
        ['label' => 'Profil Toko', 'icon' => '🏪', 'url' => route('seller.store.edit'), 'active' => 'seller.store.*'],
        ['label' => 'Produk Saya', 'icon' => '📦', 'url' => route('seller.products.index'), 'active' => 'seller.products.*'],
        ['label' => 'Pesanan Masuk', 'icon' => '🧾', 'url' => route('seller.orders.index'), 'active' => 'seller.orders.*'],
    ]"
>
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        @if (!$store)
            <div class="rounded-xl bg-sky-50 border border-sky-100 text-sky-700 px-4 py-3 text-sm mb-5">
                Daftarkan toko Anda sekarang. Setelah disubmit, admin akan memverifikasi toko Anda sebelum aktif.
            </div>
        @elseif (!$store->is_active)
            <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm mb-5">
                ⏳ Toko Anda sedang menunggu verifikasi admin. Anda tetap bisa melengkapi profil & produk.
            </div>
        @endif

        <form method="POST" action="{{ route('seller.store.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Toko</label>
                    <input type="text" name="name" value="{{ old('name', $store?->name) }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ongkos Kirim Flat per Pesanan (Rp)</label>
                    <input type="number" name="shipping_cost" value="{{ old('shipping_cost', $store?->shipping_cost ?? 10000) }}" min="0" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $store?->city) }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', $store?->province) }}" required class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat Toko</label>
                    <input type="text" name="address" value="{{ old('address', $store?->address) }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Toko</label>
                    <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-gray-300 text-sm">{{ old('description', $store?->description) }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Logo Toko (opsional)</label>
                    <input type="file" name="logo" accept="image/*" class="mt-1 block w-full text-sm file:mr-3 file:px-4 file:py-2 file:rounded-lg file:bg-emerald-600 file:text-white file:font-semibold">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl">
                    {{ $store ? 'Simpan Perubahan' : 'Daftarkan Toko' }}
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>