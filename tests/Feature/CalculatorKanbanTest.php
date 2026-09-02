<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculatorKanbanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_renders_packages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Basic');
        $response->assertSee('Medium');
        $response->assertSee('Professional');
        $response->assertSee('Web Custom');
        $response->assertSee('Rp 4.000.000');
        $response->assertSee('Rp 8.000.000');
        $response->assertSee('Rp 15.000.000');
    }

    public function test_calculator_page_renders_kanban_board(): void
    {
        $response = $this->get('/calculator?package=medium');
        $response->assertStatus(200);
        $response->assertSee('E-Commerce Project Configurator');
        $response->assertSee('Fitur Tersedia');
        $response->assertSee('Fitur Terpilih');
        $response->assertSee('Project Summary');
    }

    public function test_calculator_api_calculates_safely_without_cost_prices(): void
    {
        $mediumPackage = Package::where('slug', 'medium')->first();
        $feature = Feature::where('slug', 'voucher-promo')->first();

        // Voucher promo is optional / not included in Medium
        $response = $this->postJson(route('calculator.calculate'), [
            'package_id' => $mediumPackage->id,
            'features' => [
                [
                    'feature_id' => $feature->id,
                    'complexity' => 'standard',
                    'quantity' => 1,
                ],
            ],
            'addons' => [],
        ]);

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('package_price', $data);
        $this->assertArrayHasKey('total', $data);
        $this->assertEquals((float) $mediumPackage->price, (float) $data['package_price']);

        // Assert NO cost data is exposed in public API response
        $responseContent = $response->getContent();
        $this->assertStringNotContainsString('cost_price', $responseContent);
        $this->assertStringNotContainsString('total_cost', $responseContent);
        $this->assertStringNotContainsString('total_profit', $responseContent);
        $this->assertStringNotContainsString('margin_percentage', $responseContent);
    }
}
