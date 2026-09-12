<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@umkm.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Andi Customer',
            'email' => 'customer@umkm.test',
            'password' => 'password',
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 1, Jakarta Pusat',
        ]);

        $categories = collect([
            ['name' => 'Makanan & Minuman', 'icon' => '🍲'],
            ['name' => 'Fashion', 'icon' => '👕'],
            ['name' => 'Elektronik', 'icon' => '📱'],
            ['name' => 'Rumah Tangga', 'icon' => '🏠'],
            ['name' => 'Kecantikan', 'icon' => '💄'],
            ['name' => 'Olahraga', 'icon' => '⚽'],
        ])->map(fn ($c) => Category::create($c));

        $sellers = [
            [
                'user' => ['Budi Santoso', 'budi@umkm.test'],
                'store' => [
                    'name' => 'Toko Berkah Jaya',
                    'city' => 'Bekasi',
                    'province' => 'Jawa Barat',
                    'description' => 'Grosir & eceran kebutuhan pokok, sembako murah dan berkualitas.',
                ],
                'products' => [
                    ['Beras Pandan Wangi 5kg', 75000, 50, 1, 'Beras aromatik premium dari petani lokal.'],
                    ['Minyak Goreng 2L', 38000, 80, 1, 'Minyak goreng jernih, tahan menghitam.'],
                    ['Gula Pasir 1kg', 16500, 100, 1, 'Gula pasir putih bersih kemasan 1kg.'],
                    ['Kopi Bubuk Arabica 250g', 45000, 40, 1, 'Kopi bubuk arabica khas tanah Gayo.'],
                    ['Teh Celup Melati 50 kantong', 28000, 60, 1, 'Teh celup dengan aroma melati segar.'],
                    ['Mie Instan Isi 5', 22500, 200, 1, 'Mie instan rasa favorit keluarga.'],
                ],
            ],
            [
                'user' => ['Sari Wulandari', 'sari@umkm.test'],
                'store' => [
                    'name' => 'Dapur Sari',
                    'city' => 'Bandung',
                    'province' => 'Jawa Barat',
                    'description' => 'Makanan rumahan dan camilan khas Sunda yang dibuat fresh setiap hari.',
                ],
                'products' => [
                    ['Keripik Tempe Balado 250g', 23000, 75, 1, 'Keripik tempe renyah dengan bumbu balado.'],
                    ['Dodol Garut Otentik 500g', 55000, 30, 1, 'Dodol lembut manis khas Jawa Barat.'],
                    ['Bandros Spesial isi 6', 18000, 40, 1, 'Bandros khas Bandung, made by order.'],
                    ['Keju Susu Pisang Gepuk', 32000, 35, 1, 'Gepuk pisang keju yang manis gurih.'],
                    ['Rempeyek Kacang 200g', 15000, 90, 1, 'Rempeyek gurih resep keluarga.'],
                    ['Abon Sapi Asli 150g', 48000, 25, 1, 'Abon sapi kering tanpa pengawet.'],
                ],
            ],
            [
                'user' => ['Agus Pratama', 'agus@umkm.test'],
                'store' => [
                    'name' => 'Elektronik Pratama',
                    'city' => 'Surabaya',
                    'province' => 'Jawa Timur',
                    'description' => 'Aksesoris gadget, audio, dan peralatan elektronik bergaransi.',
                ],
                'products' => [
                    ['TWS Earbuds Bluetooth', 95000, 60, 3, 'TWS bluetooth 5.3 dengan baterai tahan lama.'],
                    ['Lampu LED 12W E27', 21000, 120, 3, 'Lampu LED hemat energi, cahaya terang.'],
                    ['Kabel USB-C Fast Charging', 27000, 150, 3, 'Kabel USB-C braided fast charging 60W.'],
                    ['Speaker Mini Portabel', 125000, 45, 3, 'Speaker Bluetooth mini, bass dalam.'],
                    ['Power Bank 10000mAh', 135000, 38, 3, 'Power bank tipis dual output.'],
                    ['Mouse Wireless 2.4G', 49000, 55, 3, 'Mouse wireless ergonomis senyap.'],
                ],
            ],
            [
                'user' => ['Dewi Lestari', 'dewi@umkm.test'],
                'store' => [
                    'name' => 'Beauty by Dewi',
                    'city' => 'Yogyakarta',
                    'province' => 'DI Yogyakarta',
                    'description' => 'Produk kecantikan herbal dan kosmetik halal.',
                ],
                'products' => [
                    ['Serum Vitamin C 30ml', 65000, 42, 5, 'Serum wajah dengan vitamin C untuk kulit cerah.'],
                    ['Masker Wajah Alami isi 10', 38000, 70, 5, 'Masker wajah dengan bahan alami.'],
                    ['Body Scrub Kopi 250ml', 45000, 33, 5, 'Body scrub kopi menghaluskan kulit.'],
                    ['Lip Tint Matte', 29000, 88, 5, 'Lip tint matte tahan lama.'],
                    ['Pelembab Aloe Vera 100ml', 42000, 50, 5, 'Pelembab dengan ekstrak aloe vera.'],
                    ['Handbody Lotion 200ml', 35000, 60, 5, 'Handbody lotion menyegarkan dan melembapkan.'],
                ],
            ],
            [
                'user' => ['Rudi Hartono', 'rudi@umkm.test'],
                'store' => [
                    'name' => 'Fashion Rudi Store',
                    'city' => 'Semarang',
                    'province' => 'Jawa Tengah',
                    'description' => 'Koleksi fashion pria dan wanita dengan harga terjangkau.',
                    'restricted' => true,
                ],
                'products' => [],
            ],
        ];

        foreach ($sellers as $seed) {
            $user = User::create([
                'name' => $seed['user'][0],
                'email' => $seed['user'][1],
                'password' => 'password',
                'role' => 'seller',
                'phone' => '08'.fake()->numerify('#########'),
            ]);

            $storeData = $seed['store'];
            $isRestricted = !empty($storeData['restricted']);
            unset($storeData['restricted']);

            $store = Store::create(array_merge($storeData, [
                'user_id' => $user->id,
                'is_active' => !$isRestricted,
            ]));

            foreach ($seed['products'] as $p) {
                $product = Product::create([
                    'store_id' => $store->id,
                    'category_id' => $p[3],
                    'name' => $p[0],
                    'price' => $p[1],
                    'stock' => $p[2],
                    'description' => $p[4],
                    'is_active' => true,
                ]);

                $path = $this->makePlaceholder($product->name);
                $image = ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => true,
                ]);
            }
        }

        $this->command?->info('Seeder selesai. Akun demo:');
        $this->command?->info('  Admin    : admin@umkm.test / password');
        $this->command?->info('  Customer : customer@umkm.test / password');
        $this->command?->info('  Sellers  : budi@umkm.test, sari@umkm.test, agus@umkm.test, dewi@umkm.test, rudi@umkm.test / password');
    }

    private function makePlaceholder(string $label): string
    {
        $colors = [
            ['#FF6B6B', '#C0392B'],
            ['#4ECDC4', '#1ABC9C'],
            ['#45B7D1', '#2980B9'],
            ['#F7DC6F', '#F39C12'],
            ['#BB8FCE', '#8E44AD'],
            ['#7FD8BE', '#27AE60'],
        ];

        $width = 640;
        $height = 640;
        $img = imagecreatetruecolor($width, $height);

        $seedIdx = crc32($label) % count($colors);
        [$bgHex, $fgHex] = $colors[$seedIdx];

        $bg = imagecolorallocate($img, hexdec(substr($bgHex, 1, 2)), hexdec(substr($bgHex, 3, 2)), hexdec(substr($bgHex, 5, 2)));
        $fg = imagecolorallocate($img, hexdec(substr($fgHex, 1, 2)), hexdec(substr($fgHex, 3, 2)), hexdec(substr($fgHex, 5, 2)));

        imagefilledrectangle($img, 0, 0, $width, $height, $bg);
        imagefilledrectangle($img, 0, $height - 80, $width, $height, $fg);

        $text = strtoupper(substr($label, 0, 2));
        $font = 5;
        $tw = imagefontwidth($font) * strlen($text);
        $th = imagefontheight($font);
        imagestring($img, $font, intdiv($width, 2) - intdiv($tw, 2), intdiv($height, 2) - intdiv($th, 2), $text, $fg);

        $name = 'products/'.strtolower(str_replace([' ', '/', '\\'], '-', $label)).'.png';
        $dir = dirname(storage_path('app/public/'.$name));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        imagepng($img, storage_path('app/public/'.$name));
        imagedestroy($img);

        return $name;
    }
}