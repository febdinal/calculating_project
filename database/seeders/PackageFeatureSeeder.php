<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Seeder;

class PackageFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $basicPackage = Package::where('slug', 'basic')->first();
        $mediumPackage = Package::where('slug', 'medium')->first();
        $premiumPackage = Package::where('slug', 'premium')->first();

        // Clear existing mappings
        PackageFeature::truncate();

        // 1. Basic Package features
        $basicSlugs = [
            'website-responsive',
            'halaman-statis-profil',
            'katalog-produk-interaktif',
            'pemesanan-direct-whatsapp',
            'optimasi-seo-onpage',
        ];

        if ($basicPackage) {
            $basicFeatureIds = Feature::whereIn('slug', $basicSlugs)->whereNull('parent_id')->pluck('id');
            $basicPackage->features()->sync($basicFeatureIds);
        }

        // 2. Medium Package features
        $mediumSlugs = [
            'website-responsive',
            'custom-homepage-slider',
            'halaman-statis-profil',
            'katalog-produk-interaktif',
            'galeri-foto-zoom',
            'manajemen-stok-inventori',
            'pemesanan-direct-whatsapp',
            'keranjang-checkout-online',
            'transfer-bank-manual',
            'payment-gateway-otomatis',
            'kalkulator-ongkir-otomatis',
            'dashboard-admin-laporan',
            'optimasi-seo-onpage',
            'google-analytics-pixel',
        ];

        if ($mediumPackage) {
            $mediumFeatureIds = Feature::whereIn('slug', $mediumSlugs)->whereNull('parent_id')->pluck('id');
            $mediumPackage->features()->sync($mediumFeatureIds);
        }

        // 3. Premium Package features (All main features)
        if ($premiumPackage) {
            $allMainFeatureIds = Feature::whereNull('parent_id')->pluck('id');
            $premiumPackage->features()->sync($allMainFeatureIds);
        }
    }
}
