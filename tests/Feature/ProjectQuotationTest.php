<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Feature;
use App\Models\Package;
use App\Models\Project;
use App\Models\Quotation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectQuotationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_save_project_with_frozen_price_snapshots(): void
    {
        $package = Package::where('slug', 'medium')->first();
        $feature = Feature::where('slug', 'voucher-promo')->first(); // Optional for Medium
        $addon = Addon::where('slug', 'mobile-app-android')->first();

        $payload = [
            'package_id' => $package->id,
            'name' => 'Toko Sepatu Online Kita',
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@tokosepatu.com',
            'customer_phone' => '081234567890',
            'customer_company' => 'PT Sepatu Nusantara',
            'notes' => 'Harap diintegrasikan dengan kurir JNE dan SiCepat.',
            'mode' => 'save',
            'features' => [
                [
                    'feature_id' => $feature->id,
                    'complexity' => 'standard',
                    'quantity' => 1,
                ],
            ],
            'addons' => [
                [
                    'addon_id' => $addon->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson(route('projects.store'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'redirect_url',
            'project_id',
        ]);

        $project = Project::find($response->json('project_id'));
        $this->assertNotNull($project);
        $this->assertEquals('Toko Sepatu Online Kita', $project->name);
        $this->assertEquals('draft', $project->status);
        $this->assertEquals((float) $package->price, (float) $project->package_price_snapshot);

        // Verify snapshot tables populated
        $this->assertDatabaseHas('project_features', [
            'project_id' => $project->id,
            'feature_id' => $feature->id,
            'feature_name' => $feature->name,
            'complexity' => 'standard',
        ]);

        $this->assertDatabaseHas('project_addons', [
            'project_id' => $project->id,
            'addon_id' => $addon->id,
            'addon_name' => $addon->name,
        ]);
    }

    public function test_can_request_quotation_and_generate_quotation_number(): void
    {
        $package = Package::where('slug', 'professional')->first();

        $payload = [
            'package_id' => $package->id,
            'name' => 'Fashion Marketplace Enterprise',
            'customer_name' => 'Siti Rahma',
            'customer_email' => 'siti@fashionbrand.com',
            'customer_phone' => '081987654321',
            'customer_company' => 'CV Fashion Indonesia',
            'mode' => 'quote',
            'features' => [],
            'addons' => [],
        ];

        $response = $this->postJson(route('projects.store'), $payload);
        $response->assertStatus(200);

        $project = Project::find($response->json('project_id'));
        $this->assertEquals('pending', $project->status);

        $this->assertNotNull($project->quotation);
        $this->assertMatchesRegularExpression('/^QUO-\d{6}-\d{4}$/', $project->quotation->quotation_number);
        $this->assertEquals('sent', $project->quotation->status);
    }

    public function test_customer_view_does_not_leak_cost_data(): void
    {
        $project = Project::factory()->create([
            'package_id' => Package::first()->id,
            'total_selling_price' => 12000000,
            'total_cost_price' => 7000000,
            'total_profit' => 5000000,
        ]);

        $response = $this->get(route('projects.show', $project));
        $response->assertStatus(200);
        $response->assertSee('Rp 12.000.000');

        $content = $response->getContent();
        $this->assertStringNotContainsString('7.000.000', $content);
        $this->assertStringNotContainsString('5.000.000', $content);
        $this->assertStringNotContainsString('cost_price', $content);
        $this->assertStringNotContainsString('total_profit', $content);
    }

    public function test_quotation_pdf_export_streams_properly(): void
    {
        $package = Package::first();
        $project = Project::factory()->create([
            'package_id' => $package->id,
            'package_price_snapshot' => $package->price,
            'total_selling_price' => 8000000,
            'total_cost_price' => 4500000,
            'total_profit' => 3500000,
        ]);

        Quotation::create([
            'project_id' => $project->id,
            'quotation_number' => Quotation::generateQuotationNumber(),
            'issued_at' => now(),
            'valid_until' => now()->addDays(30),
            'status' => 'sent',
        ]);

        $response = $this->get(route('projects.pdf', $project));
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_my_projects_lists_saved_session_projects(): void
    {
        $project = Project::factory()->create([
            'package_id' => Package::first()->id,
            'name' => 'Proyek Test Sesi',
        ]);

        $response = $this->withSession(['user_project_ids' => [$project->id]])
            ->get(route('projects.my-projects'));

        $response->assertStatus(200);
        $response->assertSee('Proyek Test Sesi');
    }
}
