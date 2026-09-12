<x-dashboard-layout
    :title="'Manajemen Pembayaran'"
    section="Panel Admin"
    heading="Verifikasi Pembayaran"
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
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Pembeli</th>
                    <th class="px-5 py-3">Toko</th>
                    <th class="px-5 py-3">Metode</th>
                    <th class="px-5 py-3 text-right">Jumlah</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($payments as $payment)
                    @php $sub = $payment->subOrder; @endphp
                    <tr class="{{ $payment->status === 'pending' && $payment->method === 'transfer' ? 'bg-amber-50/60' : '' }}">
                        <td class="px-5 py-3 font-semibold text-gray-900">{{ $payment->payment_code }}</td>
                        <td class="px-5 py-3">{{ $sub->order->user->name }}</td>
                        <td class="px-5 py-3">{{ $sub->store->name }}</td>
                        <td class="px-5 py-3">{{ $payment->method_label }}</td>
                        <td class="px-5 py-3 text-right font-semibold">{{ rupiah($payment->amount) }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold text-white bg-{{ $payment->status_badge }}-500">{{ $payment->status_label }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-1.5 flex-wrap min-w-32">
                                @if ($payment->method === 'transfer' && $payment->status === 'pending')
                                    @if ($payment->proof_image)
                                        <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-sky-50 text-sky-700 hover:bg-sky-100">Lihat Bukti</a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700">✓ Konfirmasi</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.payments.fail', $payment) }}" onsubmit="return confirm('Tolak pembayaran ini?')">
                                        @csrf
                                        <button class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100">Tolak</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $payments->links() }}</div>
</x-dashboard-layout>