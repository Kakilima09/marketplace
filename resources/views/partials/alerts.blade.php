@if (session('success'))
    <div class="px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm flex items-start gap-2">
            <span class="mt-0.5">✔</span><span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm flex items-start gap-2">
            <span class="mt-0.5">⚠</span><span>{{ session('error') }}</span>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm flex items-start gap-2">
            <span class="mt-0.5">⚠</span><span>{{ session('warning') }}</span>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-xl bg-sky-50 border border-sky-200 text-sky-800 px-4 py-3 text-sm flex items-start gap-2">
            <span class="mt-0.5">ℹ</span><span>{{ session('info') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="px-4 sm:px-6 lg:px-8 mt-4">
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Terdapat kesalahan pada input:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif