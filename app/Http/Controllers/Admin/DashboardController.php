<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FeaturePrice;
use App\Models\Package;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin metrics and dashboard.
     */
    public function index(): View
    {
        $totalPackages = Package::count();
        $totalFeatures = Feature::count();
        $totalAddons = Addon::count();
        $totalProjects = Project::count();

        // Project financial metrics (excluding rejected ones for revenue calculations)
        $activeProjectsQuery = Project::where('status', '!=', 'rejected');
        $totalRevenue = (float) $activeProjectsQuery->sum('total_selling_price');
        $totalCost = (float) $activeProjectsQuery->sum('total_cost_price');
        $totalProfit = (float) $activeProjectsQuery->sum('total_profit');
        $averageMargin = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 2) : 0;

        // Count pricing configurations status
        $unpricedFeaturesCount = FeaturePrice::whereNull('cost_price')
            ->orWhereNull('selling_price')
            ->count();

        // Recent projects
        $recentProjects = Project::with(['package', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Packages with feature counts
        $packages = Package::withCount(['packageFeatures as included_features_count' => function ($query) {
            $query->where('status', 'included');
        }])->orderBy('sort_order')->get();

        // Categories with features count
        $categories = Category::withCount('features')->orderBy('sort_order')->get();

        // Project Status Counts
        $statusCounts = [
            'draft' => Project::where('status', 'draft')->count(),
            'pending' => Project::where('status', 'pending')->count(),
            'approved' => Project::where('status', 'approved')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'rejected' => Project::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalPackages',
            'totalFeatures',
            'totalAddons',
            'totalProjects',
            'totalRevenue',
            'totalCost',
            'totalProfit',
            'averageMargin',
            'unpricedFeaturesCount',
            'recentProjects',
            'packages',
            'categories',
            'statusCounts'
        ));
    }
}
