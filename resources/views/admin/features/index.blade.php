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
                        <th class="py-3.5 px-4">Harga Fitur</th>
                        <th class="py-3.5 px-4">Sub Fitur</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($features as $feature)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-slate-800/40 transition-colors">
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
                            <td class="py-3.5 px-4 font-bold text-indigo-600 dark:text-indigo-400 font-mono text-xs">
                                Rp {{ number_format($feature->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($feature->sub_features_count > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 shadow-xs">
                                        {{ $feature->sub_features_count }} Sub Fitur
                                    </span>
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
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 dark:text-slate-400 font-mono">
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
@endsection
