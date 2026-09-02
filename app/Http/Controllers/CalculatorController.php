<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Package;
use App\Services\CalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    public function __construct(
        protected CalculatorService $calculatorService
    ) {}

    /**
     * Landing Page / Package Selection.
     */
    public function packages(): View
    {
        $packages = Package::where('status', 'active')
            ->withCount(['packageFeatures as included_count' => function ($query) {
                $query->where('status', 'included');
            }])
            ->orderBy('sort_order')
            ->get();

        $addons = Addon::where('status', 'active')->orderBy('sort_order')->get();

        return view('calculator.packages', compact('packages', 'addons'));
    }

    /**
     * Kanban Board Calculator.
     */
    public function index(Request $request): View
    {
        $packageSlug = $request->query('package', 'medium');

        // Find package or fallback to first active or medium
        $selectedPackage = Package::where('slug', $packageSlug)->where('status', 'active')->first()
            ?? Package::where('slug', 'medium')->first()
            ?? Package::first();

        $allPackages = Package::where('status', 'active')->orderBy('sort_order')->get();

        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // Load all active features with their prices and dependencies
        $features = Feature::where('status', 'active')
            ->with([
                'category',
                'prices' => function ($q) {
                    $q->where('status', 'active')->orderBy('complexity');
                },
                'dependencies',
                'requiredFeatures',
            ])
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->get();

        // Get package feature status mapping for the selected package
        $packageFeaturesMap = $selectedPackage ? $selectedPackage->packageFeatures->keyBy('feature_id') : collect();

        // Prepare data for frontend Kanban with strictly sanitized user-safe fields
        $featuresData = $features->map(function ($feature) use ($packageFeaturesMap) {
            $pf = $packageFeaturesMap->get($feature->id);
            $packageStatus = $pf ? $pf->status : ($feature->is_infrastructure ? 'included' : 'not_available');

            $prices = $feature->prices->mapWithKeys(function ($price) {
                return [
                    $price->complexity => [
                        'selling_price' => (float) ($price->selling_price ?? 0),
                        'price_type' => $price->price_type,
                        'price_min' => (float) ($price->price_min ?? 0),
                        'price_max' => (float) ($price->price_max ?? 0),
                        'is_default' => $price->is_default,
                    ],
                ];
            });

            // If no prices defined yet, default to standard with 0
            if ($prices->isEmpty()) {
                $prices = [
                    'standard' => [
                        'selling_price' => 0.0,
                        'price_type' => 'fixed',
                        'price_min' => 0.0,
                        'price_max' => 0.0,
                        'is_default' => true,
                    ],
                ];
            }

            return [
                'id' => $feature->id,
                'name' => $feature->name,
                'slug' => $feature->slug,
                'description' => $feature->description,
                'icon' => $feature->icon ?? '⚙️',
                'category_id' => $feature->category_id,
                'category_name' => $feature->category?->name ?? 'Lainnya',
                'is_infrastructure' => (bool) $feature->is_infrastructure,
                'package_status' => $packageStatus, // 'included', 'optional', 'not_available'
                'prices' => $prices,
                'required_feature_ids' => $feature->requiredFeatures->pluck('id')->values()->all(),
                'required_feature_names' => $feature->requiredFeatures->pluck('name')->values()->all(),
            ];
        });

        // Add-ons
        $addons = Addon::where('status', 'active')->orderBy('sort_order')->get()->map(function ($addon) {
            return [
                'id' => $addon->id,
                'name' => $addon->name,
                'slug' => $addon->slug,
                'description' => $addon->description,
                'icon' => $addon->icon ?? '🧩',
                'category' => $addon->category ?? 'Add-on',
                'price_type' => $addon->price_type,
                'selling_price' => (float) ($addon->selling_price ?? $addon->price_min ?? 0),
                'price_min' => (float) ($addon->price_min ?? 0),
                'price_max' => (float) ($addon->price_max ?? 0),
            ];
        });

        // Included infrastructure list
        $infrastructureFeatures = $features->where('is_infrastructure', true)->values();

        return view('calculator.index', compact(
            'selectedPackage',
            'allPackages',
            'categories',
            'featuresData',
            'addons',
            'infrastructureFeatures'
        ));
    }

    /**
     * Server-side dynamic estimation endpoint.
     * Guarantees total calculation security and consistency.
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'features' => ['nullable', 'array'],
            'features.*.feature_id' => ['required', 'exists:features,id'],
            'features.*.complexity' => ['nullable', 'string', 'in:basic,standard,advanced,custom'],
            'features.*.quantity' => ['nullable', 'integer', 'min:1'],
            'addons' => ['nullable', 'array'],
            'addons.*.addon_id' => ['required', 'exists:addons,id'],
            'addons.*.quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $selectedFeatures = $validated['features'] ?? [];
        $selectedAddons = $validated['addons'] ?? [];

        $estimate = $this->calculatorService->estimateFromLivePrices(
            $package,
            $selectedFeatures,
            $selectedAddons
        );

        // Return ONLY user-safe selling information (no cost, no profit, no margin)
        return response()->json($estimate['selling']);
    }
}
