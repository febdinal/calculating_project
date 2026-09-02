@extends('layouts.admin')

@section('title', 'Master Fitur E-Commerce')
@section('page-title', 'Master Fitur E-Commerce')
@section('page-subtitle', 'Daftar lengkap fitur sistem, dependensi, dan status infrastruktur dasar')

@section('header-actions')
    <a href="{{ route('admin.features.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/20 flex items-center gap-2 transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Fitur Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter & Search Bar -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.features.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <!-- Search -->
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama fitur, slug, atau deskripsi..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()"
                class="w-full sm:w-auto px-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <!-- Type Filter -->
            <select name="type" onchange="this.form.submit()"
                class="w-full sm:w-auto px-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Tipe</option>
                <option value="functional" {{ request('type') === 'functional' ? 'selected' : '' }}>Fitur Fungsional</option>
                <option value="infrastructure" {{ request('type') === 'infrastructure' ? 'selected' : '' }}>Infrastruktur Dasar</option>
            </select>

            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if (request()->hasAny(['search', 'category', 'type']))
                <a href="{{ route('admin.features.index') }}" class="text-xs text-rose-400 hover:underline whitespace-nowrap">Reset</a>
            @endif
        </form>
    </div>

    <!-- Features Table -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                    <tr>
                        <th class="p-4">Fitur & Ikon</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Tipe & Dependensi</th>
                        <th class="p-4 text-center">Varian Harga</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($features as $feature)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-base shrink-0">
                                        {{ $feature->icon ?? '⚙️' }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $feature->name }}</div>
                                        <div class="text-xs text-slate-400 max-w-sm line-clamp-1">{{ $feature->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 border border-slate-700 text-slate-300 flex items-center gap-1.5 w-max">
                                    <span>{{ $feature->category?->icon ?? '📁' }}</span>
                                    <span>{{ $feature->category?->name ?? 'Uncategorized' }}</span>
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    @if ($feature->is_infrastructure)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                            Infrastruktur (Included)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-400">
                                            Fungsional
                                        </span>
                                    @endif

                                    @if ($feature->requiredFeatures->isNotEmpty())
                                        <div class="text-[11px] text-amber-400/90 flex items-center gap-1">
                                            <span>Syarat:</span>
                                            <span class="font-semibold">{{ $feature->requiredFeatures->pluck('name')->join(', ') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.pricing.feature', $feature) }}" class="px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 transition-colors inline-flex items-center gap-1">
                                    <span>{{ $feature->prices_count }} Varian</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $feature->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                    {{ $feature->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.pricing.feature', $feature) }}" class="p-1.5 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 rounded-lg transition-colors" title="Atur Harga Internal & Jual">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-lg transition-colors" title="Edit Fitur">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.features.destroy', $feature) }}" method="POST" onsubmit="return confirm('Hapus fitur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors" title="Hapus Fitur">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                Tidak ada fitur yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($features->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $features->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
