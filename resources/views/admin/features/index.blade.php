@extends('layouts.admin')

@section('title', 'Kelola Fitur & Sub Fitur')
@section('page-title', 'Manajemen Fitur & Sub Fitur')

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-sans">Kelola master fitur website, harga fitur, dan rincian sub-fitur.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.features.create') }}" class="tally-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold flex items-center gap-2 shadow-sm shadow-indigo-600/30 font-mono transition-all hover:scale-[1.02] active:scale-[0.98]">
                <span>+ Tambah Fitur Baru</span>
            </a>
        </div>
    </div>

    <!-- Financial & Margin Summary Cards (Internal) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl tally-card bg-white dark:bg-[#0d121f] border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 font-mono">Total Nilai Jual Master Fitur</p>
                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-1 font-mono">
                    Rp {{ number_format($totalSellingPrice, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-800/60 flex items-center justify-center text-lg">
                🏷️
            </div>
        </div>

        <div class="p-4 rounded-2xl tally-card bg-white dark:bg-[#0d121f] border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <div class="flex items-center gap-1.5">
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 font-mono">Total Modal Real (Internal)</p>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 font-mono font-bold">INTERNAL</span>
                </div>
                <p class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-1 font-mono">
                    Rp {{ number_format($totalRealPrice, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800/60 flex items-center justify-center text-lg">
                🔒
            </div>
        </div>

        <div class="p-4 rounded-2xl tally-card bg-white dark:bg-[#0d121f] border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 font-mono">Total Margin Profit (Rata-rata)</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-xl font-bold font-mono {{ $totalMarginNominal >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $totalMarginNominal >= 0 ? '+' : '' }}Rp {{ number_format($totalMarginNominal, 0, ',', '.') }}
                    </p>
                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-full {{ $totalMarginPercent >= 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200 dark:border-rose-800/60' }}">
                        {{ $totalMarginPercent }}%
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/60 flex items-center justify-center text-lg">
                📈
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl tally-card flex flex-wrap items-center gap-3 bg-white dark:bg-[#0d121f] border border-slate-200/80 dark:border-slate-800 shadow-xs">
        <form method="GET" action="{{ route('admin.features.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau deskripsi fitur..." class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-sans transition-all">
            </div>

            <div class="w-48">
                <select name="category" onchange="this.form.submit()" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 font-sans transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="tally-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold font-mono transition-all">
                Filter
            </button>

            @if (request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.features.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white font-mono transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Features Table Card -->
    <div class="rounded-2xl tally-card bg-white dark:bg-[#0d121f] border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50/90 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-800 font-mono text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Ikon & Nama Fitur</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga Jual (Klien)</th>
                        <th class="py-3.5 px-4">Harga Real (Internal)</th>
                        <th class="py-3.5 px-4">Margin Profit</th>
                        <th class="py-3.5 px-4 text-center">Sub Fitur</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($features as $feature)
                        @php
                            $isProfit = $feature->margin >= 0;
                            $marginClass = $isProfit ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
                            $marginBadgeClass = $isProfit
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/60'
                                : 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border-rose-200 dark:border-rose-800/60';
                        @endphp
                        <tr class="hover:bg-indigo-50/20 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 dark:text-slate-500 font-medium">{{ $feature->sort_order }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-start gap-3">
                                    <span class="text-lg mt-0.5 shrink-0">{{ $feature->icon ?? '⚡' }}</span>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-slate-100 text-xs">{{ $feature->name }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 max-w-sm mt-0.5 line-clamp-1">{{ $feature->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                    {{ $feature->category?->name ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-indigo-600 dark:text-indigo-400 font-mono text-xs whitespace-nowrap">
                                Rp {{ number_format($feature->calculated_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-amber-600 dark:text-amber-400 font-mono text-xs whitespace-nowrap">
                                Rp {{ number_format($feature->calculated_real_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold {{ $marginClass }} text-xs">
                                        {{ $isProfit ? '+' : '' }}Rp {{ number_format($feature->margin, 0, ',', '.') }}
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $marginBadgeClass }}">
                                        {{ $feature->margin_percentage }}%
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($feature->sub_features_count > 0)
                                    <button type="button" onclick="toggleSubRows('sub-detail-{{ $feature->id }}', this)" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:text-indigo-300 dark:hover:bg-indigo-900 border border-indigo-200 dark:border-indigo-800/60 shadow-xs transition-colors">
                                        <span>{{ $feature->sub_features_count }} Sub Fitur</span>
                                        <svg class="w-3.5 h-3.5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600 text-xs font-mono">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($feature->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold font-mono bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 shadow-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold font-mono bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        inactive
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2 font-mono">
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="tally-btn inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:border-slate-700 transition-all hover:scale-105 active:scale-95">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.features.destroy', $feature) }}" onsubmit="return confirm('Hapus fitur {{ $feature->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tally-btn inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 shadow-xs dark:bg-rose-950/40 dark:hover:bg-rose-900/60 dark:text-rose-300 dark:border-rose-900/60 transition-all hover:scale-105 active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Collapsible Sub-Features Detailed Breakdown Row -->
                        @if ($feature->subFeatures->isNotEmpty())
                            <tr id="sub-detail-{{ $feature->id }}" class="hidden bg-slate-50/70 dark:bg-slate-900/50">
                                <td colspan="9" class="p-4 pl-12">
                                    <div class="p-4 rounded-xl bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 shadow-xs space-y-3">
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-mono">
                                            <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                                <span>📑</span>
                                                <span>Rincian Sub Fitur: {{ $feature->name }}</span>
                                            </span>
                                            <span class="text-[11px] text-slate-400">Total {{ $feature->subFeatures->count() }} sub-fitur</span>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-xs">
                                                <thead class="text-slate-400 font-mono text-[10px] uppercase border-b border-slate-100 dark:border-slate-800">
                                                    <tr>
                                                        <th class="py-2 px-3">Nama Sub Fitur</th>
                                                        <th class="py-2 px-3">Harga Real (Modal)</th>
                                                        <th class="py-2 px-3">Harga Jual (Klien)</th>
                                                        <th class="py-2 px-3">Margin Nominal</th>
                                                        <th class="py-2 px-3">Margin %</th>
                                                        <th class="py-2 px-3 text-center">Urutan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-mono text-[11px]">
                                                    @foreach ($feature->subFeatures as $sub)
                                                        @php
                                                            $subProfit = $sub->margin >= 0;
                                                        @endphp
                                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                                            <td class="py-2 px-3 font-sans font-medium text-slate-800 dark:text-slate-200">
                                                                {{ $sub->name }}
                                                            </td>
                                                            <td class="py-2 px-3 text-amber-600 dark:text-amber-400 font-bold">
                                                                Rp {{ number_format($sub->real_price, 0, ',', '.') }}
                                                            </td>
                                                            <td class="py-2 px-3 text-indigo-600 dark:text-indigo-400 font-bold">
                                                                Rp {{ number_format($sub->price, 0, ',', '.') }}
                                                            </td>
                                                            <td class="py-2 px-3 font-bold {{ $subProfit ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                                                {{ $subProfit ? '+' : '' }}Rp {{ number_format($sub->margin, 0, ',', '.') }}
                                                            </td>
                                                            <td class="py-2 px-3">
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $subProfit ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border-emerald-200' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border-rose-200' }}">
                                                                    {{ $sub->margin_percentage }}%
                                                                </span>
                                                            </td>
                                                            <td class="py-2 px-3 text-center text-slate-400">
                                                                {{ $sub->sort_order }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-500 dark:text-slate-400 font-mono">
                                Belum ada fitur yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($features->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                {{ $features->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function toggleSubRows(targetId, btn) {
        const row = document.getElementById(targetId);
        if (!row) return;

        const isHidden = row.classList.contains('hidden');
        const icon = btn.querySelector('svg');

        if (isHidden) {
            row.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
        } else {
            row.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        }
    }
</script>
@endsection
