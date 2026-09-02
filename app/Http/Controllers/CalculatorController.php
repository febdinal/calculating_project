<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feature;
use App\Models\Package;
use App\Services\CalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    public function __construct(
        protected CalculatorService $calculatorService
    ) {}

    /**
     * Halaman Pilihan Paket.
     */
    public function packages(): View
    {
        $packages = Package::where('status', 'active')
            ->withCount('features')
            ->orderBy('sort_order')
            ->get();

        return view('calculator.packages', compact('packages'));
    }

    /**
     * Halaman Utama Kanban Configurator.
     */
    public function index(Request $request): View
    {
        $packageParam = $request->query('package', 'medium');

        // Cari paket berdasarkan slug atau ID
        $selectedPackage = Package::where('status', 'active')
            ->where(function ($q) use ($packageParam) {
                if (is_numeric($packageParam)) {
                    $q->where('id', $packageParam);
                } else {
                    $q->where('slug', $packageParam);
                }
            })
            ->first()
            ?? Package::where('slug', 'medium')->first()
            ?? Package::where('status', 'active')->first()
            ?? Package::firstOrFail();

        $allPackages = Package::where('status', 'active')->orderBy('sort_order')->get();

        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // Ambil semua fitur utama (parent_id is null) beserta sub-fitur dan kategorinya
        $features = Feature::where('status', 'active')
            ->whereNull('parent_id')
            ->with([
                'category',
                'subFeatures' => function ($q) {
                    $q->where('status', 'active')->orderBy('sort_order');
                },
            ])
            ->orderBy('sort_order')
            ->get();

        // ID fitur yang sudah termasuk dalam paket yang dipilih
        $includedFeatureIds = $selectedPackage->features()->pluck('features.id')->all();

        // Format data fitur untuk frontend Kanban (harga fitur dihitung dari total harga sub-fitur)
        $featuresData = $features->map(function ($feature) use ($includedFeatureIds) {
            $isIncluded = in_array($feature->id, $includedFeatureIds, true);

            $subFeaturesList = $feature->subFeatures->map(fn ($sub) => [
                'id' => $sub->id,
                'name' => $sub->name,
                'price' => (float) ($sub->price ?? 0),
            ])->values()->all();

            $calculatedPrice = $feature->subFeatures->isNotEmpty()
                ? (float) $feature->subFeatures->sum('price')
                : (float) ($feature->price ?? 0);

            return [
                'id' => $feature->id,
                'name' => $feature->name,
                'slug' => $feature->slug,
                'description' => $feature->description,
                'icon' => $feature->icon ?? '⚙️',
                'category_id' => $feature->category_id,
                'category_name' => $feature->category?->name ?? 'Lainnya',
                'price' => $calculatedPrice,
                'default_price' => $calculatedPrice,
                'is_included' => $isIncluded,
                'sub_features' => $subFeaturesList,
            ];
        });

        return view('calculator.index', compact(
            'selectedPackage',
            'allPackages',
            'categories',
            'featuresData',
            'includedFeatureIds'
        ));
    }

    /**
     * Endpoint API Kalkulasi Harga Realtime Server-Side.
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'feature_ids' => ['nullable', 'array'],
            'feature_ids.*' => ['integer', 'exists:features,id'],
            'sub_feature_ids' => ['nullable', 'array'],
            'sub_feature_ids.*' => ['integer', 'exists:features,id'],
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $featureIds = $validated['feature_ids'] ?? [];
        $subFeatureIds = $validated['sub_feature_ids'] ?? null;

        $result = $this->calculatorService->calculate($package, $featureIds, $subFeatureIds);

        return response()->json([
            'package_id' => $package->id,
            'package_name' => $package->name,
            'package_price' => (float) $package->price,
            'included_deduction' => (float) $result['included_deduction'],
            'adjusted_package_price' => (float) $result['adjusted_package_price'],
            'period' => $package->period,
            'included_features' => $result['included_features'],
            'additional_features' => $result['additional_features'],
            'additional_features_total' => (float) $result['additional_features_total'],
            'total' => (float) $result['total'],
        ]);
    }

    /**
     * Generate & Stream / Download Dokumen Estimasi Biaya PDF.
     */
    public function pdf(Request $request): Response
    {
        $packageId = $request->input('package_id') ?? $request->query('package_id');
        $rawFeatures = $request->input('feature_ids') ?? $request->query('feature_ids');
        $rawSubFeatures = $request->input('sub_feature_ids') ?? $request->query('sub_feature_ids');

        if (is_string($rawFeatures)) {
            $featureIds = array_filter(explode(',', $rawFeatures));
        } elseif (is_array($rawFeatures)) {
            $featureIds = $rawFeatures;
        } else {
            $featureIds = [];
        }

        $subFeatureIds = null;
        if (is_string($rawSubFeatures)) {
            $subFeatureIds = array_filter(explode(',', $rawSubFeatures));
        } elseif (is_array($rawSubFeatures)) {
            $subFeatureIds = $rawSubFeatures;
        }

        $package = $packageId ? Package::find($packageId) : null;
        if (! $package) {
            $package = Package::where('slug', 'medium')->first() ?? Package::firstOrFail();
        }

        $calc = $this->calculatorService->calculate($package, $featureIds, $subFeatureIds);

        $pdf = Pdf::loadView('calculator.pdf', [
            'package' => $package,
            'packagePrice' => $calc['package_price'],
            'includedDeduction' => $calc['included_deduction'],
            'adjustedPackagePrice' => $calc['adjusted_package_price'],
            'includedFeatures' => $calc['included_features'],
            'additionalFeatures' => $calc['additional_features'],
            'additionalFeaturesTotal' => $calc['additional_features_total'],
            'total' => $calc['total'],
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'Estimasi-Biaya-Website-'.Str::slug($package->name).'.pdf';

        return $pdf->stream($fileName);
    }
}
