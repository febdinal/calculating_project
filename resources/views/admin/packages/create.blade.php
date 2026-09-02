@extends('layouts.admin')

@section('title', 'Tambah Paket Baru')
@section('page-title', 'Tambah Paket Baru')
@section('page-subtitle', 'Buat paket sewa baru dan konfigurasikan harga serta target pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Paket <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Enterprise Plus" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Slug (URL Identitas)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Otomatis jika dikosongkan"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Paket</label>
                <textarea id="description" name="description" rows="3" placeholder="Penjelasan scope dan keuntungan paket ini..."
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="price_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Tipe Harga <span class="text-rose-400">*</span></label>
                    <select id="price_type" name="price_type" required onchange="togglePriceInput(this.value)"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="fixed" {{ old('price_type') === 'fixed' ? 'selected' : '' }}>Tetap (Fixed Price)</option>
                        <option value="custom" {{ old('price_type') === 'custom' ? 'selected' : '' }}>Kustom / Hubungi Kami</option>
                    </select>
                </div>

                <div id="price-container">
                    <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Harga Paket (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="8000000" min="0" step="10000"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="billing_period" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Periode Tagihan <span class="text-rose-400">*</span></label>
                    <select id="billing_period" name="billing_period" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="annual" {{ old('billing_period', 'annual') === 'annual' ? 'selected' : '' }}>Tahunan (Annual)</option>
                        <option value="monthly" {{ old('billing_period') === 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="target_user" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Target Pengguna / Segmentasi</label>
                <input type="text" id="target_user" name="target_user" value="{{ old('target_user') }}" placeholder="Contoh: Toko Online Aktif, UKM Menengah"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                <div>
                    <label for="sort_order" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Urutan Tampil (Sort Order)</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 1) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status Publikasi <span class="text-rose-400">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-semibold text-slate-300">Tandai sebagai Featured / Populer</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('admin.packages.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                    Simpan & Lanjut ke Matriks Fitur &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePriceInput(type) {
        const container = document.getElementById('price-container');
        if (type === 'custom') {
            container.style.opacity = '0.5';
            document.getElementById('price').disabled = true;
            document.getElementById('price').value = '';
        } else {
            container.style.opacity = '1';
            document.getElementById('price').disabled = false;
        }
    }
</script>
@endsection
