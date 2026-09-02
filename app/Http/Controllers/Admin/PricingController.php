<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FeaturePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Display the master pricing management table for all features.
     */
    public function index(Request $request): View
    {
        $query = Feature::with(['category', 'prices' => function ($q) {
            $q->orderBy('complexity');
        }]);

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            if ($request->input('filter') === 'unpriced') {
                $query->whereHas('prices', function ($q) {
                    $q->whereNull('cost_price')->orWhereNull('selling_price');
                });
            } elseif ($request->input('filter') === 'priced') {
                $query->whereDoesntHave('prices', function ($q) {
                    $q->whereNull('cost_price')->orWhereNull('selling_price');
                });
            }
        }

        $features = $query->orderBy('category_id')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::orderBy('sort_order')->get();

        // Overall stats
        $totalVariants = FeaturePrice::count();
        $pricedVariants = FeaturePrice::whereNotNull('cost_price')->whereNotNull('selling_price')->count();
        $unpricedVariants = $totalVariants - $pricedVariants;

        return view('admin.pricing.index', compact(
            'features',
            'categories',
            'totalVariants',
            'pricedVariants',
            'unpricedVariants'
        ));
    }

    /**
     * Show pricing configuration form for a specific feature.
     */
    public function feature(Feature $feature): View
    {
        $feature->load(['category', 'prices']);
        $complexities = ['basic', 'standard', 'advanced', 'custom'];
        $existingPrices = $feature->prices->keyBy('complexity');

        return view('admin.pricing.feature', compact('feature', 'complexities', 'existingPrices'));
    }

    /**
     * Update price records for a specific feature.
     */
    public function update(Request $request, Feature $feature): RedirectResponse
    {
        $pricesData = $request->input('prices', []);

        foreach ($pricesData as $complexity => $data) {
            if (! in_array($complexity, ['basic', 'standard', 'advanced', 'custom'])) {
                continue;
            }

            $isActive = ! empty($data['is_active']);
            $costPrice = isset($data['cost_price']) && $data['cost_price'] !== '' ? (float) str_replace(['.', ','], ['', '.'], $data['cost_price']) : null;
            $sellingPrice = isset($data['selling_price']) && $data['selling_price'] !== '' ? (float) str_replace(['.', ','], ['', '.'], $data['selling_price']) : null;
            $priceMin = isset($data['price_min']) && $data['price_min'] !== '' ? (float) str_replace(['.', ','], ['', '.'], $data['price_min']) : null;
            $priceMax = isset($data['price_max']) && $data['price_max'] !== '' ? (float) str_replace(['.', ','], ['', '.'], $data['price_max']) : null;
            $priceType = $data['price_type'] ?? 'fixed';
            $isDefault = ($request->input('default_complexity') === $complexity);

            if ($isActive) {
                FeaturePrice::updateOrCreate(
                    [
                        'feature_id' => $feature->id,
                        'complexity' => $complexity,
                    ],
                    [
                        'price_type' => $priceType,
                        'cost_price' => $costPrice,
                        'selling_price' => $sellingPrice,
                        'price_min' => $priceMin,
                        'price_max' => $priceMax,
                        'is_default' => $isDefault,
                        'status' => 'active',
                    ]
                );
            } else {
                // If deactivated, either soft delete or set inactive
                FeaturePrice::where('feature_id', $feature->id)
                    ->where('complexity', $complexity)
                    ->update(['status' => 'inactive']);
            }
        }

        return redirect()->route('admin.pricing.index')
            ->with('success', "Harga untuk fitur '{$feature->name}' berhasil disimpan.");
    }

    /**
     * Batch update multiple prices directly from master pricing matrix table.
     */
    public function batchUpdate(Request $request): RedirectResponse
    {
        $prices = $request->input('prices', []);

        foreach ($prices as $priceId => $data) {
            $priceRecord = FeaturePrice::find($priceId);
            if ($priceRecord) {
                $costPrice = isset($data['cost_price']) && $data['cost_price'] !== ''
                    ? (float) str_replace(['.', ','], ['', '.'], $data['cost_price'])
                    : null;

                $sellingPrice = isset($data['selling_price']) && $data['selling_price'] !== ''
                    ? (float) str_replace(['.', ','], ['', '.'], $data['selling_price'])
                    : null;

                $priceRecord->update([
                    'cost_price' => $costPrice,
                    'selling_price' => $sellingPrice,
                ]);
            }
        }

        return back()->with('success', 'Perubahan harga berhasil disimpan secara massal.');
    }
}
