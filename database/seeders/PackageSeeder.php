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
                'description' => 'Paket terbaik untuk UMKM dan toko baru yang ingin hadir secara online. Mencakup katalog produk digital, tombol WhatsApp langsung, dan semua kebutuhan dasar untuk memulai berjualan online.',
                'price' => 4000000,
                'billing_period' => 'annual',
                'target_user' => 'UMKM, Toko Baru, Usaha Kecil yang baru mulai online',
                'price_type' => 'fixed',
                'is_featured' => false,
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Medium',
                'slug' => 'medium',
                'description' => 'Paket e-commerce lengkap dengan kemampuan transaksi nyata. Dilengkapi checkout online, payment gateway, integrasi ongkos kirim, akun pelanggan, dan otomasi pesanan.',
                'price' => 8000000,
                'billing_period' => 'annual',
                'target_user' => 'Toko online aktif, UKM yang sudah berjalan, bisnis dengan volume pesanan menengah',
                'price_type' => 'fixed',
                'is_featured' => true,
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Paket operasional lanjutan untuk bisnis e-commerce yang sudah berkembang. Mencakup fitur analitik, marketing tools, integrasi API, dan skalabilitas tinggi.',
                'price' => 15000000,
                'billing_period' => 'annual',
                'target_user' => 'Bisnis e-commerce skala menengah-besar, brand yang membutuhkan fitur lengkap dan skalabilitas',
                'price_type' => 'fixed',
                'is_featured' => false,
                'sort_order' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Web Custom',
                'slug' => 'web-custom',
                'description' => 'Pengembangan custom sepenuhnya sesuai kebutuhan spesifik bisnis Anda. Harga disesuaikan berdasarkan scope proyek dan kompleksitas yang diinginkan.',
                'price' => null,
                'billing_period' => 'annual',
                'target_user' => 'Enterprise, bisnis dengan kebutuhan khusus, proyek dengan fitur unik yang belum tersedia di paket standar',
                'price_type' => 'custom',
                'is_featured' => false,
                'sort_order' => 4,
                'status' => 'active',
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['slug' => $packageData['slug']],
                $packageData
            );
        }
    }
}
