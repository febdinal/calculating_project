<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'package_id' => Package::factory(),
            'name' => fake()->company().' E-Commerce',
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_company' => fake()->company(),
            'notes' => fake()->sentence(),
            'status' => 'draft',
            'package_price_snapshot' => 8000000,
            'total_selling_price' => 8000000,
            'total_cost_price' => 4000000,
            'total_profit' => 4000000,
        ];
    }
}
