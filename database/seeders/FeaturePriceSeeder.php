<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\FeaturePrice;
use Illuminate\Database\Seeder;

class FeaturePriceSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Comprehensive dummy price matrix for all 39 features:
         * [slug => [
         *    'basic' => [selling, cost],
         *    'standard' => [selling, cost, is_default],
         *    'advanced' => [selling, cost],
         *    'custom' => [selling, cost] (optional)
         * ]]
         */
        $prices = [
            // Website & Frontend
            'website-responsive' => [
                'basic' => [400000, 240000],
                'standard' => [600000, 350000, true],
                'advanced' => [1000000, 600000],
            ],
            'homepage-landing-page' => [
                'basic' => [350000, 200000],
                'standard' => [550000, 300000, true],
                'advanced' => [950000, 550000],
            ],
            'katalog-produk' => [
                'basic' => [300000, 180000],
                'standard' => [500000, 300000, true],
                'advanced' => [850000, 500000],
            ],
            'kategori-produk-frontend' => [
                'basic' => [200000, 120000],
                'standard' => [350000, 200000, true],
                'advanced' => [600000, 350000],
            ],
            'pencarian-produk' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 250000, true],
                'advanced' => [800000, 450000],
            ],
            'tombol-whatsapp-telepon' => [
                'basic' => [150000, 90000],
                'standard' => [250000, 150000, true],
                'advanced' => [450000, 250000],
            ],
            'menu-navigasi' => [
                'basic' => [150000, 90000],
                'standard' => [250000, 150000, true],
                'advanced' => [400000, 220000],
            ],
            'template-premium' => [
                'basic' => [400000, 250000],
                'standard' => [750000, 450000, true],
                'advanced' => [1400000, 800000],
            ],
            'integrasi-sosial-media' => [
                'basic' => [150000, 90000],
                'standard' => [250000, 150000, true],
                'advanced' => [450000, 250000],
            ],
            'free-banner-logo' => [
                'basic' => [200000, 120000],
                'standard' => [350000, 200000, true],
                'advanced' => [650000, 380000],
            ],
            'google-maps' => [
                'basic' => [150000, 90000],
                'standard' => [250000, 150000, true],
                'advanced' => [400000, 220000],
            ],
            'standard-contact-form' => [
                'basic' => [150000, 90000],
                'standard' => [250000, 150000, true],
                'advanced' => [400000, 220000],
            ],

            // Produk
            'manajemen-stok' => [
                'basic' => [300000, 180000],
                'standard' => [550000, 320000, true],
                'advanced' => [950000, 550000],
            ],
            'variasi-produk' => [
                'basic' => [300000, 180000],
                'standard' => [550000, 320000, true],
                'advanced' => [950000, 550000],
            ],

            // Transaksi
            'keranjang-belanja' => [
                'basic' => [350000, 200000],
                'standard' => [650000, 380000, true],
                'advanced' => [1100000, 650000],
            ],
            'checkout-online' => [
                'basic' => [450000, 260000],
                'standard' => [850000, 500000, true],
                'advanced' => [1500000, 880000],
            ],
            'akun-pelanggan' => [
                'basic' => [300000, 180000],
                'standard' => [550000, 320000, true],
                'advanced' => [950000, 550000],
            ],
            'riwayat-pesanan' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 260000, true],
                'advanced' => [750000, 440000],
            ],

            // Pembayaran
            'pembayaran-online' => [
                'basic' => [400000, 240000],
                'standard' => [750000, 440000, true],
                'advanced' => [1300000, 750000],
            ],
            'payment-gateway' => [
                'basic' => [500000, 300000],
                'standard' => [950000, 550000, true],
                'advanced' => [1650000, 950000],
            ],

            // Pengiriman
            'integrasi-ongkos-kirim' => [
                'basic' => [500000, 300000],
                'standard' => [950000, 550000, true],
                'advanced' => [1650000, 950000],
            ],

            // Marketing
            'voucher-promo' => [
                'basic' => [450000, 260000],
                'standard' => [850000, 500000, true],
                'advanced' => [1500000, 880000],
            ],
            'wishlist' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 260000, true],
                'advanced' => [750000, 440000],
            ],
            'review-rating' => [
                'basic' => [350000, 200000],
                'standard' => [650000, 380000, true],
                'advanced' => [1100000, 650000],
            ],

            // Administrasi
            'dashboard-admin' => [
                'basic' => [450000, 260000],
                'standard' => [850000, 500000, true],
                'advanced' => [1500000, 880000],
            ],
            'laporan-penjualan' => [
                'basic' => [350000, 200000],
                'standard' => [650000, 380000, true],
                'advanced' => [1100000, 650000],
            ],
            'export-excel-pdf' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 260000, true],
                'advanced' => [750000, 440000],
            ],
            'multi-role-admin' => [
                'basic' => [450000, 260000],
                'standard' => [900000, 520000, true],
                'advanced' => [1600000, 950000],
            ],
            'notifikasi-email' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 260000, true],
                'advanced' => [750000, 440000],
            ],
            'notifikasi-whatsapp' => [
                'basic' => [400000, 240000],
                'standard' => [750000, 440000, true],
                'advanced' => [1350000, 800000],
            ],
            'analytics' => [
                'basic' => [350000, 200000],
                'standard' => [650000, 380000, true],
                'advanced' => [1150000, 680000],
            ],

            // SEO
            'seo-dasar' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 260000, true],
                'advanced' => [750000, 440000],
            ],
            'seo-lanjutan' => [
                'basic' => [500000, 300000],
                'standard' => [950000, 550000, true],
                'advanced' => [1750000, 1000000],
            ],

            // Integrasi
            'api-integrasi-eksternal' => [
                'basic' => [800000, 480000],
                'standard' => [1500000, 900000, true],
                'advanced' => [2800000, 1650000],
                'custom' => [4500000, 2600000],
            ],

            // Infrastruktur (included by default)
            'hosting-vps' => [
                'basic' => [500000, 300000],
                'standard' => [950000, 550000, true],
                'advanced' => [1750000, 1000000],
            ],
            'domain' => [
                'basic' => [200000, 120000],
                'standard' => [300000, 180000, true],
                'advanced' => [500000, 300000],
            ],
            'ssl-https' => [
                'basic' => [150000, 90000],
                'standard' => [250000, 150000, true],
                'advanced' => [450000, 250000],
            ],
            'backup-otomatis' => [
                'basic' => [250000, 150000],
                'standard' => [450000, 260000, true],
                'advanced' => [750000, 440000],
            ],
            'maintenance-teknis' => [
                'basic' => [400000, 240000],
                'standard' => [750000, 440000, true],
                'advanced' => [1350000, 800000],
            ],
        ];

        foreach ($prices as $featureSlug => $variants) {
            $feature = Feature::where('slug', $featureSlug)->first();
            if (! $feature) {
                continue;
            }

            foreach ($variants as $complexity => $priceData) {
                $sellingPrice = $priceData[0];
                $costPrice = $priceData[1];
                $isDefault = $priceData[2] ?? false;

                FeaturePrice::updateOrCreate(
                    [
                        'feature_id' => $feature->id,
                        'complexity' => $complexity,
                    ],
                    [
                        'price_type' => 'fixed',
                        'cost_price' => $costPrice,
                        'selling_price' => $sellingPrice,
                        'price_min' => null,
                        'price_max' => null,
                        'is_default' => $isDefault,
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
