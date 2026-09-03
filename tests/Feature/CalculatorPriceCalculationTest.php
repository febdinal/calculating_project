<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Feature;
use App\Models\Package;
use App\Services\CalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorPriceCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected Package $mediumPackage;

    protected Feature $websiteFeature;

    protected Feature $homepageFeature;

    protected Feature $wishlistFeature;

    protected Feature $voucherFeature;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Category
        $category = Category::create([
            'name' => 'Website & Frontend',
            'slug' => 'website-frontend',
            'description' => 'Tampilan frontend',
            'icon' => '🌐',
            'sort_order' => 1,
            'status' => 'active',
        ]);

        // 2. Create Package
        $this->mediumPackage = Package::create([
            'name' => 'Medium',
            'slug' => 'medium',
            'description' => 'Paket website medium',
            'price' => 8000000,
            'period' => 'tahun',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        // 3. Create Features
        $this->websiteFeature = Feature::create([
            'category_id' => $category->id,
            'name' => 'Website Responsive',
            'slug' => 'website-responsive',
            'price' => 2000000,
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $this->homepageFeature = Feature::create([
            'category_id' => $category->id,
            'name' => 'Homepage Slider',
            'slug' => 'homepage-slider',
            'price' => 1000000,
            'status' => 'active',
            'sort_order' => 2,
        ]);

        $this->wishlistFeature = Feature::create([
            'category_id' => $category->id,
            'name' => 'Wishlist',
            'slug' => 'wishlist',
            'price' => 450000,
            'status' => 'active',
            'sort_order' => 3,
        ]);

        $this->voucherFeature = Feature::create([
            'category_id' => $category->id,
            'name' => 'Voucher Promo',
            'slug' => 'voucher-promo',
            'price' => 850000,
            'status' => 'active',
            'sort_order' => 4,
        ]);

        // 4. Attach included features to Medium Package
        $this->mediumPackage->features()->attach([
            $this->websiteFeature->id,
            $this->homepageFeature->id,
        ]);
    }

    public function test_landing_page_redirects_to_packages_list(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('packages.select'));
    }

    public function test_packages_page_displays_packages_and_pricing(): void
    {
        $response = $this->get(route('packages.select'));
        $response->assertStatus(200);
        $response->assertSee('Medium');
        $response->assertSee('Rp 3.000.000');
    }

    public function test_kanban_calculator_page_loads_with_selected_package(): void
    {
        $response = $this->get(route('calculator', ['package' => 'medium']));
        $response->assertStatus(200);
        $response->assertSee('Paket Medium');
        $response->assertSee('Website Responsive');
        $response->assertSee('Wishlist');
        $response->assertSee('3.000.000');
    }

    public function test_feature_price_is_calculated_from_sub_features_prices(): void
    {
        $category = Category::first();

        $katalog = Feature::create([
            'category_id' => $category->id,
            'name' => 'Katalog Produk',
            'slug' => 'katalog-produk',
            'price' => 0,
            'status' => 'active',
        ]);

        Feature::create([
            'category_id' => $category->id,
            'parent_id' => $katalog->id,
            'name' => 'Daftar Produk Grid',
            'slug' => 'daftar-produk-grid',
            'price' => 500000,
            'status' => 'active',
        ]);

        Feature::create([
            'category_id' => $category->id,
            'parent_id' => $katalog->id,
            'name' => 'Detail Produk',
            'slug' => 'detail-produk',
            'price' => 400000,
            'status' => 'active',
        ]);

        $katalog->refresh();
        $this->assertEquals(900000, $katalog->calculated_price);

        $service = new CalculatorService;
        $result = $service->calculate($this->mediumPackage, [
            $this->websiteFeature->id,
            $this->homepageFeature->id,
            $katalog->id,
        ]);

        $this->assertEquals(900000, $result['additional_features_total']);
        $this->assertEquals(3900000, $result['total']);
    }

    public function test_calculator_service_supports_custom_sub_feature_selection(): void
    {
        $category = Category::first();

        $package = Package::create([
            'name' => 'Standalone Pack',
            'slug' => 'standalone-pack',
            'price' => 8000000,
            'period' => 'tahun',
            'status' => 'active',
        ]);

        $katalog = Feature::create([
            'category_id' => $category->id,
            'name' => 'Katalog Produk Custom',
            'slug' => 'katalog-produk-custom',
            'price' => 0,
            'status' => 'active',
        ]);

        $sub1 = Feature::create([
            'category_id' => $category->id,
            'parent_id' => $katalog->id,
            'name' => 'Grid Produk',
            'slug' => 'grid-produk',
            'price' => 500000,
            'status' => 'active',
        ]);

        $sub2 = Feature::create([
            'category_id' => $category->id,
            'parent_id' => $katalog->id,
            'name' => 'Filter Variasi',
            'slug' => 'filter-variasi',
            'price' => 300000,
            'status' => 'active',
        ]);

        $service = new CalculatorService;

        // User only selects $sub1 (500.000) and unchecks $sub2 (300.000)
        $result = $service->calculate($package, [$katalog->id], [$sub1->id]);

        $this->assertEquals(500000, $result['additional_features_total']);
        $this->assertEquals(500000, $result['total']);
        $this->assertEquals(500000, $result['additional_features'][0]['price']);
    }

    public function test_unchecking_included_sub_features_reduces_package_price_and_grand_total(): void
    {
        $category = Category::first();

        // Create included feature with 2 sub-features
        $webFeature = Feature::create([
            'category_id' => $category->id,
            'name' => 'Website Responsive Pro',
            'slug' => 'website-responsive-pro',
            'price' => 2000000,
            'status' => 'active',
        ]);

        $subWeb1 = Feature::create([
            'category_id' => $category->id,
            'parent_id' => $webFeature->id,
            'name' => 'Mobile Layout',
            'slug' => 'mobile-layout',
            'price' => 1200000,
            'status' => 'active',
        ]);

        $subWeb2 = Feature::create([
            'category_id' => $category->id,
            'parent_id' => $webFeature->id,
            'name' => 'Dark Mode',
            'slug' => 'dark-mode',
            'price' => 800000,
            'status' => 'active',
        ]);

        $package = Package::create([
            'name' => 'Test Pack',
            'slug' => 'test-pack',
            'price' => 5000000,
            'period' => 'tahun',
            'status' => 'active',
        ]);

        $package->features()->attach([$webFeature->id]);

        $service = new CalculatorService;

        // 1. When all sub-features of the included feature are selected:
        // Package base price is sum of included features (1.200.000 + 800.000 = 2.000.000)
        $resFull = $service->calculate($package, [$webFeature->id], [$subWeb1->id, $subWeb2->id]);
        $this->assertEquals(2000000, $resFull['total']);
        $this->assertEquals(0, $resFull['included_deduction']);

        // 2. When user unchecks $subWeb2 (800.000 deduction):
        $resDeducted = $service->calculate($package, [$webFeature->id], [$subWeb1->id]);
        $this->assertEquals(800000, $resDeducted['included_deduction']);
        $this->assertEquals(1200000, $resDeducted['adjusted_package_price']);
        $this->assertEquals(1200000, $resDeducted['total']);
    }

    public function test_calculator_service_calculates_correct_price_formula(): void
    {
        $service = new CalculatorService;

        // Selected features: Website (included), Homepage (included), Wishlist (additional), Voucher (additional)
        $selectedFeatureIds = [
            $this->websiteFeature->id,
            $this->homepageFeature->id,
            $this->wishlistFeature->id,
            $this->voucherFeature->id,
        ];

        $result = $service->calculate($this->mediumPackage, $selectedFeatureIds);

        // Expected:
        // Package Base Price (sum of included features 2.000.000 + 1.000.000): 3.000.000
        // Included Features Count: 2
        // Additional Features Count: 2
        // Additional Features Total: 450.000 + 850.000 = 1.300.000
        // Grand Total: 3.000.000 + 1.300.000 = 4.300.000
        $this->assertEquals(3000000, $result['package_price']);
        $this->assertCount(2, $result['included_features']);
        $this->assertCount(2, $result['additional_features']);
        $this->assertEquals(1300000, $result['additional_features_total']);
        $this->assertEquals(4300000, $result['total']);
    }

    public function test_package_price_follows_sum_of_features_not_inputted_price(): void
    {
        // Set manual inputted price to an arbitrary number
        $this->mediumPackage->update(['price' => 99000000]);

        $service = new CalculatorService;
        $result = $service->calculate($this->mediumPackage, [
            $this->websiteFeature->id,
            $this->homepageFeature->id,
        ]);

        // Base price must be sum of features (2.000.000 + 1.000.000 = 3.000.000), NOT 99.000.000
        $this->assertEquals(3000000, $result['package_price']);
        $this->assertEquals(3000000, $result['total']);
    }

    public function test_calculator_api_endpoint_calculates_and_returns_clean_json(): void
    {
        $response = $this->postJson(route('calculator.calculate'), [
            'package_id' => $this->mediumPackage->id,
            'feature_ids' => [
                $this->websiteFeature->id,
                $this->homepageFeature->id,
                $this->wishlistFeature->id,
                $this->voucherFeature->id,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'package_id' => $this->mediumPackage->id,
            'package_name' => 'Medium',
            'package_price' => 3000000,
            'additional_features_total' => 1300000,
            'total' => 4300000,
        ]);
    }

    public function test_pdf_generation_endpoint_generates_pdf_document(): void
    {
        $response = $this->post(route('calculator.pdf'), [
            'package_id' => $this->mediumPackage->id,
            'feature_ids' => implode(',', [
                $this->websiteFeature->id,
                $this->wishlistFeature->id,
            ]),
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
