<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Feature;
use App\Models\FeaturePrice;
use App\Models\Package;
use App\Models\Project;
use App\Models\ProjectAddon;
use App\Models\ProjectFeature;

/**
 * CalculatorService
 *
 * Server-side price calculation engine.
 * All totals are calculated here from database values — never trusted from browser input.
 *
 * SECURITY:
 * - cost_price figures are returned only in the 'admin' result key.
 * - Callers must check authorization before exposing admin results.
 * - This service never sends cost data to Blade views unless explicitly requested by admin routes.
 */
class CalculatorService
{
    /**
     * Calculate the subtotal for a single project feature (selling price × quantity).
     * Uses snapshot values already stored on the ProjectFeature row.
     */
    public function calculateFeatureSubtotal(ProjectFeature $projectFeature): float
    {
        if ($projectFeature->is_included_in_package) {
            return 0.0;
        }

        $price = (float) ($projectFeature->selling_price ?? 0);

        return round($price * $projectFeature->quantity, 2);
    }

    /**
     * Calculate the subtotal for a single project addon (selling price × quantity).
     * Uses snapshot values already stored on the ProjectAddon row.
     */
    public function calculateAddonSubtotal(ProjectAddon $projectAddon): float
    {
        $price = (float) ($projectAddon->selling_price ?? $projectAddon->price_min ?? 0);

        return round($price * $projectAddon->quantity, 2);
    }

    /**
     * Determine whether a feature is included in the given package.
     */
    public function isFeatureIncludedInPackage(Feature $feature, Package $package): bool
    {
        return $package->packageFeatures()
            ->where('feature_id', $feature->id)
            ->where('status', 'included')
            ->exists();
    }

