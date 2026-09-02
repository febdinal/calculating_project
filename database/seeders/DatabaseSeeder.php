<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters: seeders run in dependency order.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,         // 1. Users (admin + demo)
            PackageSeeder::class,      // 2. Packages (Basic, Medium, Professional, Custom)
            CategorySeeder::class,     // 3. Feature categories
            FeatureSeeder::class,      // 4. Features (requires categories)
            PackageFeatureSeeder::class, // 5. Feature matrix (requires packages + features)
            FeaturePriceSeeder::class, // 6. Price variants (requires features)
            AddonSeeder::class,        // 7. Add-ons
        ]);
    }
}
