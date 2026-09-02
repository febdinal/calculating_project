@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Manajemen Kategori Fitur')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-slate-500 dark:text-slate-400 font-sans">Kelola kategori pengelompokan fitur website.</p>
        <a href="{{ route('admin.categories.create') }}" class="tally-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold flex items-center gap-2 shadow-sm shadow-indigo-600/30 font-mono transition-all hover:scale-[1.02] active:scale-[0.98]">
            <span>+ Tambah Kategori</span>
        </a>
    </div>

    <!-- Categories Table Card -->
    <div class="rounded-2xl tally-card bg-white dark:bg-[#0d121f] border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50/90 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 font-bold border-b border-slate-200 dark:border-slate-800 font-mono text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Ikon & Nama Kategori</th>
                        <th class="py-3.5 px-4">Slug</th>
                        <th class="py-3.5 px-4">Jumlah Fitur</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 dark:text-slate-500 font-medium">{{ $category->sort_order }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg shrink-0">{{ $category->icon ?? '🏷️' }}</span>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-slate-100 text-xs">{{ $category->name }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ $category->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-slate-400 text-xs">
                                {{ $category->slug }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700 shadow-xs">
                                    {{ $category->main_features_count }} Fitur Utama
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($category->status === 'active')
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
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="tally-btn inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-xs dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:border-slate-700 transition-all hover:scale-105 active:scale-95">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?');" class="inline">
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
                            <td colspan="6" class="py-8 text-center text-slate-500 dark:text-slate-400 font-mono">
                                Belum ada kategori. Silakan tambahkan kategori baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
