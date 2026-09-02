@extends('layouts.admin')

@section('title', 'Edit Paket ' . $package->name)
@section('page-title', 'Edit Paket: ' . $package->name)
@section('page-subtitle', 'Perbarui konfigurasi harga, deskripsi, dan status paket sewa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('admin.packages.update', $package) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Paket <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Slug (URL Identitas) <span class="text-rose-400">*</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $package->slug) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Paket</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="price_type" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Tipe Harga <span class="text-rose-400">*</span></label>
                    <select id="price_type" name="price_type" required onchange="togglePriceInput(this.value)"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="fixed" {{ old('price_type', $package->price_type) === 'fixed' ? 'selected' : '' }}>Tetap (Fixed Price)</option>
                        <option value="custom" {{ old('price_type', $package->price_type) === 'custom' ? 'selected' : '' }}>Kustom / Hubungi Kami</option>
                    </select>
                </div>

                <div id="price-container">
                    <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Harga Paket (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', (int)$package->price) }}" min="0" step="10000"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="billing_period" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Periode Tagihan <span class="text-rose-400">*</span></label>
                    <select id="billing_period" name="billing_period" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="annual" {{ old('billing_period', $package->billing_period) === 'annual' ? 'selected' : '' }}>Tahunan (Annual)</option>
                        <option value="monthly" {{ old('billing_period', $package->billing_period) === 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="target_user" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Target Pengguna / Segmentasi</label>
                <input type="text" id="target_user" name="target_user" value="{{ old('target_user', $package->target_user) }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                <div>
                    <label for="sort_order" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Urutan Tampil (Sort Order)</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status Publikasi <span class="text-rose-400">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="active" {{ old('status', $package->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $package->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="draft" {{ old('status', $package->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-semibold text-slate-300">Tandai sebagai Featured / Populer</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-between">
                <a href="{{ route('admin.packages.features', $package) }}" class="px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 font-semibold rounded-xl text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>Buka Pengaturan Matriks Fitur &rarr;</span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.packages.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                        Perbarui Paket
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Danger Zone (Delete) -->
    <div class="bg-rose-500/5 border border-rose-500/20 rounded-2xl p-6 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-rose-300">Hapus Paket Ini</h3>
            <p class="text-xs text-rose-400/80">Paket akan dihapus dari daftar. Proyek tersimpan tidak akan terpengaruh karena menggunakan snapshot harga.</p>
        </div>
        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/40 text-xs font-semibold rounded-xl transition-all">
                Hapus Paket
            </button>
        </form>
    </div>
</div>

<script>
    function togglePriceInput(type) {
        const container = document.getElementById('price-container');
        if (type === 'custom') {
            container.style.opacity = '0.5';
            document.getElementById('price').disabled = true;
        } else {
            container.style.opacity = '1';
            document.getElementById('price').disabled = false;
        }
    }
    togglePriceInput('{{ $package->price_type }}');
</script>
@endsection
