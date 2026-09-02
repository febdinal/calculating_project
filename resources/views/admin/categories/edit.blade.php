@extends('layouts.admin')

@section('title', 'Edit Kategori: ' . $category->name)
@section('page-title', 'Edit Kategori: ' . $category->name)
@section('page-subtitle', 'Perbarui informasi kelompok kategori fitur')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Kategori <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Slug (URL Identitas) <span class="text-rose-400">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Singkat</label>
                <textarea id="description" name="description" rows="2"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Emoji / Ikon</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-center text-lg">
                </div>

                <div>
                    <label for="color" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Warna Identitas (HEX)</label>
                    <input type="color" id="color" name="color" value="{{ old('color', $category->color ?? '#6366f1') }}"
                        class="w-full h-10 p-1 bg-slate-800 border border-slate-700/80 rounded-xl cursor-pointer">
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status Publikasi <span class="text-rose-400">*</span></label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
