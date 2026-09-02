<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $name = fake()->unique()->word().' Package';

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'price' => 8000000,
            'billing_period' => 'annual',
            'target_user' => 'UKM & Toko Online',
            'price_type' => 'fixed',
            'is_featured' => false,
            'sort_order' => 1,
            'status' => 'active',
        ];
    }
}
