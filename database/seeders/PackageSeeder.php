<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Paket website profil dan katalog produk dasar dengan tombol pemesanan WhatsApp langsung.',
                'price' => 5000000,
                'period' => 'tahun',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => 'Medium',
                'slug' => 'medium',
                'description' => 'Paket website lengkap dengan katalog interaktif, checkout online, dan sistem pembayaran terintegrasi.',
                'price' => 8000000,
                'period' => 'tahun',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Paket website enterprise lanjutan dengan fitur marketing, voucher diskon, analitik, dan integrasi API.',
                'price' => 15000000,
                'period' => 'tahun',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'name' => 'Custom',
                'slug' => 'custom',
                'description' => 'Rancang website dari nol tanpa batasan paket. Bebas memilih fitur dan sub-fitur sesuai kebutuhan proyek Anda.',
                'price' => 0,
                'period' => 'tahun',
                'status' => 'active',
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(
                ['slug' => $pkg['slug']],
                $pkg
            );
        }
    }
}
