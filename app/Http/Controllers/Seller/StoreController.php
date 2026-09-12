<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function edit()
    {
        $store = auth()->user()->store;

        return view('seller.store-edit', compact('store'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $store = $user->store;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('stores', 'name')->ignore($store?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'shipping_cost' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($store) {
            if ($request->hasFile('logo') && $store->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($store->logo);
            }

            $store->update($data + [
                'logo' => $request->hasFile('logo') ? $request->file('logo')->store('stores', 'public') : $store->logo,
            ]);

            return redirect()->route('seller.dashboard')->with('success', 'Profil toko berhasil diperbarui.');
        }

        $store = $user->store()->create($data + [
            'slug' => Str::slug($data['name']),
            'logo' => $request->hasFile('logo') ? $request->file('logo')->store('stores', 'public') : null,
            'is_active' => false,
        ]);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Toko berhasil didaftarkan. Menunggu persetujuan admin.');
    }
}