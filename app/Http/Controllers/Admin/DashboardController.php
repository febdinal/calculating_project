<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Package;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan statistik ringkas dashboard admin.
     */
    public function index(): View
    {
        $totalPackages = Package::count();
        $totalCategories = Category::count();
        $totalMainFeatures = Feature::whereNull('parent_id')->count();
        $totalSubFeatures = Feature::whereNotNull('parent_id')->count();

        $packages = Package::withCount('features')->orderBy('sort_order')->get();
        $categories = Category::withCount('mainFeatures')->orderBy('sort_order')->get();
        $recentFeatures = Feature::whereNull('parent_id')->with(['category', 'subFeatures'])->latest()->take(6)->get();

        // Financial & Margin metrics (Internal)
        $allFeatures = Feature::whereNull('parent_id')->with('subFeatures')->get();
        $totalSellingPrice = $allFeatures->sum(fn ($f) => $f->calculated_price);
        $totalRealPrice = $allFeatures->sum(fn ($f) => $f->calculated_real_price);
        $totalMarginNominal = $totalSellingPrice - $totalRealPrice;
        $totalMarginPercent = $totalSellingPrice > 0 ? round(($totalMarginNominal / $totalSellingPrice) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalPackages',
            'totalCategories',
            'totalMainFeatures',
            'totalSubFeatures',
            'packages',
            'categories',
            'recentFeatures',
            'totalSellingPrice',
            'totalRealPrice',
            'totalMarginNominal',
            'totalMarginPercent'
        ));
    }
}
