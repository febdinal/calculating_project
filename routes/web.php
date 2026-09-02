<?php

use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Landing & Package Selection
Route::get('/', [CalculatorController::class, 'packages'])->name('home');
Route::get('/packages', [CalculatorController::class, 'packages'])->name('packages.select');

// Kanban Calculator / Project Configurator
Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
Route::post('/calculator/calculate', [CalculatorController::class, 'calculate'])->name('calculator.calculate');

// Projects & Quotations (Customer Facing)
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/projects/{project}/quotation', [ProjectController::class, 'requestQuotation'])->name('projects.request-quotation');
Route::get('/projects/{project}/pdf', [ProjectController::class, 'pdf'])->name('projects.pdf');
Route::get('/projects/{project}/print', [ProjectController::class, 'printView'])->name('projects.print');
Route::get('/my-projects', [ProjectController::class, 'myProjects'])->name('projects.my-projects');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected by auth & admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Packages Management
    Route::get('packages/{package}/features', [PackageController::class, 'features'])->name('packages.features');
    Route::put('packages/{package}/features', [PackageController::class, 'updateFeatures'])->name('packages.features.update');
    Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::resource('packages', PackageController::class);

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Features Management
    Route::resource('features', FeatureController::class);

    // Pricing & Cost/Selling Price Management
    Route::get('pricing', [PricingController::class, 'index'])->name('pricing.index');
    Route::post('pricing/batch-update', [PricingController::class, 'batchUpdate'])->name('pricing.batch-update');
    Route::get('pricing/{feature}', [PricingController::class, 'feature'])->name('pricing.feature');
    Route::put('pricing/{feature}', [PricingController::class, 'update'])->name('pricing.update');

    // Addons Management
    Route::post('addons/{addon}/toggle-status', [AddonController::class, 'toggleStatus'])->name('addons.toggle-status');
    Route::resource('addons', AddonController::class);

    // Projects Management (Internal Cost & Quotations)
    Route::get('projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{project}', [AdminProjectController::class, 'show'])->name('projects.show');
    Route::patch('projects/{project}/status', [AdminProjectController::class, 'updateStatus'])->name('projects.status');
    Route::delete('projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');
});
