<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeatureController extends Controller
{
    /**
     * Tampilkan daftar fitur utama beserta sub-fiturnya.
     */
    public function index(Request $request): View
    {
        $query = Feature::whereNull('parent_id')
            ->with(['category', 'subFeatures' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->withCount('subFeatures');

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $features = $query->orderBy('category_id')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();

        // Hitung ringkasan finansial internal (Harga Jual, Modal/Real, Margin)
        $allFeaturesForStats = Feature::whereNull('parent_id')->with('subFeatures')->get();
        $totalSellingPrice = $allFeaturesForStats->sum(fn ($f) => $f->calculated_price);
        $totalRealPrice = $allFeaturesForStats->sum(fn ($f) => $f->calculated_real_price);
        $totalMarginNominal = $totalSellingPrice - $totalRealPrice;
        $totalMarginPercent = $totalSellingPrice > 0 ? round(($totalMarginNominal / $totalSellingPrice) * 100, 1) : 0;

        return view('admin.features.index', compact(
            'features',
            'categories',
            'totalSellingPrice',
            'totalRealPrice',
            'totalMarginNominal',
            'totalMarginPercent'
        ));
    }

    /**
     * Form tambah fitur baru.
     */
    public function create(Request $request): View
    {
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();
        $parentFeatures = Feature::whereNull('parent_id')->orderBy('name')->get();
        $selectedParentId = $request->query('parent_id');

        return view('admin.features.create', compact('categories', 'parentFeatures', 'selectedParentId'));
    }

    /**
     * Simpan fitur baru (Utama atau Sub-Fitur).
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('price')) {
            $cleanPrice = preg_replace('/[^0-9]/', '', (string) $request->input('price'));
            $request->merge(['price' => $cleanPrice === '' ? 0 : (float) $cleanPrice]);
        }

        if ($request->has('real_price')) {
            $cleanRealPrice = preg_replace('/[^0-9]/', '', (string) $request->input('real_price'));
            $request->merge(['real_price' => $cleanRealPrice === '' ? 0 : (float) $cleanRealPrice]);
        }

        if ($request->has('sub_features') && is_array($request->input('sub_features'))) {
            $subs = $request->input('sub_features');
            foreach ($subs as $k => $sub) {
                if (isset($sub['price'])) {
                    $cleanSubPrice = preg_replace('/[^0-9]/', '', (string) $sub['price']);
                    $subs[$k]['price'] = $cleanSubPrice === '' ? 0 : (float) $cleanSubPrice;
                }
                if (isset($sub['real_price'])) {
                    $cleanSubRealPrice = preg_replace('/[^0-9]/', '', (string) $sub['real_price']);
                    $subs[$k]['real_price'] = $cleanSubRealPrice === '' ? 0 : (float) $cleanSubRealPrice;
                }
            }
            $request->merge(['sub_features' => $subs]);
        }

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'parent_id' => ['nullable', 'exists:features,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:features,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'real_price' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'sub_features' => ['nullable', 'array'],
            'sub_features.*.name' => ['required_with:sub_features', 'string', 'max:255'],
            'sub_features.*.price' => ['nullable', 'numeric', 'min:0'],
            'sub_features.*.real_price' => ['nullable', 'numeric', 'min:0'],
            'sub_features.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            $originalSlug = $validated['slug'];
            $count = 1;
            while (Feature::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = "{$originalSlug}-{$count}";
                $count++;
            }
        }

        // Jika sub-fitur langsung, warisi category_id dari parent jika kosong
        if (! empty($validated['parent_id'])) {
            $parent = Feature::find($validated['parent_id']);
            if ($parent && empty($validated['category_id'])) {
                $validated['category_id'] = $parent->category_id;
            }
        }

        $validated['price'] = $validated['price'] ?? 0;
        $validated['real_price'] = $validated['real_price'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? (Feature::where('category_id', $validated['category_id'])->max('sort_order') + 1);

        $feature = Feature::create($validated);

        // Jika fitur utama memiliki sub-fitur dinamis
        if (empty($validated['parent_id']) && ! empty($request->input('sub_features'))) {
            $totalSubPrice = 0;
            $totalSubRealPrice = 0;
            foreach ($request->input('sub_features') as $index => $subData) {
                if (! empty($subData['name'])) {
                    $subPrice = isset($subData['price']) && $subData['price'] !== '' ? (float) $subData['price'] : 0;
                    $subRealPrice = isset($subData['real_price']) && $subData['real_price'] !== '' ? (float) $subData['real_price'] : 0;
                    $totalSubPrice += $subPrice;
                    $totalSubRealPrice += $subRealPrice;

                    Feature::create([
                        'category_id' => $feature->category_id,
                        'parent_id' => $feature->id,
                        'name' => $subData['name'],
                        'slug' => Str::slug($feature->slug.'-'.$subData['name'].'-'.uniqid()),
                        'price' => $subPrice,
                        'real_price' => $subRealPrice,
                        'sort_order' => $subData['sort_order'] ?? ($index + 1),
                        'status' => 'active',
                    ]);
                }
            }

            // Update harga fitur utama dari total harga dan harga real sub-fitur
            if ($totalSubPrice > 0 || $totalSubRealPrice > 0) {
                $feature->update([
                    'price' => $totalSubPrice,
                    'real_price' => $totalSubRealPrice,
                ]);
            }
        } elseif (! empty($validated['parent_id'])) {
            // Jika ini adalah penambahan sub-fitur langsung, update harga parent
            $parent = Feature::find($validated['parent_id']);
            $parent?->syncPriceFromSubFeatures();
        }

        return redirect()->route('admin.features.index')
            ->with('success', "Fitur '{$feature->name}' berhasil ditambahkan.");
    }

    /**
     * Form edit fitur.
     */
    public function edit(Feature $feature): View
    {
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();
        $parentFeatures = Feature::whereNull('parent_id')
            ->where('id', '!=', $feature->id)
            ->orderBy('name')
            ->get();

        $feature->load('subFeatures');

        return view('admin.features.edit', compact('feature', 'categories', 'parentFeatures'));
    }

    /**
     * Perbarui fitur.
     */
    public function update(Request $request, Feature $feature): RedirectResponse
    {
        if ($request->has('price')) {
            $cleanPrice = preg_replace('/[^0-9]/', '', (string) $request->input('price'));
            $request->merge(['price' => $cleanPrice === '' ? 0 : (float) $cleanPrice]);
        }

        if ($request->has('real_price')) {
            $cleanRealPrice = preg_replace('/[^0-9]/', '', (string) $request->input('real_price'));
            $request->merge(['real_price' => $cleanRealPrice === '' ? 0 : (float) $cleanRealPrice]);
        }

        if ($request->has('sub_features') && is_array($request->input('sub_features'))) {
            $subs = $request->input('sub_features');
            foreach ($subs as $k => $sub) {
                if (isset($sub['price'])) {
                    $cleanSubPrice = preg_replace('/[^0-9]/', '', (string) $sub['price']);
                    $subs[$k]['price'] = $cleanSubPrice === '' ? 0 : (float) $cleanSubPrice;
                }
                if (isset($sub['real_price'])) {
                    $cleanSubRealPrice = preg_replace('/[^0-9]/', '', (string) $sub['real_price']);
                    $subs[$k]['real_price'] = $cleanSubRealPrice === '' ? 0 : (float) $cleanSubRealPrice;
                }
            }
            $request->merge(['sub_features' => $subs]);
        }

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'parent_id' => ['nullable', 'exists:features,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:features,slug,'.$feature->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'real_price' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['price'] = $validated['price'] ?? 0;
        $validated['real_price'] = $validated['real_price'] ?? 0;
        $feature->update($validated);

        // Simpan / update sub-fitur beserta harga masing-masing
        if ($request->has('sub_features')) {
            $subItems = $request->input('sub_features', []);
            $keepIds = [];
            $totalSubPrice = 0;
            $totalSubRealPrice = 0;

            foreach ($subItems as $subData) {
                if (! empty($subData['name'])) {
                    $subPrice = isset($subData['price']) && $subData['price'] !== '' ? (float) $subData['price'] : 0;
                    $subRealPrice = isset($subData['real_price']) && $subData['real_price'] !== '' ? (float) $subData['real_price'] : 0;
                    $totalSubPrice += $subPrice;
                    $totalSubRealPrice += $subRealPrice;

                    if (! empty($subData['id'])) {
                        $sub = Feature::find($subData['id']);
                        if ($sub && $sub->parent_id == $feature->id) {
                            $sub->update([
                                'name' => $subData['name'],
                                'price' => $subPrice,
                                'real_price' => $subRealPrice,
                                'sort_order' => $subData['sort_order'] ?? 0,
                            ]);
                            $keepIds[] = $sub->id;
                        }
                    } else {
                        $newSub = Feature::create([
                            'category_id' => $feature->category_id,
                            'parent_id' => $feature->id,
                            'name' => $subData['name'],
                            'slug' => Str::slug($feature->slug.'-'.$subData['name'].'-'.uniqid()),
                            'price' => $subPrice,
                            'real_price' => $subRealPrice,
                            'sort_order' => $subData['sort_order'] ?? 0,
                            'status' => 'active',
                        ]);
                        $keepIds[] = $newSub->id;
                    }
                }
            }

            // Hapus sub-fitur yang tidak ada dalam daftar
            $feature->subFeatures()->whereNotIn('id', $keepIds)->delete();

            // Auto-sync harga dan harga real fitur utama dari total harga sub-fitur
            if (count($keepIds) > 0) {
                $feature->update([
                    'price' => $totalSubPrice,
                    'real_price' => $totalSubRealPrice,
                ]);
            }
        } elseif ($feature->isSub() && $feature->parent) {
            $feature->parent->syncPriceFromSubFeatures();
        }

        return redirect()->route('admin.features.index')
            ->with('success', "Fitur '{$feature->name}' berhasil diperbarui.");
    }

    /**
     * Hapus fitur.
     */
    public function destroy(Feature $feature): RedirectResponse
    {
        $name = $feature->name;
        $parent = $feature->parent;
        $feature->delete();

        if ($parent) {
            $parent->syncPriceFromSubFeatures();
        }

        return redirect()->route('admin.features.index')
            ->with('success', "Fitur '{$name}' berhasil dihapus.");
    }
}
