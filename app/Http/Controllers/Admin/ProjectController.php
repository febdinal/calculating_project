<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with admin financial metrics.
     */
    public function index(Request $request): View
    {
        $query = Project::with(['package', 'user', 'quotation'])
            ->withCount(['projectFeatures', 'projectAddons']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_company', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Display the specified project with full internal financial snapshot breakdown.
     */
    public function show(Project $project): View
    {
        $project->load([
            'package',
            'user',
            'quotation',
            'projectFeatures' => function ($q) {
                $q->orderBy('category_name')->orderBy('feature_name');
            },
            'projectAddons',
        ]);

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Update project status (Approve, Reject, Complete, etc.).
     */
    public function updateStatus(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,pending,approved,rejected,completed'],
            'notes' => ['nullable', 'string'],
        ]);

        $project->update([
            'status' => $validated['status'],
        ]);

        if (! empty($validated['notes'])) {
            $project->notes = ($project->notes ? $project->notes."\n[Admin Note ".now()->format('d/m/Y H:i').']: ' : '[Admin Note '.now()->format('d/m/Y H:i').']: ').$validated['notes'];
            $project->save();
        }

        return back()->with('success', "Status proyek berhasil diperbarui menjadi '{$project->status}'.");
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $name = $project->name;
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', "Proyek '{$name}' berhasil dihapus.");
    }
}
