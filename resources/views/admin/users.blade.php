<x-dashboard-layout
    :title="'Manajemen Pengguna'"
    section="Panel Admin"
    heading="Daftar Pengguna"
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
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Peran</th>
                    <th class="px-5 py-3">Status Email</th>
                    <th class="px-5 py-3 text-right">Terdaftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 inline-flex items-center justify-center font-bold uppercase">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700' : ($user->role === 'seller' ? 'bg-sky-50 text-sky-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $user->hasVerifiedEmail() ? '✓ Terverifikasi' : 'Belum' }}</td>
                        <td class="px-5 py-3 text-right text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-dashboard-layout>