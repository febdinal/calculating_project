@extends('layouts.admin')

@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-white">Formulir Paket Baru</h3>
        <a href="{{ route('admin.packages.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Kembali ke Daftar Paket
        </a>
    </div>

    <form method="POST" action="{{ route('admin.packages.store') }}" class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Paket *</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Medium" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Slug URL (Opsional)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Otomatis digenerate jika kosong (contoh: medium)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi Singkat</label>
            <textarea name="description" rows="3" placeholder="Deskripsi peruntukan paket..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Harga Paket *</label>
                <input type="text" inputmode="numeric" name="price" value="{{ old('price') ? (is_numeric(old('price')) ? 'Rp ' . number_format(old('price'), 0, ',', '.') : old('price')) : 'Rp 8.000.000' }}" required placeholder="Rp 8.000.000" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Periode Biaya *</label>
                <input type="text" name="period" value="{{ old('period', 'tahun') }}" required placeholder="tahun / bulan" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>
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
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30">
                Simpan & Lanjut ke Fitur Paket &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
