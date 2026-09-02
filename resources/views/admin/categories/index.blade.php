@extends('layouts.admin')

@section('title', 'Kategori Fitur')
@section('page-title', 'Kategori Fitur (Feature Categories)')
@section('page-subtitle', 'Kelompokkan fitur e-commerce ke dalam kelompok yang rapi dan mudah dipahami pengguna')

@section('header-actions')
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/20 flex items-center gap-2 transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Kategori Baru</span>
    </a>
@endsection

@section('content')
<div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                <tr>
                    <th class="p-4 w-12 text-center">Urutan</th>
                    <th class="p-4">Kategori & Ikon</th>
                    <th class="p-4">Slug Identitas</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4 text-center">Jumlah Fitur</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @foreach ($categories as $category)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="p-4 text-center font-mono text-slate-400">
                            {{ $category->sort_order }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700/60 flex items-center justify-center text-lg shadow-sm">
                                    {{ $category->icon ?? '📁' }}
                                </div>
                                <div>
                                    <div class="font-bold text-white">{{ $category->name }}</div>
                                    @if ($category->color)
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $category->color }}"></span>
                                            <span class="text-[10px] font-mono text-slate-400">{{ $category->color }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-mono text-xs text-indigo-400">
                            {{ $category->slug }}
                        </td>
                        <td class="p-4 text-xs text-slate-400 max-w-xs">
                            {{ $category->description ?? '—' }}
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.features.index', ['category' => $category->id]) }}" class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-800 text-indigo-300 border border-slate-700 hover:border-indigo-500/50 transition-colors">
                                {{ $category->features_count }} Fitur
                            </a>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase {{ $category->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                                {{ $category->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-lg transition-colors" title="Edit Kategori">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if ($category->features_count == 0)
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors" title="Hapus Kategori">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
