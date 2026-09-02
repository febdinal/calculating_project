@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard & Profit Overview')
@section('page-subtitle', 'Analisis finansial internal, status proyek, dan metriks paket e-commerce')

@section('header-actions')
    <a href="{{ route('admin.pricing.index') }}" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/20 flex items-center gap-2 transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Kelola Harga (Pricing)</span>
    </a>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Unpriced Alert Notice -->
    @if ($unpricedFeaturesCount > 0)
        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg shadow-amber-500/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Perhatian: Terdapat {{ $unpricedFeaturesCount }} varian harga fitur yang belum diatur</h2>
                    <p class="text-xs text-amber-300/80">Sesuai standar, harga modal internal (cost price) tidak dikarang secara otomatis. Silakan lengkapi harga internal & jual.</p>
                </div>
            </div>
            <a href="{{ route('admin.pricing.index', ['filter' => 'unpriced']) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-xl whitespace-nowrap transition-colors shadow">
                Lengkapi Harga Sekarang &rarr;
            </a>
        </div>
    @endif

    <!-- Financial Metric Cards (Cost, Selling, Profit, Margin) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Revenue / Selling Price -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800/80 shadow-xl relative overflow-hidden group hover:border-indigo-500/40 transition-all">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Nilai Penjualan</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-white tracking-tight">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <p class="text-xs text-indigo-400 mt-2 flex items-center gap-1">
                <span>Selling Price (User Facing)</span>
            </p>
        </div>

        <!-- Total Internal Cost -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800/80 shadow-xl relative overflow-hidden group hover:border-amber-500/40 transition-all">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Biaya Internal (Cost)</span>
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-300 tracking-tight">
                Rp {{ number_format($totalCost, 0, ',', '.') }}
            </div>
            <p class="text-xs text-amber-400/80 mt-2 flex items-center gap-1 font-mono text-[11px]">
                <span>Cost Price (Admin Secret)</span>
            </p>
        </div>

        <!-- Total Profit -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800/80 shadow-xl relative overflow-hidden group hover:border-emerald-500/40 transition-all">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Keuntungan (Profit)</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-400 tracking-tight">
                Rp {{ number_format($totalProfit, 0, ',', '.') }}
            </div>
            <p class="text-xs text-emerald-400 mt-2 flex items-center gap-1">
                <span>Revenue &minus; Cost</span>
            </p>
        </div>

        <!-- Average Margin -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800/80 shadow-xl relative overflow-hidden group hover:border-purple-500/40 transition-all">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-Rata Margin Profit</span>
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center border border-purple-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-purple-300 tracking-tight">
                {{ number_format($averageMargin, 1, ',', '.') }}%
            </div>
            <div class="w-full bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-full rounded-full" style="width: {{ min(100, max(0, $averageMargin)) }}%"></div>
            </div>
        </div>
    </div>

    <!-- System Count Summary Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.packages.index') }}" class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 flex items-center justify-between group transition-colors">
            <div>
                <p class="text-xs text-slate-400">Total Paket</p>
                <p class="text-xl font-bold text-white mt-1">{{ $totalPackages }} <span class="text-xs font-normal text-slate-400">Paket</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-sm font-bold">📦</div>
        </a>

        <a href="{{ route('admin.features.index') }}" class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 flex items-center justify-between group transition-colors">
            <div>
                <p class="text-xs text-slate-400">Master Fitur</p>
                <p class="text-xl font-bold text-white mt-1">{{ $totalFeatures }} <span class="text-xs font-normal text-slate-400">Fitur</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center text-sm font-bold">⚙️</div>
        </a>

        <a href="{{ route('admin.addons.index') }}" class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 flex items-center justify-between group transition-colors">
            <div>
                <p class="text-xs text-slate-400">Add-ons & Custom</p>
                <p class="text-xl font-bold text-white mt-1">{{ $totalAddons }} <span class="text-xs font-normal text-slate-400">Add-ons</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center text-sm font-bold">🧩</div>
        </a>

        <a href="{{ route('admin.projects.index') }}" class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 flex items-center justify-between group transition-colors">
            <div>
                <p class="text-xs text-slate-400">Total Proyek</p>
                <p class="text-xl font-bold text-white mt-1">{{ $totalProjects }} <span class="text-xs font-normal text-slate-400">Proyek</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center text-sm font-bold">📑</div>
        </a>
    </div>

    <!-- Main Two Column Grid: Packages & Recent Projects -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Packages Overview (Left Column) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-white">Struktur Paket Sewa</h2>
                    <a href="{{ route('admin.packages.index') }}" class="text-xs text-indigo-400 hover:underline">Kelola &rarr;</a>
                </div>

                <div class="space-y-3">
                    @foreach ($packages as $pkg)
                        <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/60 space-y-2 hover:border-indigo-500/40 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-white">{{ $pkg->name }}</span>
                                    @if ($pkg->is_featured)
                                        <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase rounded bg-indigo-500 text-white">Popular</span>
                                    @endif
                                </div>
                                <span class="text-xs font-mono font-semibold text-emerald-400">{{ $pkg->formatted_price }}</span>
                            </div>
                            <p class="text-xs text-slate-400 line-clamp-2">{{ $pkg->description }}</p>
                            <div class="pt-2 border-t border-slate-700/50 flex items-center justify-between text-xs text-slate-400">
                                <span>Fitur Termasuk: <strong class="text-slate-200">{{ $pkg->included_features_count }}</strong></span>
                                <a href="{{ route('admin.packages.features', $pkg) }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Matriks Fitur &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-white">Kelompok Kategori</h2>
                    <a href="{{ route('admin.categories.index') }}" class="text-xs text-indigo-400 hover:underline">Semua &rarr;</a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $cat)
                        <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 text-xs text-slate-300 flex items-center gap-1.5">
                            <span>{{ $cat->icon ?? '📁' }}</span>
                            <span>{{ $cat->name }}</span>
                            <span class="px-1 py-0.2 rounded bg-slate-700 text-[10px] text-slate-400 font-mono">{{ $cat->features_count }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Projects Table with Internal Financial Metrics (Right Column) -->
        <div class="lg:col-span-2 bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-white">Daftar Proyek Terbaru (Internal Financial Snapshot)</h2>
                    <p class="text-xs text-slate-400">Menampilkan nilai jual, biaya modal (cost), laba kotor, dan margin profit</p>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="text-xs text-indigo-400 hover:underline font-semibold">Lihat Semua Proyek &rarr;</a>
            </div>

            @if ($recentProjects->isEmpty())
                <div class="p-12 text-center text-slate-500 border border-dashed border-slate-800 rounded-xl space-y-2">
                    <svg class="w-10 h-10 mx-auto text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <p class="text-sm font-medium">Belum ada proyek tersimpan.</p>
                    <p class="text-xs text-slate-600">Proyek yang disimpan user dari kalkulator akan otomatis tercatat di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-[10px] uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                            <tr>
                                <th class="p-3">Proyek & Klien</th>
                                <th class="p-3">Paket</th>
                                <th class="p-3 text-right">Selling Price</th>
                                <th class="p-3 text-right">Cost Price</th>
                                <th class="p-3 text-right">Profit</th>
                                <th class="p-3 text-center">Margin</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($recentProjects as $proj)
                                @php
                                    $margin = $proj->calculateMarginPercentage();
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="p-3 font-medium">
                                        <div class="text-white font-semibold text-sm">{{ $proj->name }}</div>
                                        <div class="text-slate-400 text-[11px]">{{ $proj->customer_name ?: 'Anonim' }} {{ $proj->customer_company ? "({$proj->customer_company})" : '' }}</div>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                            {{ $proj->package?->name ?? 'Custom' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right font-mono font-semibold text-white">
                                        Rp {{ number_format((float)$proj->total_selling_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-right font-mono text-amber-300">
                                        Rp {{ number_format((float)$proj->total_cost_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-right font-mono font-semibold text-emerald-400">
                                        Rp {{ number_format((float)$proj->total_profit, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-center">
                                        @if ($margin !== null)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $margin >= 35 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($margin >= 20 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30') }}">
                                                {{ $margin }}%
                                            </span>
                                        @else
                                            <span class="text-slate-600">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-slate-700 text-slate-300',
                                                'pending' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
                                                'approved' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
                                                'completed' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
                                                'rejected' => 'bg-rose-500/20 text-rose-300 border border-rose-500/30',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $statusColors[$proj->status] ?? 'bg-slate-700 text-slate-300' }}">
                                            {{ $proj->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('admin.projects.show', $proj) }}" class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-lg inline-block transition-colors" title="Lihat Detail Snapshot">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
