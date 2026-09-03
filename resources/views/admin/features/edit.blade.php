@extends('layouts.admin')

@section('title', 'Edit Fitur ' . $feature->name)
@section('page-title', 'Edit Fitur: ' . $feature->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between font-mono">
        <h3 class="text-sm font-bold text-[var(--tally-ink-0)]">Edit Informasi Fitur</h3>
        <a href="{{ route('admin.features.index') }}" class="text-xs text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
            &larr; Kembali ke Daftar Fitur
        </a>
    </div>

    <form method="POST" action="{{ route('admin.features.update', $feature) }}" class="p-6 rounded-2xl tally-card space-y-5">
        @csrf
        @method('PUT')

        <!-- Parent Feature Selector -->
        <div class="p-4 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)]">
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Tipe Fitur / Parent Feature</label>
            <select name="parent_id" id="parent_id" onchange="toggleFeatureType()" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-sans shadow-xs">
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
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Kategori Fitur *</label>
                <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-sans shadow-xs">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $feature->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Ikon Emoji</label>
                <input type="text" name="icon" value="{{ old('icon', $feature->icon) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>
        </div>

        <!-- Name & Slug -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Nama Fitur *</label>
                <input type="text" name="name" value="{{ old('name', $feature->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Slug URL *</label>
                <input type="text" name="slug" value="{{ old('slug', $feature->slug) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>
        </div>

        <!-- Price & Real Price (Standalone / Summary) -->
        <div id="price_container" class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)]">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono" id="price_label">Harga Jual (Klien)</label>
                <input type="text" inputmode="numeric" name="price" id="price_input" value="{{ old('price') ? (is_numeric(old('price')) ? 'Rp ' . number_format(old('price'), 0, ',', '.') : old('price')) : 'Rp ' . number_format($feature->calculated_price, 0, ',', '.') }}" placeholder="Rp 0" oninput="maskRupiah(this); calculateStandaloneMargin();" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
                <p class="text-[10px] text-[var(--tally-ink-2)] mt-1 font-mono" id="price_help">Harga jual publik yang ditagihkan ke klien.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono flex items-center justify-between" id="real_price_label">
                    <span>Harga Real (Internal / HPP)</span>
                    <span class="text-[10px] text-amber-500 font-normal">🔒 Internal Only</span>
                </label>
                <input type="text" inputmode="numeric" name="real_price" id="real_price_input" value="{{ old('real_price') ? (is_numeric(old('real_price')) ? 'Rp ' . number_format(old('real_price'), 0, ',', '.') : old('real_price')) : 'Rp ' . number_format($feature->calculated_real_price, 0, ',', '.') }}" placeholder="Rp 0" oninput="maskRupiah(this); calculateStandaloneMargin();" class="input-rupiah w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-amber-500 shadow-xs">
                <p class="text-[10px] text-[var(--tally-ink-2)] mt-1 font-mono">Biaya modal/internal pengembangan fitur.</p>
            </div>

            <div class="sm:col-span-2 pt-2 border-t border-[var(--tally-card-border)] flex items-center justify-between text-xs font-mono" id="standalone_margin_bar">
                <span class="text-[var(--tally-ink-2)]">Estimasi Margin Keuntungan:</span>
                <span id="standalone_margin_badge" class="font-bold text-emerald-600 dark:text-emerald-400">Rp 0 (0%)</span>
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Deskripsi Fitur</label>
            <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">{{ old('description', $feature->description) }}</textarea>
        </div>

        <!-- Sub-features Management (For Main Features) -->
        <div id="sub_features_container" class="p-4 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h4 class="text-xs font-bold text-[var(--tally-ink-0)] font-mono flex items-center gap-1.5">
                        <span>📑</span>
                        <span>Rincian Sub Fitur & Harga Internal</span>
                    </h4>
                    <p class="text-[11px] text-[var(--tally-ink-2)] mt-0.5">Kelola Harga Real (Modal) dan Harga Jual (Klien). Margin profit dihitung otomatis.</p>
                </div>
                <button type="button" onclick="addSubFeatureRow()" class="tally-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold font-mono flex items-center gap-1.5 shadow-xs shrink-0">
                    <span>+ Tambah Sub Fitur</span>
                </button>
            </div>

            <!-- Repeater Header -->
            <div class="hidden md:grid grid-cols-12 gap-2 text-[10px] font-mono font-bold text-[var(--tally-ink-2)] uppercase px-2">
                <div class="col-span-4">Nama Sub Fitur *</div>
                <div class="col-span-3 text-amber-500">Harga Real (Internal)</div>
                <div class="col-span-3 text-indigo-500">Harga Jual (Klien)</div>
                <div class="col-span-1 text-center">Urut</div>
                <div class="col-span-1 text-right">Aksi</div>
            </div>

            <div id="sub_features_list" class="space-y-3">
                @foreach ($feature->subFeatures as $index => $sub)
                    <div class="p-3 rounded-xl bg-[var(--tally-paper-1)]/60 border border-[var(--tally-card-border)] sub-row transition-all space-y-2">
                        <input type="hidden" name="sub_features[{{ $index }}][id]" value="{{ $sub->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                            <div class="md:col-span-4">
                                <input type="text" name="sub_features[{{ $index }}][name]" value="{{ $sub->name }}" placeholder="Nama Sub Fitur" required class="w-full px-3 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
                            </div>
                            <div class="md:col-span-3">
                                <input type="text" inputmode="numeric" name="sub_features[{{ $index }}][real_price]" value="Rp {{ number_format($sub->real_price ?? 0, 0, ',', '.') }}" placeholder="Harga Real (Rp 0)" oninput="maskRupiah(this); updateRowMargin(this); calculateTotalFromSub();" class="input-rupiah sub-real-price w-full px-2.5 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-amber-500 shadow-xs">
                            </div>
                            <div class="md:col-span-3">
                                <input type="text" inputmode="numeric" name="sub_features[{{ $index }}][price]" value="Rp {{ number_format($sub->price ?? 0, 0, ',', '.') }}" placeholder="Harga Jual (Rp 0)" oninput="maskRupiah(this); updateRowMargin(this); calculateTotalFromSub();" class="input-rupiah sub-price w-full px-2.5 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
                            </div>
                            <div class="md:col-span-1">
                                <input type="number" name="sub_features[{{ $index }}][sort_order]" value="{{ $sub->sort_order }}" min="0" placeholder="Urutan" class="w-full px-2 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs text-center">
                            </div>
                            <div class="md:col-span-1 flex justify-end">
                                <button type="button" onclick="this.closest('.sub-row').remove(); calculateTotalFromSub();" class="tally-btn w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900 text-rose-600 dark:text-rose-300 text-xs border border-rose-500/20 font-bold flex items-center justify-center transition-colors">
                                    ✕
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-1 text-[11px] font-mono border-t border-[var(--tally-card-border)]/50 pt-1.5 text-[var(--tally-ink-2)]">
                            <span>Margin Sub Fitur:</span>
                            <span class="row-margin-badge font-semibold text-emerald-600 dark:text-emerald-400">Rp 0 (0%)</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sub Features Live Summary Bench -->
            <div id="sub_summary_bar" class="p-3 rounded-xl bg-[var(--tally-paper-1)] border border-[var(--tally-card-border)] grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-mono">
                <div>
                    <p class="text-[10px] text-[var(--tally-ink-2)]">Total Harga Real (Modal)</p>
                    <p class="font-bold text-amber-500 mt-0.5" id="sum_real_price">Rp 0</p>
                </div>
                <div>
                    <p class="text-[10px] text-[var(--tally-ink-2)]">Total Harga Jual (Klien)</p>
                    <p class="font-bold text-indigo-600 dark:text-indigo-400 mt-0.5" id="sum_price">Rp 0</p>
                </div>
                <div>
                    <p class="text-[10px] text-[var(--tally-ink-2)]">Total Margin Keuntungan</p>
                    <p class="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5" id="sum_margin">Rp 0 (0%)</p>
                </div>
            </div>
        </div>

        <!-- Sort Order & Status -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $feature->sort_order) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[var(--tally-ink-1)] mb-1.5 font-mono">Status *</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs">
                    <option value="active" {{ old('status', $feature->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $feature->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-[var(--tally-card-border)] flex items-center justify-end gap-3 font-mono">
            <a href="{{ route('admin.features.index') }}" class="tally-btn px-4 py-2 rounded-xl text-xs font-semibold text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
                Batal
            </a>
            <button type="submit" class="tally-btn px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/20">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    let nextSubIndex = {{ $feature->subFeatures->count() + 100 }};

    function addSubFeatureRow(name = '', realPrice = '', price = '') {
        const list = document.getElementById('sub_features_list');
        const row = document.createElement('div');
        row.className = 'p-3 rounded-xl bg-[var(--tally-paper-1)]/60 border border-[var(--tally-card-border)] sub-row transition-all space-y-2';

        const formattedReal = realPrice ? (typeof realPrice === 'number' || !isNaN(Number(realPrice)) ? formatRupiahJs(realPrice) : realPrice) : 'Rp 0';
        const formattedPrice = price ? (typeof price === 'number' || !isNaN(Number(price)) ? formatRupiahJs(price) : price) : 'Rp 0';

        row.innerHTML = `
            <input type="hidden" name="sub_features[${nextSubIndex}][id]" value="">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-4">
                    <input type="text" name="sub_features[${nextSubIndex}][name]" value="${name}" placeholder="Nama Sub Fitur Baru" required class="w-full px-3 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
                </div>
                <div class="md:col-span-3">
                    <input type="text" inputmode="numeric" name="sub_features[${nextSubIndex}][real_price]" value="${formattedReal}" placeholder="Harga Real (Rp 0)" oninput="maskRupiah(this); updateRowMargin(this); calculateTotalFromSub();" class="input-rupiah sub-real-price w-full px-2.5 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-amber-500 shadow-xs">
                </div>
                <div class="md:col-span-3">
                    <input type="text" inputmode="numeric" name="sub_features[${nextSubIndex}][price]" value="${formattedPrice}" placeholder="Harga Jual (Rp 0)" oninput="maskRupiah(this); updateRowMargin(this); calculateTotalFromSub();" class="input-rupiah sub-price w-full px-2.5 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-xs">
                </div>
                <div class="md:col-span-1">
                    <input type="number" name="sub_features[${nextSubIndex}][sort_order]" value="${nextSubIndex}" min="0" placeholder="Urutan" class="w-full px-2 py-2 rounded-lg bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-[var(--tally-ink-0)] text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-xs text-center">
                </div>
                <div class="md:col-span-1 flex justify-end">
                    <button type="button" onclick="this.closest('.sub-row').remove(); calculateTotalFromSub();" class="tally-btn w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900 text-rose-600 dark:text-rose-300 text-xs border border-rose-500/20 font-bold flex items-center justify-center transition-colors">
                        ✕
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between px-1 text-[11px] font-mono border-t border-[var(--tally-card-border)]/50 pt-1.5 text-[var(--tally-ink-2)]">
                <span>Margin Sub Fitur:</span>
                <span class="row-margin-badge font-semibold text-emerald-600 dark:text-emerald-400">Rp 0 (0%)</span>
            </div>
        `;
        list.appendChild(row);
        updateRowMargin(row.querySelector('.sub-price'));
        nextSubIndex++;
        calculateTotalFromSub();
    }

    function updateRowMargin(inputEl) {
        const row = inputEl.closest('.sub-row');
        if (!row) return;

        const realEl = row.querySelector('.sub-real-price');
        const priceEl = row.querySelector('.sub-price');
        const badgeEl = row.querySelector('.row-margin-badge');

        const realVal = parseFloat((realEl?.value || '').replace(/[^0-9]/g, '')) || 0;
        const priceVal = parseFloat((priceEl?.value || '').replace(/[^0-9]/g, '')) || 0;
        const margin = priceVal - realVal;
        const percent = priceVal > 0 ? ((margin / priceVal) * 100).toFixed(1) : 0;

        const sign = margin > 0 ? '+' : '';
        const colorClass = margin >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400';
        badgeEl.className = `row-margin-badge font-semibold ${colorClass}`;
        badgeEl.innerText = `${sign}${formatRupiahJs(margin)} (${percent}%)`;
    }

    function calculateTotalFromSub() {
        const priceInputs = document.querySelectorAll('.sub-price');
        const realInputs = document.querySelectorAll('.sub-real-price');

        let totalPrice = 0;
        let totalReal = 0;

        priceInputs.forEach(input => {
            const clean = input.value.replace(/[^0-9]/g, '');
            totalPrice += parseFloat(clean) || 0;
        });

        realInputs.forEach(input => {
            const clean = input.value.replace(/[^0-9]/g, '');
            totalReal += parseFloat(clean) || 0;
        });

        const totalMargin = totalPrice - totalReal;
        const totalPercent = totalPrice > 0 ? ((totalMargin / totalPrice) * 100).toFixed(1) : 0;

        // Update summary bench
        document.getElementById('sum_price').innerText = formatRupiahJs(totalPrice);
        document.getElementById('sum_real_price').innerText = formatRupiahJs(totalReal);

        const sumMarginEl = document.getElementById('sum_margin');
        const sign = totalMargin > 0 ? '+' : '';
        sumMarginEl.className = `font-bold mt-0.5 ${totalMargin >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'}`;
        sumMarginEl.innerText = `${sign}${formatRupiahJs(totalMargin)} (${totalPercent}%)`;

        // Update main feature input fields if sub features exist
        if (priceInputs.length > 0) {
            document.getElementById('price_input').value = formatRupiahJs(totalPrice);
            document.getElementById('real_price_input').value = formatRupiahJs(totalReal);
            calculateStandaloneMargin();
        }
    }

    function calculateStandaloneMargin() {
        const priceVal = parseFloat(document.getElementById('price_input').value.replace(/[^0-9]/g, '')) || 0;
        const realVal = parseFloat(document.getElementById('real_price_input').value.replace(/[^0-9]/g, '')) || 0;
        const margin = priceVal - realVal;
        const percent = priceVal > 0 ? ((margin / priceVal) * 100).toFixed(1) : 0;

        const badge = document.getElementById('standalone_margin_badge');
        const sign = margin > 0 ? '+' : '';
        badge.className = `font-bold ${margin >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'}`;
        badge.innerText = `${sign}${formatRupiahJs(margin)} (${percent}%)`;
    }

    function toggleFeatureType() {
        const parentSelect = document.getElementById('parent_id');
        const subContainer = document.getElementById('sub_features_container');
        const priceLabel = document.getElementById('price_label');
        const realPriceLabel = document.getElementById('real_price_label');
        const priceHelp = document.getElementById('price_help');
        const isSub = parentSelect.value !== '';

        if (isSub) {
            if (subContainer) subContainer.style.display = 'none';
            if (priceLabel) priceLabel.innerText = 'Harga Jual Sub Fitur *';
            if (realPriceLabel) realPriceLabel.innerHTML = '<span>Harga Real (Modal) Sub Fitur *</span><span class="text-[10px] text-amber-500 font-normal">🔒 Internal Only</span>';
            if (priceHelp) priceHelp.innerText = 'Harga jual yang akan diakumulasikan ke fitur utama.';
        } else {
            if (subContainer) subContainer.style.display = 'block';
            if (priceLabel) priceLabel.innerText = 'Total Harga Jual Fitur';
            if (realPriceLabel) realPriceLabel.innerHTML = '<span>Total Harga Real (Modal) Fitur</span><span class="text-[10px] text-amber-500 font-normal">🔒 Internal Only</span>';
            if (priceHelp) priceHelp.innerText = 'Jika fitur memiliki sub-fitur di bawah, harga jual & harga real otomatis dihitung dari sub-fitur.';
        }
        calculateStandaloneMargin();
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleFeatureType();
        // Calculate initial margins for all existing sub-feature rows
        document.querySelectorAll('.sub-row').forEach(row => {
            const priceEl = row.querySelector('.sub-price');
            if (priceEl) updateRowMargin(priceEl);
        });
        calculateTotalFromSub();
        calculateStandaloneMargin();
    });
</script>
@endsection
