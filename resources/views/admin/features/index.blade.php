@extends('layouts.admin')

@section('title', 'Kelola Fitur & Sub Fitur')
@section('page-title', 'Manajemen Fitur & Sub Fitur')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-slate-400">Kelola master fitur website, harga fitur, dan rincian sub-fitur.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.features.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-indigo-600/30">
                <span>+ Tambah Fitur Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 flex flex-wrap items-center gap-3">
        <form method="GET" action="{{ route('admin.features.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau deskripsi fitur..." class="w-full px-3.5 py-2 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div class="w-48">
                <select name="category" onchange="this.form.submit()" class="w-full px-3.5 py-2 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold">
                Filter
            </button>

            @if (request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.features.index') }}" class="px-3 py-2 text-xs text-slate-400 hover:text-white">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Features Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-800/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Urutan</th>
                        <th class="py-3.5 px-4">Ikon & Nama Fitur</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga Fitur</th>
                        <th class="py-3.5 px-4">Sub Fitur</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse ($features as $feature)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-slate-400">{{ $feature->sort_order }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-start gap-3">
                                    <span class="text-base mt-0.5">{{ $feature->icon ?? '⚡' }}</span>
                                    <div>
                                        <p class="font-bold text-white">{{ $feature->name }}</p>
                                        <p class="text-[11px] text-slate-400 max-w-sm">{{ Str::limit($feature->description, 70) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 font-medium">
                                    {{ $feature->category?->name ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-indigo-400">
                                Rp {{ number_format($feature->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($feature->sub_features_count > 0)
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-300 border border-purple-500/20 font-semibold text-xs">
                                        {{ $feature->sub_features_count }} Sub Fitur
                                    </span>
                                @else
                                    <span class="text-slate-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $feature->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-700 text-slate-400' }}">
                                    {{ $feature->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.features.destroy', $feature) }}" onsubmit="return confirm('Hapus fitur {{ $feature->name }} beserta seluruh sub-fiturnya?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 text-[11px] font-medium border border-rose-500/20">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada fitur yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($features->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/40">
                {{ $features->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
