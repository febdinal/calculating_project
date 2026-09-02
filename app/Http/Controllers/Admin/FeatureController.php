<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FeatureDependency;
use App\Models\FeaturePrice;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeatureController extends Controller
{
    /**
     * Display a listing of features with filtering.
     */
    public function index(Request $request): View
    {
        $query = Feature::with(['category', 'prices', 'requiredFeatures'])
            ->withCount('prices');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Search by name / slug
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by infrastructure flag
        if ($request->filled('type')) {
            if ($request->input('type') === 'infrastructure') {
                $query->where('is_infrastructure', true);
            } elseif ($request->input('type') === 'functional') {
                $query->where('is_infrastructure', false);
            }
        }

        $features = $query->orderBy('category_id')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('sort_order')->get();

        return view('admin.features.index', compact('features', 'categories'));
    }

    /**
     * Show the form for creating a new feature.
     */
    public function create(): View
    {
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();
        $otherFeatures = Feature::where('status', 'active')->orderBy('name')->get();

        return view('admin.features.create', compact('categories', 'otherFeatures'));
    }

    /**
     * Store a newly created feature in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:features,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_infrastructure' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'dependencies' => ['nullable', 'array'],
            'dependencies.*' => ['exists:features,id'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_infrastructure'] = $request->boolean('is_infrastructure');
        $validated['sort_order'] = $validated['sort_order'] ?? (Feature::where('category_id', $validated['category_id'])->max('sort_order') + 1);

        $feature = Feature::create($validated);

        // Attach dependencies
        if (! empty($validated['dependencies'])) {
            foreach ($validated['dependencies'] as $depId) {
                FeatureDependency::create([
                    'feature_id' => $feature->id,
                    'required_feature_id' => $depId,
                ]);
            }
        }

        // Create default standard price record
        FeaturePrice::create([
            'feature_id' => $feature->id,
            'complexity' => 'standard',
            'price_type' => 'fixed',
            'cost_price' => null,
            'selling_price' => null,
            'is_default' => true,
            'status' => 'active',
        ]);

        // Attach to existing packages with default status
        $packages = Package::all();
        foreach ($packages as $package) {
            PackageFeature::create([
                'package_id' => $package->id,
                'feature_id' => $feature->id,
                'status' => $feature->is_infrastructure ? 'included' : 'not_available',
            ]);
        }

        return redirect()->route('admin.pricing.feature', $feature)
            ->with('success', "Fitur '{$feature->name}' berhasil dibuat! Silakan atur harga internal (cost) dan jual (selling).");
    }

    /**
     * Show the form for editing the specified feature.
     */
    public function edit(Feature $feature): View
    {
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();
        $otherFeatures = Feature::where('id', '!=', $feature->id)->where('status', 'active')->orderBy('name')->get();
        $currentDependencies = $feature->dependencies()->pluck('required_feature_id')->toArray();

        return view('admin.features.edit', compact('feature', 'categories', 'otherFeatures', 'currentDependencies'));
    }

    /**
     * Update the specified feature in storage.
     */
    public function update(Request $request, Feature $feature): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:features,slug,'.$feature->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_infrastructure' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'dependencies' => ['nullable', 'array'],
            'dependencies.*' => ['exists:features,id'],
        ]);

        $validated['is_infrastructure'] = $request->boolean('is_infrastructure');

        $feature->update($validated);

        // Sync dependencies
        $feature->dependencies()->delete();
        if (! empty($validated['dependencies'])) {
            foreach ($validated['dependencies'] as $depId) {
                if ($depId != $feature->id) {
                    FeatureDependency::create([
                        'feature_id' => $feature->id,
                        'required_feature_id' => $depId,
                    ]);
                }
            }
        }

        return redirect()->route('admin.features.index')
            ->with('success', "Fitur '{$feature->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified feature from storage.
     */
    public function destroy(Feature $feature): RedirectResponse
    {
        $name = $feature->name;
        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', "Fitur '{$name}' berhasil dihapus.");
    }
}
