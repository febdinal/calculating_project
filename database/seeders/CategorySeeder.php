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
                'name' => 'Website & Frontend',
                'slug' => 'website-frontend',
                'description' => 'Tampilan dan antarmuka website yang dilihat pengunjung',
                'icon' => '🖥️',
                'color' => '#6366f1',
                'sort_order' => 1,
            ],
            [
                'name' => 'Produk',
                'slug' => 'produk',
                'description' => 'Manajemen katalog dan inventori produk',
                'icon' => '📦',
                'color' => '#8b5cf6',
                'sort_order' => 2,
            ],
            [
                'name' => 'Transaksi',
                'slug' => 'transaksi',
                'description' => 'Proses pembelian, keranjang, dan checkout',
                'icon' => '🛒',
                'color' => '#0ea5e9',
                'sort_order' => 3,
            ],
            [
                'name' => 'Pembayaran',
                'slug' => 'pembayaran',
                'description' => 'Metode dan gateway pembayaran online',
                'icon' => '💳',
                'color' => '#10b981',
                'sort_order' => 4,
            ],
            [
                'name' => 'Pengiriman',
                'slug' => 'pengiriman',
                'description' => 'Integrasi logistik dan kalkulasi ongkos kirim',
                'icon' => '🚚',
                'color' => '#f59e0b',
                'sort_order' => 5,
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Alat promosi, diskon, dan retensi pelanggan',
                'icon' => '📣',
                'color' => '#ef4444',
                'sort_order' => 6,
            ],
            [
                'name' => 'Administrasi',
                'slug' => 'administrasi',
                'description' => 'Dashboard admin, laporan, dan manajemen operasional',
                'icon' => '⚙️',
                'color' => '#64748b',
                'sort_order' => 7,
            ],
            [
                'name' => 'SEO',
                'slug' => 'seo',
                'description' => 'Optimasi mesin pencari untuk meningkatkan visibilitas',
                'icon' => '🔍',
                'color' => '#06b6d4',
                'sort_order' => 8,
            ],
            [
                'name' => 'Integrasi',
                'slug' => 'integrasi',
                'description' => 'Koneksi dengan sistem dan layanan eksternal via API',
                'icon' => '🔗',
                'color' => '#84cc16',
                'sort_order' => 9,
            ],
            [
                'name' => 'Infrastruktur',
                'slug' => 'infrastruktur',
                'description' => 'Hosting, domain, SSL, backup, dan pemeliharaan teknis (sudah termasuk dalam paket)',
                'icon' => '🏗️',
                'color' => '#6b7280',
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                array_merge($categoryData, ['status' => 'active'])
            );
        }
    }
}
