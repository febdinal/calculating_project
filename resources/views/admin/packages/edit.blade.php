@extends('layouts.admin')

@section('title', 'Edit Paket ' . $package->name)
@section('page-title', 'Edit Paket ' . $package->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-white">Edit Informasi Paket</h3>
        <a href="{{ route('admin.packages.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Kembali ke Daftar Paket
        </a>
    </div>

    <form method="POST" action="{{ route('admin.packages.update', $package) }}" class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Paket *</label>
            <input type="text" name="name" value="{{ old('name', $package->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Slug URL *</label>
            <input type="text" name="slug" value="{{ old('slug', $package->slug) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi Singkat</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">{{ old('description', $package->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Harga Paket *</label>
                <input type="text" inputmode="numeric" name="price" value="{{ old('price') ? (is_numeric(old('price')) ? 'Rp ' . number_format(old('price'), 0, ',', '.') : old('price')) : 'Rp ' . number_format($package->price, 0, ',', '.') }}" required placeholder="Rp 8.000.000" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Periode Biaya *</label>
                <input type="text" name="period" value="{{ old('period', $package->period) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                    <option value="active" {{ old('status', $package->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $package->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
            <a href="{{ route('admin.packages.features', $package) }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1.5">
                <span>Atur Checklist Fitur Bawaan Paket &rarr;</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
