@extends('layouts.admin')

@section('title', 'Edit Add-on: ' . $addon->name)
@section('page-title', 'Edit Add-on: ' . $addon->name)
@section('page-subtitle', 'Perbarui detail layanan, rentang harga, dan modal biaya internal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('admin.addons.update', $addon) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Add-on <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $addon->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Ikon / Emoji</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $addon->icon) }}"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-center text-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Kategori Add-on</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $addon->category) }}"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $addon->slug) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi Layanan Add-on</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $addon->description) }}</textarea>
            </div>

            <!-- Price Type and Inputs -->
            <div class="p-5 rounded-2xl bg-slate-800/50 border border-slate-700/60 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-white">Struktur Harga & Biaya Internal</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="price_type" class="block text-[11px] font-semibold text-slate-300 mb-1.5">Tipe Harga <span class="text-rose-400">*</span></label>
                        <select id="price_type" name="price_type" required onchange="toggleAddonPriceType(this.value)"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <option value="range" {{ old('price_type', $addon->price_type) === 'range' ? 'selected' : '' }}>Rentang (Range Min – Max)</option>
                            <option value="fixed" {{ old('price_type', $addon->price_type) === 'fixed' ? 'selected' : '' }}>Harga Tetap (Fixed)</option>
                            <option value="custom" {{ old('price_type', $addon->price_type) === 'custom' ? 'selected' : '' }}>Kustom</option>
                        </select>
                    </div>

                    <div id="min-price-box">
                        <label for="price_min" class="block text-[11px] font-semibold text-slate-300 mb-1.5">Harga Min (Rp)</label>
                        <input type="number" step="100000" id="price_min" name="price_min" value="{{ old('price_min', (int)$addon->price_min) }}"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div id="max-price-box">
                        <label for="price_max" class="block text-[11px] font-semibold text-slate-300 mb-1.5">Harga Max (Rp)</label>
                        <input type="number" step="100000" id="price_max" name="price_max" value="{{ old('price_max', (int)$addon->price_max) }}"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-700/50">
                    <div id="selling-price-box">
                        <label for="selling_price" class="block text-[11px] font-semibold text-slate-300 mb-1.5">Selling Price Pasti (Opsional)</label>
                        <input type="number" step="100000" id="selling_price" name="selling_price" value="{{ old('selling_price', (int)$addon->selling_price) }}"
                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="cost_price" class="block text-[11px] font-semibold text-amber-400 mb-1.5">Cost Modal Internal (Rahasia)</label>
                        <input type="number" step="100000" id="cost_price" name="cost_price" value="{{ old('cost_price', (int)$addon->cost_price) }}"
                            class="w-full px-3 py-2 bg-slate-800 border border-amber-500/30 rounded-xl text-amber-300 text-xs font-mono focus:outline-none focus:ring-1 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $addon->sort_order) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status <span class="text-rose-400">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="active" {{ old('status', $addon->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $addon->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('admin.addons.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                    Perbarui Add-on
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Zone -->
    <div class="bg-rose-500/5 border border-rose-500/20 rounded-2xl p-6 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-rose-300">Hapus Add-on</h3>
            <p class="text-xs text-rose-400/80">Add-on ini akan dihapus. Riwayat proyek tersimpan tidak terpengaruh.</p>
        </div>
        <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus add-on ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/40 text-xs font-semibold rounded-xl transition-all">
                Hapus Add-on
            </button>
        </form>
    </div>
</div>

<script>
    function toggleAddonPriceType(type) {
        const minBox = document.getElementById('min-price-box');
        const maxBox = document.getElementById('max-price-box');
        const sellBox = document.getElementById('selling-price-box');

        if (type === 'fixed') {
            minBox.style.display = 'none';
            maxBox.style.display = 'none';
            sellBox.style.display = 'block';
        } else if (type === 'range') {
            minBox.style.display = 'block';
            maxBox.style.display = 'block';
            sellBox.style.display = 'block';
        } else {
            minBox.style.display = 'none';
            maxBox.style.display = 'none';
            sellBox.style.display = 'none';
        }
    }
    toggleAddonPriceType('{{ old('price_type', $addon->price_type) }}');
</script>
@endsection
