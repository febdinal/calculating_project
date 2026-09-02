@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-white">Formulir Kategori Baru</h3>
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Kembali ke Daftar Kategori
        </a>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Website & Tampilan" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Ikon Emoji</label>
                <input type="text" name="icon" value="{{ old('icon', '🏷️') }}" placeholder="🌐" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Slug URL (Opsional)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Otomatis digenerate jika kosong (contoh: website-tampilan)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi Kategori</label>
            <textarea name="description" rows="3" placeholder="Deskripsi singkat kategori ini..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 1) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30">
                Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
