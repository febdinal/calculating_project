<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Seeder;

/**
 * Seeds the feature matrix per the document:
 * "Perencanaan Anggaran Teknis E-Commerce - Paket Sewa"
 *
 * Status values:
 *   included     = ✓ termasuk dalam paket
 *   optional     = bisa ditambahkan sebagai add-on berbayar
 *   not_available = tidak tersedia / tidak relevan untuk paket ini
 */
class PackageFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $basic = Package::where('slug', 'basic')->first();
        $medium = Package::where('slug', 'medium')->first();
        $professional = Package::where('slug', 'professional')->first();

        if (! $basic || ! $medium || ! $professional) {
            $this->command->error('Packages not found. Run PackageSeeder first.');

            return;
        }

        /**
         * Matrix definition:
         * [feature_slug => [basic, medium, professional]]
         *
         * Values: 'included' | 'optional' | 'not_available'
         */
        $matrix = [
            // ─── Website & Frontend ─────────────────────────────────────
            // Basic = katalog online, semua fitur tampilan dasar termasuk
            'website-responsive' => ['included', 'included', 'included'],
            'homepage-landing-page' => ['included', 'included', 'included'],
            'katalog-produk' => ['included', 'included', 'included'],
            'kategori-produk-frontend' => ['included', 'included', 'included'],
            'pencarian-produk' => ['included', 'included', 'included'],
            'tombol-whatsapp-telepon' => ['included', 'included', 'included'],
            'menu-navigasi' => ['included', 'included', 'included'],
            'template-premium' => ['included', 'included', 'included'],
            'integrasi-sosial-media' => ['included', 'included', 'included'],
            'free-banner-logo' => ['included', 'included', 'included'],
            'google-maps' => ['included', 'included', 'included'],
            'standard-contact-form' => ['included', 'included', 'included'],

            // ─── Produk ─────────────────────────────────────────────────
            // Basic = katalog statis (no stock management)
            'manajemen-stok' => ['not_available', 'included', 'included'],
            'variasi-produk' => ['not_available', 'included', 'included'],

            // ─── Transaksi ───────────────────────────────────────────────
            // Basic = WhatsApp order only, no cart/checkout
            'keranjang-belanja' => ['not_available', 'included', 'included'],
            'checkout-online' => ['not_available', 'included', 'included'],
            'akun-pelanggan' => ['not_available', 'included', 'included'],
            'riwayat-pesanan' => ['not_available', 'included', 'included'],

            // ─── Pembayaran ──────────────────────────────────────────────
            // Basic = manual transfer only
            'pembayaran-online' => ['not_available', 'included', 'included'],
            'payment-gateway' => ['not_available', 'included', 'included'],

            // ─── Pengiriman ──────────────────────────────────────────────
            'integrasi-ongkos-kirim' => ['not_available', 'included', 'included'],

            // ─── Marketing ───────────────────────────────────────────────
            // Basic & Medium = no marketing tools; Professional = all
            'voucher-promo' => ['not_available', 'not_available', 'included'],
            'wishlist' => ['not_available', 'not_available', 'included'],
            'review-rating' => ['not_available', 'not_available', 'included'],

            // ─── Administrasi ────────────────────────────────────────────
            // Basic = minimal admin; Medium = full reporting; Professional = advanced
            'dashboard-admin' => ['included', 'included', 'included'],
            'laporan-penjualan' => ['not_available', 'included', 'included'],
            'export-excel-pdf' => ['not_available', 'included', 'included'],
            'multi-role-admin' => ['not_available', 'not_available', 'included'],
            'notifikasi-email' => ['not_available', 'included', 'included'],
            'notifikasi-whatsapp' => ['not_available', 'not_available', 'included'],
            'analytics' => ['not_available', 'not_available', 'included'],

            // ─── SEO ─────────────────────────────────────────────────────
            'seo-dasar' => ['included', 'included', 'included'],
            'seo-lanjutan' => ['not_available', 'not_available', 'included'],

            // ─── Integrasi ───────────────────────────────────────────────
            'api-integrasi-eksternal' => ['not_available', 'optional', 'included'],

            // ─── Infrastruktur (semua paket mencakup infrastruktur dasar) ─
            'hosting-vps' => ['included', 'included', 'included'],
            'domain' => ['included', 'included', 'included'],
            'ssl-https' => ['included', 'included', 'included'],
            'backup-otomatis' => ['included', 'included', 'included'],
            'maintenance-teknis' => ['included', 'included', 'included'],
        ];

        $packages = [$basic, $medium, $professional];

        foreach ($matrix as $featureSlug => [$basicStatus, $mediumStatus, $professionalStatus]) {
            $feature = Feature::where('slug', $featureSlug)->first();
            if (! $feature) {
                $this->command->warn("Feature not found: {$featureSlug}");

                continue;
            }

            $statuses = [$basicStatus, $mediumStatus, $professionalStatus];

            foreach ($packages as $index => $package) {
                PackageFeature::firstOrCreate(
                    [
                        'package_id' => $package->id,
                        'feature_id' => $feature->id,
                    ],
                    ['status' => $statuses[$index]]
                );
            }
        }
    }
}
