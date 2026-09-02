<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Tampilkan daftar paket.
     */
    public function index(): View
    {
        $packages = Package::withCount('features')
            ->orderBy('sort_order')
            ->get();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Form tambah paket baru.
     */
    public function create(): View
    {
        return view('admin.packages.create');
    }

    /**
     * Simpan paket baru.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('price')) {
            $cleanPrice = preg_replace('/[^0-9]/', '', (string) $request->input('price'));
            $request->merge(['price' => $cleanPrice === '' ? 0 : (float) $cleanPrice]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:packages,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'period' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? (Package::max('sort_order') + 1);

        $package = Package::create($validated);

        return redirect()->route('admin.packages.features', $package)
            ->with('success', "Paket '{$package->name}' berhasil dibuat! Silakan tentukan fitur bawaan paket ini.");
    }

    /**
     * Form edit paket.
     */
    public function edit(Package $package): View
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Perbarui paket.
     */
    public function update(Request $request, Package $package): RedirectResponse
    {
        if ($request->has('price')) {
            $cleanPrice = preg_replace('/[^0-9]/', '', (string) $request->input('price'));
            $request->merge(['price' => $cleanPrice === '' ? 0 : (float) $cleanPrice]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:packages,slug,'.$package->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'period' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', "Paket '{$package->name}' berhasil diperbarui.");
    }

    /**
     * Hapus paket.
     */
    public function destroy(Package $package): RedirectResponse
    {
        $name = $package->name;
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', "Paket '{$name}' berhasil dihapus.");
    }

    /**
     * Halaman Checklist Fitur Bawaan Paket.
     */
    public function features(Package $package): View
    {
        $categories = Category::with(['mainFeatures' => function ($q) {
            $q->orderBy('sort_order')->with('subFeatures');
        }])->orderBy('sort_order')->get();

        $includedFeatureIds = $package->features()->pluck('features.id')->toArray();

        return view('admin.packages.features', compact('package', 'categories', 'includedFeatureIds'));
    }

    /**
     * Simpan Checklist Fitur Bawaan Paket.
     */
    public function updateFeatures(Request $request, Package $package): RedirectResponse
    {
        $featureIds = $request->input('feature_ids', []);
        $package->features()->sync($featureIds);

        return redirect()->route('admin.packages.index')
            ->with('success', "Fitur bawaan untuk paket '{$package->name}' berhasil diperbarui.");
    }

    /**
     * Toggle status aktif/nonaktif paket.
     */
    public function toggleStatus(Package $package): RedirectResponse
    {
        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();

        return back()->with('success', "Status paket '{$package->name}' diubah menjadi {$package->status}.");
    }
}
