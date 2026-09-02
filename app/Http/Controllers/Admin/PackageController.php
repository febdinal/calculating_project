<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Display a listing of packages.
     */
    public function index(): View
    {
        $packages = Package::withCount([
            'packageFeatures as included_count' => function ($query) {
                $query->where('status', 'included');
            },
            'packageFeatures as optional_count' => function ($query) {
                $query->where('status', 'optional');
            },
            'projects',
        ])
            ->orderBy('sort_order')
            ->get();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create(): View
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:packages,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'billing_period' => ['required', 'in:monthly,annual'],
            'target_user' => ['nullable', 'string', 'max:255'],
            'price_type' => ['required', 'in:fixed,custom'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,draft'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? (Package::max('sort_order') + 1);

        $package = Package::create($validated);

        // Initialize feature matrix entries for all active features with 'not_available'
        $allFeatures = Feature::all();
        foreach ($allFeatures as $feature) {
            PackageFeature::create([
                'package_id' => $package->id,
                'feature_id' => $feature->id,
                'status' => $feature->is_infrastructure ? 'included' : 'not_available',
            ]);
        }

        return redirect()->route('admin.packages.features', $package)
            ->with('success', 'Paket berhasil dibuat! Silakan tentukan fitur yang termasuk dalam paket ini.');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package): View
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:packages,slug,'.$package->id],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'billing_period' => ['required', 'in:monthly,annual'],
            'target_user' => ['nullable', 'string', 'max:255'],
            'price_type' => ['required', 'in:fixed,custom'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,draft'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', "Paket '{$package->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package): RedirectResponse
    {
        $name = $package->name;
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', "Paket '{$name}' berhasil dihapus.");
    }

    /**
     * Manage the feature matrix for the specified package.
     */
    public function features(Package $package): View
    {
        $package->load('packageFeatures');

        // Fetch all categories with their features
        $categories = Category::with(['features' => function ($query) {
            $query->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        // Key package features by feature_id for easy lookup
        $packageFeaturesMap = $package->packageFeatures->keyBy('feature_id');

        return view('admin.packages.features', compact('package', 'categories', 'packageFeaturesMap'));
    }

    /**
     * Update the feature matrix for the specified package.
     */
    public function updateFeatures(Request $request, Package $package): RedirectResponse
    {
        $featureStatuses = $request->input('features', []);
        $featureNotes = $request->input('notes', []);

        foreach ($featureStatuses as $featureId => $status) {
            if (in_array($status, ['included', 'optional', 'not_available'])) {
                PackageFeature::updateOrCreate(
                    [
                        'package_id' => $package->id,
                        'feature_id' => $featureId,
                    ],
                    [
                        'status' => $status,
                        'notes' => $featureNotes[$featureId] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('admin.packages.features', $package)
            ->with('success', "Matriks fitur untuk paket '{$package->name}' berhasil disimpan.");
    }

    /**
     * Quick toggle status (active/inactive).
     */
    public function toggleStatus(Package $package): RedirectResponse
    {
        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();

        return back()->with('success', "Status paket '{$package->name}' diubah menjadi {$package->status}.");
    }
}
