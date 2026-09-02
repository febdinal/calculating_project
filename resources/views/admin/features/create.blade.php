@extends('layouts.admin')

@section('title', 'Tambah Fitur')
@section('page-title', 'Tambah Fitur / Sub Fitur Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between font-mono">
        <h3 class="text-sm font-bold text-[var(--tally-ink-0)]">Formulir Fitur Baru</h3>
        <a href="{{ route('admin.features.index') }}" class="text-xs text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
            &larr; Kembali ke Daftar Fitur
        </a>
    </div>

    <form method="POST" action="{{ route('admin.features.store') }}" class="p-6 rounded-2xl tally-card space-y-5">
        @csrf

        <!-- Parent Feature Selector (Optional) -->
        <div class="p-4 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)]">
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Tipe Fitur / Parent Feature</label>
            <select name="parent_id" id="parent_id" onchange="toggleFeatureType()" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-sans shadow-xs">
                <option value="">-- Fitur Utama (Total Harga dihitung dari Sub-Fitur) --</option>
                @foreach ($parentFeatures as $parent)
                    <option value="{{ $parent->id }}" {{ (old('parent_id', $selectedParentId) == $parent->id) ? 'selected' : '' }}>
                        Sub Fitur dari: {{ $parent->name }} ({{ $parent->category?->name ?? 'Lainnya' }})
                    </option>
                @endforeach
            </select>
            <p class="text-[11px] text-[var(--tally-ink-2)] mt-1.5 font-mono">Pilih parent jika Anda ingin menambahkan sub-fitur dengan harga sendiri.</p>
        </div>

        <!-- Category & Icon -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Kategori Fitur *</label>
                <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-sans shadow-xs">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Ikon Emoji</label>
                <input type="text" name="icon" value="{{ old('icon', '⚡') }}" placeholder="🌐" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>
        </div>

        <!-- Name & Slug -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Nama Fitur *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Katalog Produk" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Slug URL (Opsional)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="katalog-produk" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>
        </div>

        <!-- Price -->
        <div id="price_container">
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono" id="price_label">Harga Fitur</label>
            <input type="text" inputmode="numeric" name="price" id="price_input" value="{{ old('price') ? (is_numeric(old('price')) ? 'Rp ' . number_format(old('price'), 0, ',', '.') : old('price')) : 'Rp 0' }}" placeholder="Rp 1.500.000" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            <p class="text-[11px] text-[var(--tally-ink-2)] mt-1 font-mono" id="price_help">Jika menambahkan sub-fitur di bawah, harga fitur utama akan otomatis dihitung dari total harga sub-fitur.</p>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Deskripsi Fitur</label>
            <textarea name="description" rows="3" placeholder="Penjelasan singkat fitur ini..." class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">{{ old('description') }}</textarea>
        </div>

        <!-- Sub-features Dynamic Management (For Main Features) -->
        <div id="sub_features_container" class="p-4 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-[var(--tally-ink-0)] font-mono">Rincian Sub Fitur & Harga Masing-masing</h4>
                    <p class="text-[11px] text-[var(--tally-ink-2)]">Setiap sub-fitur memiliki harga sendiri. Totalnya akan menjadi harga fitur utama.</p>
                </div>
                <button type="button" onclick="addSubFeatureRow()" class="tally-btn px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-semibold font-mono">
                    + Tambah Sub Fitur
                </button>
            </div>

            <div id="sub_features_list" class="space-y-2">
                <!-- Dynamic rows -->
            </div>
        </div>

        <!-- Sort Order & Status -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 1) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-[var(--tally-card-border)] flex items-center justify-end gap-3 font-mono">
            <a href="{{ route('admin.features.index') }}" class="tally-btn px-4 py-2 rounded-xl text-xs font-semibold text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
                Batal
            </a>
            <button type="submit" class="tally-btn px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/20">
                Simpan Fitur
            </button>
        </div>
    </form>
</div>

<script>
    let subIndex = 0;
    function addSubFeatureRow(name = '', price = '') {
        const list = document.getElementById('sub_features_list');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 sub-row';
        const formattedPrice = price ? (isNumeric(price) ? formatRupiahJs(price) : price) : 'Rp 0';
        row.innerHTML = `
            <input type="text" name="sub_features[${subIndex}][name]" value="${name}" placeholder="Nama Sub Fitur (contoh: Daftar Produk)" required class="flex-1 px-3 py-1.5 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            <div class="w-44">
                <input type="text" inputmode="numeric" name="sub_features[${subIndex}][price]" value="${formattedPrice}" placeholder="Rp 0" oninput="maskRupiah(this); calculateTotalFromSub();" class="input-rupiah sub-price w-full px-2.5 py-1.5 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            </div>
            <input type="number" name="sub_features[${subIndex}][sort_order]" value="${subIndex + 1}" placeholder="Urutan" class="w-16 px-2 py-1.5 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            <button type="button" onclick="this.closest('.sub-row').remove(); calculateTotalFromSub();" class="tally-btn px-2 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900 text-rose-600 dark:text-rose-300 text-xs border border-rose-500/20 font-bold">
                ×
            </button>
        `;
        list.appendChild(row);
        subIndex++;
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
        const priceHelp = document.getElementById('price_help');
        const isSub = parentSelect.value !== '';

        if (isSub) {
            subContainer.style.display = 'none';
            priceLabel.innerText = 'Harga Sub Fitur *';
            priceHelp.innerText = 'Harga untuk sub-fitur ini yang akan diakumulasikan ke fitur utama.';
        } else {
            subContainer.style.display = 'block';
            priceLabel.innerText = 'Total Harga Fitur';
            priceHelp.innerText = 'Jika menambahkan sub-fitur di bawah, total harga akan otomatis dihitung dari sub-fitur.';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleFeatureType);
</script>
@endsection
