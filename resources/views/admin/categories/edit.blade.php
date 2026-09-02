@extends('layouts.admin')

@section('title', 'Edit Kategori ' . $category->name)
@section('page-title', 'Edit Kategori: ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between font-mono">
        <h3 class="text-sm font-bold text-[var(--tally-ink-0)]">Edit Informasi Kategori</h3>
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
            &larr; Kembali ke Daftar Kategori
        </a>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="p-6 rounded-2xl tally-card space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Ikon Emoji</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="🌐" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Slug URL *</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Deskripsi Kategori</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
                    <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-[var(--tally-card-border)] flex items-center justify-end gap-3 font-mono">
            <a href="{{ route('admin.categories.index') }}" class="tally-btn px-4 py-2 rounded-xl text-xs font-semibold text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
                Batal
            </a>
            <button type="submit" class="tally-btn px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/20">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
