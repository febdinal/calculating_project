<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

/**
 * Seeds add-ons from the document.
 * price_min and price_max are set from the document ranges.
 * cost_price = NULL (admin must fill per instruction #27).
 * selling_price = NULL (admin decides exact selling price within range).
 */
class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            [
                'name' => 'Mobile App Android',
                'slug' => 'mobile-app-android',
                'description' => 'Pengembangan aplikasi mobile Android native atau hybrid untuk toko online Anda. Tersedia di Google Play Store.',
                'icon' => '🤖',
                'category' => 'Mobile',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 8000000,
                'price_max' => 25000000,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mobile App iOS',
                'slug' => 'mobile-app-ios',
                'description' => 'Pengembangan aplikasi mobile iOS native untuk toko online Anda. Tersedia di Apple App Store.',
                'icon' => '🍎',
                'category' => 'Mobile',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 10000000,
                'price_max' => 30000000,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mobile App Android + iOS',
                'slug' => 'mobile-app-android-ios',
                'description' => 'Paket lengkap aplikasi mobile untuk Android dan iOS sekaligus dengan codebase yang terintegrasi.',
                'icon' => '📱',
                'category' => 'Mobile',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 20000000,
                'price_max' => 55000000,
                'sort_order' => 3,
            ],
            [
                'name' => 'Multi-vendor Marketplace',
                'slug' => 'multi-vendor-marketplace',
                'description' => 'Ubah toko Anda menjadi marketplace dengan banyak penjual. Termasuk dashboard penjual, komisi, dan manajemen multi-toko.',
                'icon' => '🏪',
                'category' => 'Advanced',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 15000000,
                'price_max' => 30000000,
                'sort_order' => 4,
            ],
            [
                'name' => 'Affiliate / Reseller',
                'slug' => 'affiliate-reseller',
                'description' => 'Sistem affiliate marketing dan reseller dengan tracking komisi, dashboard afiliasi, dan laporan kinerja.',
                'icon' => '🤝',
                'category' => 'Marketing',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 5000000,
                'price_max' => 10000000,
                'sort_order' => 5,
            ],
            [
                'name' => 'Loyalty Point',
                'slug' => 'loyalty-point',
                'description' => 'Sistem poin reward untuk pelanggan setia. Poin bisa ditukarkan dengan diskon atau produk gratis.',
                'icon' => '🎯',
                'category' => 'Marketing',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 4000000,
                'price_max' => 8000000,
                'sort_order' => 6,
            ],
            [
                'name' => 'ERP / POS Integration',
                'slug' => 'erp-pos-integration',
                'description' => 'Integrasi dengan sistem ERP atau POS yang sudah ada (SAP, Odoo, Accurate, Moka, dll.) untuk sinkronisasi data.',
                'icon' => '🔄',
                'category' => 'Integration',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 10000000,
                'price_max' => 30000000,
                'sort_order' => 7,
            ],
            [
                'name' => 'AI Recommendation',
                'slug' => 'ai-recommendation',
                'description' => 'Sistem rekomendasi produk berbasis AI/ML berdasarkan perilaku belanja dan preferensi pelanggan.',
                'icon' => '🤖',
                'category' => 'Advanced',
                'price_type' => 'range',
                'cost_price' => null,
                'selling_price' => null,
                'price_min' => 10000000,
                'price_max' => 25000000,
                'sort_order' => 8,
            ],
        ];

        foreach ($addons as $addonData) {
            Addon::firstOrCreate(
                ['slug' => $addonData['slug']],
                array_merge($addonData, ['status' => 'active'])
            );
        }
    }
}
