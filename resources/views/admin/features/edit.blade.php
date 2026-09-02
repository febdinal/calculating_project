@extends('layouts.admin')

@section('title', 'Edit Fitur: ' . $feature->name)
@section('page-title', 'Edit Fitur: ' . $feature->name)
@section('page-subtitle', 'Perbarui detail informasi, kategori, dan dependensi fitur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('admin.features.update', $feature) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Kategori <span class="text-rose-400">*</span></label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $feature->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Ikon / Emoji</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $feature->icon) }}"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-center text-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Fitur <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $feature->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Slug (URL Identitas) <span class="text-rose-400">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $feature->slug) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi & Cakupan Scope</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $feature->description) }}</textarea>
            </div>

            <!-- Dependencies Selection -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Dependensi / Fitur Prasyarat</label>
                <p class="text-xs text-slate-400 mb-3">Fitur yang harus dipilih sebelum fitur ini bisa diaktifkan.</p>
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/60 max-h-48 overflow-y-auto space-y-2">
                    @foreach ($otherFeatures as $other)
                        <label class="flex items-center gap-2.5 hover:bg-slate-700/30 p-1.5 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" name="dependencies[]" value="{{ $other->id }}" {{ in_array($other->id, old('dependencies', $currentDependencies)) ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-slate-800 border-slate-600 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs text-slate-200">{{ $other->icon }} {{ $other->name }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">({{ $other->category?->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                <div>
                    <label for="sort_order" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $feature->sort_order) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status <span class="text-rose-400">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="active" {{ old('status', $feature->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $feature->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_infrastructure" value="1" {{ old('is_infrastructure', $feature->is_infrastructure) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-semibold text-slate-300">Termasuk Infrastruktur Dasar</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-between">
                <a href="{{ route('admin.pricing.feature', $feature) }}" class="px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 font-semibold rounded-xl text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Kelola Harga Fitur Ini &rarr;</span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.features.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                        Perbarui Fitur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
