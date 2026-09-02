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
}
