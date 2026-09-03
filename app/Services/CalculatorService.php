<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\Package;

class CalculatorService
{
    /**
     * Calculate project cost based on selected package, selected feature IDs, and customized sub-feature IDs.
     *
     * @param  array<int>  $featureIds
     * @param  array<int>|null  $subFeatureIds
     * @return array{
     *     package: Package,
     *     package_price: float,
     *     included_deduction: float,
     *     adjusted_package_price: float,
     *     included_features: array,
     *     additional_features: array,
     *     additional_features_total: float,
     *     total: float
     * }
     */
    public function calculate(Package|int|string $package, array $featureIds = [], ?array $subFeatureIds = null): array
    {
        if (! ($package instanceof Package)) {
            $package = is_numeric($package)
                ? Package::findOrFail($package)
                : Package::where('slug', $package)->firstOrFail();
        }

        // Fetch all default included features for this package
        $defaultIncludedFeatures = $package->features()->with(['category', 'subFeatures'])->get();
        $includedFeatureIds = $defaultIncludedFeatures->pluck('id')->all();

        // Hitung harga dasar paket dari penjumlahan fitur bawaan paket (bukan harga statis inputan)
        $packagePrice = $package->slug === 'custom'
            ? 0.0
            : (float) $defaultIncludedFeatures->sum(function ($feature) {
                return $feature->subFeatures->isNotEmpty()
                    ? (float) $feature->subFeatures->sum('price')
                    : (float) ($feature->price ?? 0);
            });

        // Load the selected main features with their category and sub-features
        $cleanFeatureIds = array_filter(array_map('intval', $featureIds));

        $selectedFeatures = Feature::whereIn('id', $cleanFeatureIds)
            ->whereNull('parent_id')
            ->with(['category', 'subFeatures'])
            ->get();

        $includedList = [];
        $additionalList = [];
        $additionalFeaturesTotal = 0.0;
        $totalIncludedDeduction = 0.0;

        $hasCustomSubFeatures = is_array($subFeatureIds);
        $cleanSubFeatureIds = $hasCustomSubFeatures ? array_map('intval', $subFeatureIds) : [];

        // 1. Process selected features
        foreach ($selectedFeatures as $feature) {
            $isIncluded = in_array($feature->id, $includedFeatureIds, true);

            $subFeaturesList = [];
            $featureCalculatedPrice = 0.0;
            $featureDefaultFullPrice = 0.0;

            if ($feature->subFeatures->isNotEmpty()) {
                foreach ($feature->subFeatures as $sub) {
                    $isSelected = $hasCustomSubFeatures
                        ? in_array($sub->id, $cleanSubFeatureIds, true)
                        : true;

                    $subPrice = (float) ($sub->price ?? 0);
                    $featureDefaultFullPrice += $subPrice;

                    $subFeaturesList[] = [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'price' => $subPrice,
                        'is_selected' => $isSelected,
                    ];

                    if ($isSelected) {
                        $featureCalculatedPrice += $subPrice;
                    }
                }
            } else {
                $featureCalculatedPrice = (float) ($feature->price ?? 0);
                $featureDefaultFullPrice = $featureCalculatedPrice;
            }

            $featureItem = [
                'id' => $feature->id,
                'name' => $feature->name,
                'slug' => $feature->slug,
                'icon' => $feature->icon,
                'category_name' => $feature->category?->name ?? 'Lainnya',
                'description' => $feature->description,
                'price' => $featureCalculatedPrice,
                'default_price' => $featureDefaultFullPrice,
                'is_included' => $isIncluded,
                'sub_features' => $subFeaturesList,
            ];

            if ($isIncluded) {
                $includedList[] = $featureItem;
                // Jika sub-fitur bawaan paket tidak dipilih (dikurangi), hitung potongannya
                if ($featureCalculatedPrice < $featureDefaultFullPrice) {
                    $totalIncludedDeduction += ($featureDefaultFullPrice - $featureCalculatedPrice);
                }
            } else {
                $additionalList[] = $featureItem;
                $additionalFeaturesTotal += $featureCalculatedPrice;
            }
        }

        // 2. Check for default included features that were completely deselected/removed
        foreach ($defaultIncludedFeatures as $defaultIncluded) {
            if (! in_array($defaultIncluded->id, $cleanFeatureIds, true)) {
                $defaultPrice = $defaultIncluded->subFeatures->isNotEmpty()
                    ? (float) $defaultIncluded->subFeatures->sum('price')
                    : (float) ($defaultIncluded->price ?? 0);

                $totalIncludedDeduction += $defaultPrice;
            }
        }

        $adjustedPackagePrice = max(0.0, $packagePrice - $totalIncludedDeduction);
        $total = $adjustedPackagePrice + $additionalFeaturesTotal;

        return [
            'package' => $package,
            'package_price' => $packagePrice,
            'included_deduction' => $totalIncludedDeduction,
            'adjusted_package_price' => $adjustedPackagePrice,
            'included_features' => $includedList,
            'additional_features' => $additionalList,
            'additional_features_total' => $additionalFeaturesTotal,
            'total' => $total,
        ];
    }
}
