@props(['product'])
<a href="{{ route('products.show', $product) }}" class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-all flex flex-col">
    <div class="aspect-square bg-gray-100 relative overflow-hidden">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400 text-4xl">📦</div>
        @endif
        @if ($product->stock === 0)
            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">Habis</span>
        @endif
    </div>
    <div class="p-3.5 flex-1 flex flex-col gap-1">
        <p class="text-xs font-semibold text-emerald-700 truncate" title="{{ $product->store->name }}">{{ $product->store->name }}</p>
        <p class="text-sm font-medium text-gray-900 leading-snug line-clamp-2 min-h-[2.5rem]" title="{{ $product->name }}">{{ $product->name }}</p>
        <p class="font-bold text-emerald-700 mt-auto">{{ rupiah($product->price) }}</p>
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span class="inline-flex items-center gap-1">
                <span class="text-amber-500">★</span>
                <span class="font-semibold">{{ $product->rating_avg > 0 ? number_format($product->rating_avg, 1) : 'Baru' }}</span>
            </span>
            <span>
                @if ($product->sold_count) {{ $product->sold_count }} terjual @endif
            </span>
        </div>
    </div>
</a>