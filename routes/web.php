<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculatorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Website Feature Configurator & Price Calculator
|--------------------------------------------------------------------------
*/

// Public Flow
Route::get('/', function () {
    return redirect()->route('packages.select');
})->name('home');

Route::get('/packages', [CalculatorController::class, 'packages'])->name('packages.select');
Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
Route::post('/calculator/calculate', [CalculatorController::class, 'calculate'])->name('calculator.calculate');
Route::match(['get', 'post'], '/calculator/pdf', [CalculatorController::class, 'pdf'])->name('calculator.pdf');

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

    // Features Management (Main & Sub-Features)
    Route::resource('features', FeatureController::class);
});