    /**
     * Build a live estimate from current database prices for a draft configuration.
     * This does NOT use snapshots — it reads live feature/addon prices.
     *
     * @param  array<int, array{feature_id: int, complexity: string, quantity: int}>  $selectedFeatures
     * @param  array<int, array{addon_id: int, quantity: int}>  $selectedAddons
     * @return array{selling: array, admin: array}
     */
    public function estimateFromLivePrices(
        Package $package,
        array $selectedFeatures,
        array $selectedAddons
    ): array {
        $basePackagePrice = (float) ($package->price ?? 0);

        // Retrieve all default included features for this package
        $allIncludedFeatureIds = $package->includedFeatures()->pluck('features.id')->all();
        $selectedFeatureIds = collect($selectedFeatures)->pluck('feature_id')->all();

        // Calculate deduction for removed included features (50% credit per excluded included feature)
        $removedIncludedFeatureIds = array_diff($allIncludedFeatureIds, $selectedFeatureIds);
        $packageDeduction = 0.0;

        foreach ($removedIncludedFeatureIds as $remId) {
            $remPrice = FeaturePrice::where('feature_id', $remId)
                ->where('complexity', 'standard')
                ->where('status', 'active')
                ->first();

            $stdSelling = (float) ($remPrice?->selling_price ?? 300000);
            $packageDeduction += ($stdSelling * 0.5);
        }

        // Package price with deduction (floor at 30% of base price if custom, or 0)
        $effectivePackagePrice = max($basePackagePrice * 0.3, $basePackagePrice - $packageDeduction);

        $featureDetails = [];
        $featureTotalSelling = 0.0;
        $featureTotalCost = 0.0;

        foreach ($selectedFeatures as $item) {
            $feature = Feature::find($item['feature_id']);
            if (! $feature) {
                continue;
            }

            $isIncluded = $this->isFeatureIncludedInPackage($feature, $package);

            $priceRecord = FeaturePrice::where('feature_id', $feature->id)
                ->where('complexity', $item['complexity'] ?? 'standard')
                ->where('status', 'active')
                ->first();

            $sellingPrice = (float) ($priceRecord?->selling_price ?? 0);
            $costPrice = (float) ($priceRecord?->cost_price ?? 0);
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            // Included features have 0 additional charge in package; optional/add-ons are charged
            $itemSellingPrice = $isIncluded ? 0.0 : $sellingPrice;
            $subtotal = $itemSellingPrice * $quantity;

            if (! $isIncluded) {
                $featureTotalSelling += $subtotal;
                $featureTotalCost += $costPrice * $quantity;
            } else {
                // Internal cost for included feature
                $featureTotalCost += ($costPrice * 0.5) * $quantity;
            }

            $featureDetails[] = [
                'feature_id' => $feature->id,
                'feature_name' => $feature->name,
                'category_name' => $feature->category?->name,
                'complexity' => $item['complexity'] ?? 'standard',
                'quantity' => $quantity,
                'selling_price' => $itemSellingPrice,
                'cost_price' => $costPrice,
                'subtotal' => $subtotal,
                'is_included_in_package' => $isIncluded,
            ];
        }

        $addonDetails = [];
        $addonTotalSelling = 0.0;
        $addonTotalCost = 0.0;

        foreach ($selectedAddons as $item) {
            $addon = Addon::find($item['addon_id']);
            if (! $addon) {
                continue;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $sellingPrice = (float) ($addon->selling_price ?? $addon->price_min ?? 0);
            $costPrice = (float) ($addon->cost_price ?? 0);
            $subtotal = $sellingPrice * $quantity;

            $addonTotalSelling += $subtotal;
            $addonTotalCost += $costPrice * $quantity;

            $addonDetails[] = [
                'addon_id' => $addon->id,
                'addon_name' => $addon->name,
                'quantity' => $quantity,
                'selling_price' => $sellingPrice,
                'cost_price' => $costPrice,
                'price_min' => (float) ($addon->price_min ?? 0),
                'price_max' => (float) ($addon->price_max ?? 0),
                'subtotal' => $subtotal,
            ];
        }

        $totalSelling = $effectivePackagePrice + $featureTotalSelling + $addonTotalSelling;
        $totalCost = $featureTotalCost + $addonTotalCost;
        $totalProfit = $totalSelling - $totalCost;

        return [
            // Safe to expose to users (no cost data)
            'selling' => [
                'package_price' => $basePackagePrice,
                'effective_package_price' => $effectivePackagePrice,
                'package_deduction' => $packageDeduction,
                'package_name' => $package->name,
                'feature_total' => $featureTotalSelling,
                'addon_total' => $addonTotalSelling,
                'total' => $totalSelling,
                'features' => collect($featureDetails)->map(fn ($f) => collect($f)->except('cost_price')->all())->all(),
                'addons' => collect($addonDetails)->map(fn ($a) => collect($a)->except('cost_price')->all())->all(),
            ],
            // Admin-only: contains cost_price, profit, margin
            'admin' => [
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit,
                'margin_percentage' => $totalSelling > 0 ? round(($totalProfit / $totalSelling) * 100, 2) : null,
                'features' => $featureDetails,
                'addons' => $addonDetails,
            ],
        ];
    }

    /**
     * Recalculate totals for a saved project from its snapshot rows.
     */
    public function recalculateFromSnapshots(Project $project): array
    {
        $packagePrice = (float) ($project->package_price_snapshot ?? 0);

        $featureSelling = 0.0;
        $featureCost = 0.0;

        foreach ($project->projectFeatures as $pf) {
            if (! $pf->is_included_in_package) {
                $featureSelling += (float) $pf->subtotal;
                $featureCost += (float) ($pf->cost_price ?? 0) * $pf->quantity;
            }
        }

        $addonSelling = 0.0;
        $addonCost = 0.0;

        foreach ($project->projectAddons as $pa) {
            $addonSelling += (float) $pa->subtotal;
            $addonCost += (float) ($pa->cost_price ?? 0) * $pa->quantity;
        }

        $totalSelling = $packagePrice + $featureSelling + $addonSelling;
        $totalCost = $featureCost + $addonCost;
        $totalProfit = $totalSelling - $totalCost;

        return [
            'total_selling_price' => round($totalSelling, 2),
            'total_cost_price' => round($totalCost, 2),
            'total_profit' => round($totalProfit, 2),
        ];
    }

    /**
     * Save a project configuration with price snapshots.
     *
     * @param  array<int, array{feature_id: int, complexity: string, quantity: int}>  $selectedFeatures
     * @param  array<int, array{addon_id: int, quantity: int}>  $selectedAddons
     */
    public function saveProjectConfiguration(
        Project $project,
        Package $package,
        array $selectedFeatures,
        array $selectedAddons
    ): Project {
        $estimate = $this->estimateFromLivePrices($package, $selectedFeatures, $selectedAddons);

        // Snapshot the package price
        $project->package_price_snapshot = (float) ($package->price ?? 0);

        // Delete existing rows and re-insert snapshots
        $project->projectFeatures()->delete();
        $project->projectAddons()->delete();

        foreach ($estimate['admin']['features'] as $featureData) {
            ProjectFeature::create([
                'project_id' => $project->id,
                'feature_id' => $featureData['feature_id'],
                'feature_name' => $featureData['feature_name'],
                'category_name' => $featureData['category_name'],
                'complexity' => $featureData['complexity'],
                'quantity' => $featureData['quantity'],
                'cost_price' => $featureData['cost_price'] ?: null,
                'selling_price' => $featureData['selling_price'] ?: null,
                'subtotal' => $featureData['subtotal'],
                'is_included_in_package' => $featureData['is_included_in_package'],
            ]);
        }

        foreach ($estimate['admin']['addons'] as $addonData) {
            ProjectAddon::create([
                'project_id' => $project->id,
                'addon_id' => $addonData['addon_id'],
                'addon_name' => $addonData['addon_name'],
                'quantity' => $addonData['quantity'],
                'cost_price' => $addonData['cost_price'] ?: null,
                'selling_price' => $addonData['selling_price'] ?: null,
                'price_min' => $addonData['price_min'] ?: null,
                'price_max' => $addonData['price_max'] ?: null,
                'subtotal' => $addonData['subtotal'],
            ]);
        }

        $project->total_selling_price = $estimate['selling']['total'];
        $project->total_cost_price = $estimate['admin']['total_cost'];
        $project->total_profit = $estimate['admin']['total_profit'];
        $project->save();

        return $project;
    }
}
