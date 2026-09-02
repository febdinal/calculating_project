<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AddonController extends Controller
{
    /**
     * Display a listing of add-ons with cost, selling price, and margins.
     */
    public function index(): View
    {
        $addons = Addon::orderBy('sort_order')->get();

        return view('admin.addons.index', compact('addons'));
    }

    /**
     * Show the form for creating a new add-on.
     */
    public function create(): View
    {
        return view('admin.addons.create');
    }

    /**
     * Store a newly created add-on in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:addons,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'price_type' => ['required', 'in:fixed,range,custom'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? (Addon::max('sort_order') + 1);

        Addon::create($validated);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Add-on baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified add-on.
     */
    public function edit(Addon $addon): View
    {
        return view('admin.addons.edit', compact('addon'));
    }

    /**
     * Update the specified add-on in storage.
     */
    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:addons,slug,'.$addon->id],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'price_type' => ['required', 'in:fixed,range,custom'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $addon->update($validated);

        return redirect()->route('admin.addons.index')
            ->with('success', "Add-on '{$addon->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified add-on from storage.
     */
    public function destroy(Addon $addon): RedirectResponse
    {
        $name = $addon->name;
        $addon->delete();

        return redirect()->route('admin.addons.index')
            ->with('success', "Add-on '{$name}' berhasil dihapus.");
    }

    /**
     * Quick toggle status.
     */
    public function toggleStatus(Addon $addon): RedirectResponse
    {
        $addon->status = $addon->status === 'active' ? 'inactive' : 'active';
        $addon->save();

        return back()->with('success', "Status add-on '{$addon->name}' diubah menjadi {$addon->status}.");
    }
}
