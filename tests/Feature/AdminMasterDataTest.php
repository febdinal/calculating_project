<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Feature;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminMasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@featureconfig.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@featureconfig.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Master Data');
    }

    public function test_admin_can_create_package(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.packages.store'), [
            'name' => 'Enterprise Plus',
            'slug' => 'enterprise-plus',
            'description' => 'Paket enterprise khusus',
            'price' => 25000000,
            'period' => 'tahun',
            'sort_order' => 5,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('packages', [
            'name' => 'Enterprise Plus',
            'slug' => 'enterprise-plus',
            'price' => 25000000,
        ]);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.categories.store'), [
            'name' => 'Keamanan & Backup',
            'slug' => 'keamanan-backup',
            'description' => 'Fitur keamanan data',
            'icon' => '🔒',
            'sort_order' => 10,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Keamanan & Backup',
            'slug' => 'keamanan-backup',
        ]);
    }

    public function test_admin_can_create_feature_and_sub_features(): void
    {
        $category = Category::create([
            'name' => 'Marketing',
            'slug' => 'marketing',
            'status' => 'active',
        ]);

        // Create main feature with sub-features having individual prices
        $response = $this->actingAs($this->adminUser)->post(route('admin.features.store'), [
            'category_id' => $category->id,
            'name' => 'Affiliate Program',
            'slug' => 'affiliate-program',
            'status' => 'active',
            'sub_features' => [
                ['name' => 'Komisi Penjualan', 'price' => 2000000, 'sort_order' => 1],
                ['name' => 'Dashboard Afiliasi', 'price' => 1500000, 'sort_order' => 2],
            ],
        ]);

        $this->assertDatabaseHas('features', [
            'name' => 'Affiliate Program',
            'price' => 3500000,
            'parent_id' => null,
        ]);

        $mainFeature = Feature::where('slug', 'affiliate-program')->first();
        $this->assertNotNull($mainFeature);
        $this->assertEquals(2, $mainFeature->subFeatures()->count());
        $this->assertEquals(3500000, $mainFeature->calculated_price);
    }

    public function test_admin_can_assign_included_features_to_package(): void
    {
        $package = Package::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'price' => 3000000,
            'period' => 'tahun',
            'status' => 'active',
        ]);

        $feature1 = Feature::create([
            'name' => 'Feature 1',
            'slug' => 'feature-1',
            'price' => 1000000,
            'status' => 'active',
        ]);

        $feature2 = Feature::create([
            'name' => 'Feature 2',
            'slug' => 'feature-2',
            'price' => 2000000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.packages.features.update', $package), [
            'feature_ids' => [$feature1->id, $feature2->id],
        ]);

        $this->assertEquals(2, $package->features()->count());
    }

    public function test_admin_forms_accept_rupiah_formatted_price_and_store_pure_number_in_database(): void
    {
        // 1. Test Package store with formatted "Rp 8.500.000"
        $this->actingAs($this->adminUser)->post(route('admin.packages.store'), [
            'name' => 'Custom Paket Rupiah',
            'slug' => 'custom-paket-rupiah',
            'price' => 'Rp 8.500.000',
            'period' => 'tahun',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('packages', [
            'slug' => 'custom-paket-rupiah',
            'price' => 8500000,
        ]);

        // 2. Test Feature store with sub-features having "Rp 1.500.000" and "Rp 750.000"
        $category = Category::create([
            'name' => 'Testing Cat',
            'slug' => 'testing-cat',
            'status' => 'active',
        ]);

        $this->actingAs($this->adminUser)->post(route('admin.features.store'), [
            'category_id' => $category->id,
            'name' => 'Fitur Rupiah Test',
            'slug' => 'fitur-rupiah-test',
            'price' => 'Rp 2.250.000',
            'status' => 'active',
            'sub_features' => [
                ['name' => 'Sub 1', 'price' => 'Rp 1.500.000', 'sort_order' => 1],
                ['name' => 'Sub 2', 'price' => 'Rp 750.000', 'sort_order' => 2],
            ],
        ]);

        $this->assertDatabaseHas('features', [
            'slug' => 'fitur-rupiah-test',
            'price' => 2250000,
        ]);

        $sub1 = Feature::where('name', 'Sub 1')->first();
        $this->assertNotNull($sub1);
        $this->assertEquals(1500000, (float) $sub1->price);
    }

    public function test_admin_can_create_feature_with_sub_features_real_price_and_margins(): void
    {
        $category = Category::create([
            'name' => 'Analytics',
            'slug' => 'analytics',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.features.store'), [
            'category_id' => $category->id,
            'name' => 'Dashboard Pelaporan',
            'slug' => 'dashboard-pelaporan',
            'status' => 'active',
            'sub_features' => [
                ['name' => 'Laporan Harian', 'real_price' => 'Rp 600.000', 'price' => 'Rp 1.000.000', 'sort_order' => 1],
                ['name' => 'Export PDF', 'real_price' => 'Rp 400.000', 'price' => 'Rp 1.000.000', 'sort_order' => 2],
            ],
        ]);

        $response->assertRedirect(route('admin.features.index'));

        // Parent feature should have auto-synced total price and total real_price
        $mainFeature = Feature::where('slug', 'dashboard-pelaporan')->first();
        $this->assertNotNull($mainFeature);
        $this->assertEquals(2000000, (float) $mainFeature->price);
        $this->assertEquals(1000000, (float) $mainFeature->real_price);
        $this->assertEquals(1000000, (float) $mainFeature->margin);
        $this->assertEquals(50.0, (float) $mainFeature->margin_percentage);

        // Sub feature check
        $sub1 = Feature::where('name', 'Laporan Harian')->first();
        $this->assertNotNull($sub1);
        $this->assertEquals(1000000, (float) $sub1->price);
        $this->assertEquals(600000, (float) $sub1->real_price);
        $this->assertEquals(400000, (float) $sub1->margin);
        $this->assertEquals(40.0, (float) $sub1->margin_percentage);
    }

    public function test_admin_features_index_displays_real_price_and_margin(): void
    {
        $category = Category::create([
            'name' => 'Security',
            'slug' => 'security',
            'status' => 'active',
        ]);

        $feature = Feature::create([
            'category_id' => $category->id,
            'name' => 'SSL Certificate',
            'slug' => 'ssl-cert',
            'price' => 500000,
            'real_price' => 200000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.features.index'));

        $response->assertStatus(200);
        $response->assertSee('Harga Real (Internal)');
        $response->assertSee('Margin Profit');
        $response->assertSee('Total Modal Real (Internal)');
        $response->assertSee('Rp 200.000');
        $response->assertSee('Rp 500.000');
        $response->assertSee('60%');
    }

    public function test_admin_can_update_feature_and_sub_features_real_price(): void
    {
        $category = Category::create([
            'name' => 'SEO',
            'slug' => 'seo',
            'status' => 'active',
        ]);

        $feature = Feature::create([
            'category_id' => $category->id,
            'name' => 'SEO Suite',
            'slug' => 'seo-suite',
            'price' => 1000000,
            'real_price' => 600000,
            'status' => 'active',
        ]);

        $sub = Feature::create([
            'category_id' => $category->id,
            'parent_id' => $feature->id,
            'name' => 'Sitemap Generator',
            'slug' => 'sitemap-gen',
            'price' => 1000000,
            'real_price' => 600000,
            'sort_order' => 1,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.features.update', $feature), [
            'name' => 'SEO Suite Updated',
            'slug' => 'seo-suite',
            'category_id' => $category->id,
            'status' => 'active',
            'sub_features' => [
                [
                    'id' => $sub->id,
                    'name' => 'Sitemap Generator Pro',
                    'price' => 'Rp 1.500.000',
                    'real_price' => 'Rp 700.000',
                    'sort_order' => 1,
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.features.index'));

        $feature->refresh();
        $this->assertEquals(1500000, (float) $feature->price);
        $this->assertEquals(700000, (float) $feature->real_price);
        $this->assertEquals(800000, (float) $feature->margin);
        $this->assertEquals(53.3, (float) $feature->margin_percentage);
    }
}
