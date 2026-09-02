<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Website & Tampilan',
                'slug' => 'website-tampilan',
                'description' => 'Desain frontend, layout responsif, dan halaman landing',
                'icon' => '🌐',
                'color' => '#6366f1',
                'sort_order' => 1,
            ],
            [
                'name' => 'Katalog & Produk',
                'slug' => 'katalog-produk',
                'description' => 'Pengelolaan katalog, etalase, dan detail item produk',
                'icon' => '📦',
                'color' => '#8b5cf6',
                'sort_order' => 2,
            ],
            [
                'name' => 'Transaksi & Pesanan',
                'slug' => 'transaksi-pesanan',
                'description' => 'Alur keranjang, checkout, dan manajemen pesanan',
                'icon' => '🛒',
                'color' => '#0ea5e9',
                'sort_order' => 3,
            ],
            [
                'name' => 'Pembayaran & Checkout',
                'slug' => 'pembayaran-checkout',
                'description' => 'Integrasi gateway pembayaran dan metode transfer',
                'icon' => '💳',
                'color' => '#10b981',
                'sort_order' => 4,
            ],
            [
                'name' => 'Marketing & Promosi',
                'slug' => 'marketing-promosi',
                'description' => 'Voucher diskon, loyalty point, dan fitur promosi',
                'icon' => '📣',
                'color' => '#ef4444',
                'sort_order' => 5,
            ],
            [
                'name' => 'Administrasi & Laporan',
                'slug' => 'administrasi-laporan',
                'description' => 'Dashboard admin, analitik, dan manajemen pengguna',
                'icon' => '⚙️',
                'color' => '#64748b',
                'sort_order' => 6,
            ],
            [
                'name' => 'Optimasi & SEO',
                'slug' => 'optimasi-seo',
                'description' => 'Optimasi mesin pencari, sitemap, dan meta tags',
                'icon' => '🔍',
                'color' => '#06b6d4',
                'sort_order' => 7,
            ],
            [
                'name' => 'Integrasi Sistem',
                'slug' => 'integrasi-sistem',
                'description' => 'Koneksi WhatsApp API, kurir otomatis, dan ERP',
                'icon' => '🔗',
                'color' => '#84cc16',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['status' => 'active'])
            );
        }
    }
}
