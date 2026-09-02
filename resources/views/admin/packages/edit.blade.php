@extends('layouts.admin')

@section('title', 'Edit Paket ' . $package->name)
@section('page-title', 'Edit Paket: ' . $package->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between font-mono">
        <h3 class="text-sm font-bold text-[var(--tally-ink-0)]">Edit Informasi Paket</h3>
        <a href="{{ route('admin.packages.index') }}" class="text-xs text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
            &larr; Kembali ke Daftar Paket
        </a>
    </div>

    <form method="POST" action="{{ route('admin.packages.update', $package) }}" class="p-6 rounded-2xl tally-card space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Nama Paket *</label>
            <input type="text" name="name" value="{{ old('name', $package->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Slug URL *</label>
            <input type="text" name="slug" value="{{ old('slug', $package->slug) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Deskripsi Singkat</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">{{ old('description', $package->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Harga Paket *</label>
                <input type="text" inputmode="numeric" name="price" value="{{ old('price') ? (is_numeric(old('price')) ? 'Rp ' . number_format(old('price'), 0, ',', '.') : old('price')) : 'Rp ' . number_format($package->price, 0, ',', '.') }}" required class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Periode Biaya *</label>
                <input type="text" name="period" value="{{ old('period', $package->period) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
                    <option value="active" {{ old('status', $package->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $package->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-[var(--tally-card-border)] flex items-center justify-between font-mono">
            <a href="{{ route('admin.packages.features', $package) }}" class="tally-btn px-4 py-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-xs font-semibold border border-indigo-500/20 flex items-center gap-1.5">
                <span>⚡</span>
                <span>Atur Fitur Bawaan Paket &rarr;</span>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.packages.index') }}" class="tally-btn px-4 py-2 rounded-xl text-xs font-semibold text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
                    Batal
                </a>
                <button type="submit" class="tally-btn px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/20">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
