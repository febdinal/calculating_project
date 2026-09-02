<?php

namespace Tests\Feature;

use App\Models\FeaturePrice;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_user_receives_forbidden(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard & Profit Overview');
    }

    public function test_admin_can_view_packages_and_feature_matrix(): void
    {
        $admin = User::where('role', 'admin')->first();
        $package = Package::first();

        $response = $this->actingAs($admin)->get(route('admin.packages.index'));
        $response->assertStatus(200);
        $response->assertSee($package->name);

        $matrixResponse = $this->actingAs($admin)->get(route('admin.packages.features', $package));
        $matrixResponse->assertStatus(200);
        $matrixResponse->assertSee("Matriks Fitur: {$package->name}");
    }

    public function test_admin_can_view_pricing_and_batch_update(): void
    {
        $admin = User::where('role', 'admin')->first();
        $price = FeaturePrice::first();

        $response = $this->actingAs($admin)->get(route('admin.pricing.index'));
        $response->assertStatus(200);

        // Test batch update
        $batchResponse = $this->actingAs($admin)->post(route('admin.pricing.batch-update'), [
            'prices' => [
                $price->id => [
                    'cost_price' => '1200000',
                    'selling_price' => '2000000',
                ],
            ],
        ]);

        $batchResponse->assertSessionHas('success');
        $this->assertDatabaseHas('feature_prices', [
            'id' => $price->id,
            'cost_price' => 1200000,
            'selling_price' => 2000000,
        ]);
    }
}
