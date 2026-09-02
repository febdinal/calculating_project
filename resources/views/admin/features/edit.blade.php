@extends('layouts.admin')

@section('title', 'Edit Fitur ' . $feature->name)
@section('page-title', 'Edit Fitur: ' . $feature->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-white">Edit Informasi Fitur</h3>
        <a href="{{ route('admin.features.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Kembali ke Daftar Fitur
        </a>
    </div>

    <form method="POST" action="{{ route('admin.features.update', $feature) }}" class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-5">
        @csrf
        @method('PUT')

        <!-- Parent Feature Selector -->
        <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60">
            <label class="block text-xs font-semibold text-slate-200 mb-1.5">Tipe Fitur / Parent Feature</label>
            <select name="parent_id" id="parent_id" onchange="toggleFeatureType()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                <option value="">-- Fitur Utama (Total Harga dihitung dari Sub-Fitur) --</option>
                @foreach ($parentFeatures as $parent)
                    <option value="{{ $parent->id }}" {{ (old('parent_id', $feature->parent_id) == $parent->id) ? 'selected' : '' }}>
                        Sub Fitur dari: {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Category & Icon -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Fitur *</label>
                <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $feature->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Ikon Emoji</label>
                <input type="text" name="icon" value="{{ old('icon', $feature->icon) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <!-- Name & Slug -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Fitur *</label>
                <input type="text" name="name" value="{{ old('name', $feature->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Slug URL *</label>
                <input type="text" name="slug" value="{{ old('slug', $feature->slug) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <!-- Price -->
        <div id="price_container">
            <label class="block text-xs font-semibold text-slate-300 mb-1.5" id="price_label">Harga Fitur</label>
            <input type="text" inputmode="numeric" name="price" id="price_input" value="{{ old('price') ? (is_numeric(old('price')) ? 'Rp ' . number_format(old('price'), 0, ',', '.') : old('price')) : 'Rp ' . number_format($feature->calculated_price, 0, ',', '.') }}" placeholder="Rp 0" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-indigo-500">
            <p class="text-[11px] text-slate-400 mt-1" id="price_help">Jika fitur memiliki sub-fitur di bawah, harga fitur utama adalah akumulasi total harga sub-fitur.</p>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi Fitur</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">{{ old('description', $feature->description) }}</textarea>
        </div>

        <!-- Sub-features Management (For Main Features) -->
        <div id="sub_features_container" class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/60 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-white">Rincian Sub Fitur & Harga Masing-masing</h4>
                    <p class="text-[11px] text-slate-400">Setiap sub-fitur memiliki harga tersendiri. Totalnya otomatis menjadi harga fitur utama.</p>
                </div>
                <button type="button" onclick="addSubFeatureRow()" class="px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-semibold">
                    + Tambah Sub Fitur
                </button>
            </div>

            <div id="sub_features_list" class="space-y-2">
                @foreach ($feature->subFeatures as $index => $sub)
                    <div class="flex items-center gap-2 sub-row">
                        <input type="hidden" name="sub_features[{{ $index }}][id]" value="{{ $sub->id }}">
                        <input type="text" name="sub_features[{{ $index }}][name]" value="{{ $sub->name }}" placeholder="Nama Sub Fitur" required class="flex-1 px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                        <div class="w-44">
                            <input type="text" inputmode="numeric" name="sub_features[{{ $index }}][price]" value="Rp {{ number_format($sub->price ?? 0, 0, ',', '.') }}" placeholder="Rp 0" oninput="maskRupiah(this); calculateTotalFromSub();" class="input-rupiah sub-price w-full px-2.5 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-indigo-500">
                        </div>
                        <input type="number" name="sub_features[{{ $index }}][sort_order]" value="{{ $sub->sort_order }}" placeholder="Urutan" class="w-16 px-2 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                        <button type="button" onclick="this.closest('.sub-row').remove(); calculateTotalFromSub();" class="px-2 py-1.5 rounded-lg bg-rose-950/50 hover:bg-rose-900 text-rose-300 text-xs border border-rose-500/30">
                            ×
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sort Order & Status -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $feature->sort_order) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
                    <option value="active" {{ old('status', $feature->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $feature->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.features.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    let nextSubIndex = {{ $feature->subFeatures->count() + 100 }};
    function addSubFeatureRow(name = '', price = '') {
        const list = document.getElementById('sub_features_list');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 sub-row';
        const formattedPrice = price ? (isNumeric(price) ? formatRupiahJs(price) : price) : 'Rp 0';
        row.innerHTML = `
            <input type="hidden" name="sub_features[${nextSubIndex}][id]" value="">
            <input type="text" name="sub_features[${nextSubIndex}][name]" value="${name}" placeholder="Nama Sub Fitur Baru" required class="flex-1 px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            <div class="w-44">
                <input type="text" inputmode="numeric" name="sub_features[${nextSubIndex}][price]" value="${formattedPrice}" placeholder="Rp 0" oninput="maskRupiah(this); calculateTotalFromSub();" class="input-rupiah sub-price w-full px-2.5 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-xs font-mono focus:outline-none focus:border-indigo-500">
            </div>
            <input type="number" name="sub_features[${nextSubIndex}][sort_order]" value="${nextSubIndex}" placeholder="Urutan" class="w-16 px-2 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500">
            <button type="button" onclick="this.closest('.sub-row').remove(); calculateTotalFromSub();" class="px-2 py-1.5 rounded-lg bg-rose-950/50 hover:bg-rose-900 text-rose-300 text-xs border border-rose-500/30">
                ×
            </button>
        `;
        list.appendChild(row);
        nextSubIndex++;
    }

    function calculateTotalFromSub() {
        const priceInputs = document.querySelectorAll('.sub-price');
        let total = 0;
        priceInputs.forEach(input => {
            const clean = input.value.replace(/[^0-9]/g, '');
            const val = parseFloat(clean) || 0;
            total += val;
        });
        if (priceInputs.length > 0) {
            document.getElementById('price_input').value = formatRupiahJs(total);
        }
    }

    function toggleFeatureType() {
        const parentSelect = document.getElementById('parent_id');
        const subContainer = document.getElementById('sub_features_container');
        const priceLabel = document.getElementById('price_label');
        const isSub = parentSelect.value !== '';

        if (isSub) {
            if (subContainer) subContainer.style.display = 'none';
            if (priceLabel) priceLabel.innerText = 'Harga Sub Fitur *';
        } else {
            if (subContainer) subContainer.style.display = 'block';
            if (priceLabel) priceLabel.innerText = 'Total Harga Fitur';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleFeatureType);
</script>
@endsection
