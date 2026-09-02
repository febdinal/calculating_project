@extends('layouts.app')

@section('title', 'Konfigurasi Fitur — ' . $selectedPackage->name)

@push('styles')
<style>
    /* Hallmark Tally Drag & Drop Styling */
    .card-dragging {
        opacity: 0.25 !important;
        transform: scale(0.96) !important;
        border: 1px dashed #6366f1 !important;
        background: rgba(99, 102, 241, 0.05) !important;
        box-shadow: none !important;
    }

    .dropzone-active {
        border-color: rgba(99, 102, 241, 0.4) !important;
        background: rgba(99, 102, 241, 0.02) !important;
    }

    .dropzone-hover {
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, 0.08) !important;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.15) !important;
    }

    .feature-card {
        transition: transform 140ms cubic-bezier(0.22, 0.61, 0.36, 1), box-shadow 140ms ease, border-color 140ms ease, background-color 240ms ease;
    }

    .feature-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 pb-16">
    
    <!-- Hallmark Tally Top Header & Package Switcher Bar -->
    <div class="p-5 sm:p-6 rounded-3xl tally-card flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="tally-dot-live"></span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                    Paket {{ $selectedPackage->name }}
                </span>
                <span class="text-xs text-[var(--tally-ink-2)] font-mono">
                    {{ $selectedPackage->slug === 'custom' ? '✨ Affordable Custom' : 'Rp ' . number_format($selectedPackage->price, 0, ',', '.') . ' / ' . $selectedPackage->period }}
                </span>
            </div>
            <h1 class="text-lg sm:text-xl font-extrabold text-[var(--tally-ink-0)] tracking-tight">
                Funix Feature Configurator & Price Calculator
            </h1>
            <p class="text-xs text-[var(--tally-ink-2)]">
                Tarik fitur ke kolom tengah dan sesuaikan sub-fitur secara interaktif. Biaya dihitung realtime di panel kanan.
            </p>
        </div>

        <!-- Package Switcher & Reset Button -->
        <div class="flex items-center gap-2 shrink-0">
            <div class="relative">
                <select onchange="switchPackage(this.value)" class="appearance-none pl-3.5 pr-8 py-2 rounded-xl bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] text-xs text-[var(--tally-ink-0)] font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono shadow-sm">
                    @foreach ($allPackages as $pkg)
                        <option value="{{ $pkg->slug }}" {{ $pkg->id === $selectedPackage->id ? 'selected' : '' }}>
                            Paket {{ $pkg->name }} ({{ $pkg->slug === 'custom' ? 'Affordable' : 'Rp ' . number_format($pkg->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-[var(--tally-ink-2)]">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <button type="button" onclick="resetToPackageDefaults()" class="tally-btn px-3 py-2 rounded-xl bg-[var(--tally-subtle-bg)] hover:bg-[var(--tally-paper-3)] border border-[var(--tally-card-border)] text-[var(--tally-ink-1)] text-xs font-mono transition-colors flex items-center gap-1.5" title="Reset ke Fitur Bawaan Paket">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
        </div>
    </div>

    <!-- Mobile View Tab Switcher (Visible on < lg screens) -->
    <div class="flex lg:hidden rounded-2xl bg-[var(--tally-paper-1)] border border-[var(--tally-card-border)] p-1 gap-1">
        <button type="button" onclick="switchMobileTab('available')" id="tab-btn-available" class="flex-1 py-2 text-xs font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md">
            Fitur Tersedia (<span id="mobile-count-available">0</span>)
        </button>
        <button type="button" onclick="switchMobileTab('selected')" id="tab-btn-selected" class="flex-1 py-2 text-xs font-bold rounded-xl transition-all text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
            Dipilih (<span id="mobile-count-selected">0</span>)
        </button>
        <button type="button" onclick="switchMobileTab('summary')" id="tab-btn-summary" class="flex-1 py-2 text-xs font-bold rounded-xl transition-all text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]">
            Ringkasan Biaya
        </button>
    </div>

    <!-- 3-Column Hallmark Tally Kanban Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- COLUMN 1: FITUR TERSEDIA (4 cols) -->
        <div id="col-available" class="lg:col-span-4 space-y-4">
            <div class="p-4 rounded-2xl tally-card space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <h2 class="text-xs font-mono font-bold text-[var(--tally-ink-0)] uppercase tracking-wider">Fitur Tersedia</h2>
                    </div>
                    <span id="badge-count-available" class="text-[10px] px-2 py-0.5 rounded-full bg-[var(--tally-subtle-bg)] text-[var(--tally-ink-2)] font-mono border border-[var(--tally-card-border)]">
                        0 Fitur
                    </span>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[var(--tally-ink-2)]">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="featureSearch" oninput="filterAvailableFeatures()" placeholder="Cari nama atau rincian fitur..."
                        class="w-full pl-8 pr-3 py-2 bg-[var(--tally-input-bg)] border border-[var(--tally-input-border)] rounded-xl text-[var(--tally-ink-0)] text-xs placeholder-[var(--tally-ink-3)] focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-all font-sans">
                </div>

                <!-- Category Filter Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-thin">
                    <button type="button" onclick="setCategoryFilter('')" data-category="" class="cat-pill active px-3 py-1 rounded-lg text-[11px] font-medium transition-all whitespace-nowrap bg-indigo-600 text-white font-mono">
                        Semua
                    </button>
                    @foreach ($categories as $cat)
                        <button type="button" onclick="setCategoryFilter({{ $cat->id }})" data-category="{{ $cat->id }}" class="cat-pill px-3 py-1 rounded-lg text-[11px] font-medium transition-all whitespace-nowrap bg-[var(--tally-subtle-bg)] text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)] border border-[var(--tally-card-border)] font-mono">
                            {{ $cat->icon }} {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Available Features Dropzone & List -->
            <div id="dropzone-available" class="space-y-3 min-h-[420px] p-2 rounded-2xl bg-[var(--tally-paper-2)]/40 border border-dashed border-[var(--tally-card-border)] transition-all duration-200"
                ondragover="handleDragOver(event, 'dropzone-available')" ondragleave="handleDragLeave(event, 'dropzone-available')" ondrop="handleDrop(event, 'available')">
                <!-- Cards populated by JS -->
            </div>
        </div>

        <!-- COLUMN 2: FITUR DIPILIH (5 cols) -->
        <div id="col-selected" class="hidden lg:block lg:col-span-5 space-y-4">
            <div class="p-4 rounded-2xl tally-card border-indigo-500/30 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="tally-dot-live"></span>
                        <h2 class="text-xs font-mono font-bold text-[var(--tally-ink-0)] uppercase tracking-wider">Fitur Dipilih</h2>
                    </div>
                    <span id="badge-count-selected" class="text-[10px] px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 font-mono font-bold border border-indigo-500/20">
                        0 Fitur
                    </span>
                </div>
                <p class="text-xs text-[var(--tally-ink-2)]">
                    Kustomisasi sub-fitur di bawah. Menonaktifkan sub-fitur langsung memotong harga secara realtime di panel kanan.
                </p>
            </div>

            <!-- Selected Features Dropzone & List -->
            <div id="dropzone-selected" class="space-y-3 min-h-[420px] p-2.5 rounded-2xl bg-indigo-500/5 border-2 border-dashed border-indigo-500/30 transition-all duration-200"
                ondragover="handleDragOver(event, 'dropzone-selected')" ondragleave="handleDragLeave(event, 'dropzone-selected')" ondrop="handleDrop(event, 'selected')">
                <!-- Cards populated by JS -->
            </div>
        </div>

        <!-- COLUMN 3: TALLY BENCH SUMMARY (3 cols) -->
        <div id="col-summary" class="hidden lg:block lg:col-span-3 space-y-4 sticky top-20">
            <div class="p-5 rounded-3xl tally-card border-[var(--tally-card-border)] shadow-2xl space-y-5">
                
                <div class="border-b border-[var(--tally-card-border)] pb-3 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-[var(--tally-ink-2)]">Rincian Kalkulasi</span>
                        <h3 class="text-sm font-bold text-[var(--tally-ink-0)] mt-0.5">Paket {{ $selectedPackage->name }}</h3>
                    </div>
                    <span class="tally-dot-live"></span>
                </div>

                <!-- Price Breakdown Table -->
                <div class="space-y-3 text-xs">
                    <!-- Base Package Price -->
                    <div class="flex items-center justify-between text-[var(--tally-ink-1)]">
                        <span>Harga Paket Dasar</span>
                        <span class="font-bold text-[var(--tally-ink-0)] font-mono">
                            Rp {{ number_format($selectedPackage->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Included Package Deductions -->
                    <div id="summary-deduction-row" class="hidden flex items-center justify-between text-emerald-600 dark:text-emerald-400 font-semibold py-1">
                        <span>Potongan Sub-Fitur Paket</span>
                        <span id="summary-deduction-total" class="font-mono font-bold">- Rp 0</span>
                    </div>

                    <!-- Adjusted Package Subtotal -->
                    <div id="summary-adjusted-package-row" class="hidden flex items-center justify-between text-[var(--tally-ink-1)] pt-1 border-t border-[var(--tally-card-border)]">
                        <span class="text-[11px] text-[var(--tally-ink-2)]">Paket Disesuaikan</span>
                        <span id="summary-adjusted-package-price" class="font-bold text-[var(--tally-ink-0)] font-mono">Rp 0</span>
                    </div>

                    <!-- Additional Features List -->
                    <div class="pt-2 border-t border-[var(--tally-card-border)]">
                        <span class="text-[10px] font-mono font-semibold text-[var(--tally-ink-2)] uppercase tracking-wider block mb-2">
                            Fitur Tambahan:
                        </span>
                        <div id="summary-additional-list" class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-[var(--tally-ink-1)] scrollbar-thin">
                            <!-- Items appear here -->
                        </div>
                    </div>

                    <!-- Additional Total Subtotal -->
                    <div class="pt-2 border-t border-[var(--tally-card-border)] flex items-center justify-between text-[var(--tally-ink-1)]">
                        <span>Total Tambahan</span>
                        <span id="summary-additional-total" class="font-bold text-indigo-600 dark:text-indigo-300 font-mono">
                            Rp 0
                        </span>
                    </div>
                </div>

                <!-- Grand Total Box -->
                <div class="pt-4 border-t-2 border-[var(--tally-card-border)] space-y-1 bg-[var(--tally-subtle-bg)] -mx-5 -mb-5 p-5 rounded-b-3xl">
                    <span class="text-[10px] font-mono font-semibold text-[var(--tally-ink-2)] uppercase tracking-wider">TOTAL ESTIMASI BIAYA</span>
                    <div id="summary-total-price" class="text-xl font-black text-[var(--tally-ink-0)] font-mono tracking-tight">
                        Rp {{ number_format($selectedPackage->price, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-[var(--tally-ink-3)] font-mono">Estimasi per {{ $selectedPackage->period }}. Belum termasuk PPN jika berlaku.</p>

                    <!-- Generate PDF Button -->
                    <div class="pt-4">
                        <button type="button" onclick="generatePDF()" class="tally-btn w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:bg-white text-white dark:text-[#07090e] dark:hover:bg-slate-200 text-xs font-bold shadow-[0_4px_20px_rgba(79,70,229,0.3)] dark:shadow-[0_4px_20px_rgba(255,255,255,0.2)] transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Unduh PDF Estimasi</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Hidden PDF Form -->
<form id="pdfForm" method="POST" action="{{ route('calculator.pdf') }}" target="_blank" class="hidden">
    @csrf
    <input type="hidden" name="package_id" id="pdf_package_id" value="{{ $selectedPackage->id }}">
    <input type="hidden" name="feature_ids" id="pdf_feature_ids" value="">
    <input type="hidden" name="sub_feature_ids" id="pdf_sub_feature_ids" value="">
</form>

@endsection

@push('scripts')
<script>
    const currentPackage = {
        id: {{ $selectedPackage->id }},
        name: "{{ $selectedPackage->name }}",
        slug: "{{ $selectedPackage->slug }}",
        price: {{ (float) $selectedPackage->price }},
        period: "{{ $selectedPackage->period }}"
    };

    const allFeaturesData = @json($featuresData);
    const includedFeatureIds = @json($includedFeatureIds);

    let selectedFeatureIds = [...includedFeatureIds];
    let selectedSubFeatureMap = {};
    let expandedSubMap = {};

    let activeCategoryFilter = '';
    let currentSearchTerm = '';
    let draggedFeatureId = null;

    function getSelectedSubFeatureIds(featureId) {
        if (!selectedSubFeatureMap[featureId]) {
            const feat = allFeaturesData.find(f => f.id === featureId);
            if (feat && feat.sub_features) {
                selectedSubFeatureMap[featureId] = new Set(feat.sub_features.map(s => s.id));
            } else {
                selectedSubFeatureMap[featureId] = new Set();
            }
        }
        return selectedSubFeatureMap[featureId];
    }

    function getFeatureCurrentPrice(feature) {
        if (!feature.sub_features || feature.sub_features.length === 0) {
            return feature.price || 0;
        }
        const selectedSubs = getSelectedSubFeatureIds(feature.id);
        let total = 0;
        feature.sub_features.forEach(sub => {
            if (selectedSubs.has(sub.id)) {
                total += sub.price;
            }
        });
        return total;
    }

    function formatRupiah(amount) {
        return 'Rp ' + Math.round(Number(amount) || 0).toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', () => {
        includedFeatureIds.forEach(fId => {
            getSelectedSubFeatureIds(fId);
        });
        renderKanban();
    });

    function renderKanban() {
        renderAvailableColumn();
        renderSelectedColumn();
        updateRealtimeCalculation();
    }

    function renderAvailableColumn() {
        const dropzone = document.getElementById('dropzone-available');
        dropzone.innerHTML = '';

        const availableFeatures = allFeaturesData.filter(f => !selectedFeatureIds.includes(f.id));

        const filtered = availableFeatures.filter(f => {
            const matchesCat = !activeCategoryFilter || f.category_id == activeCategoryFilter;
            const matchesSearch = !currentSearchTerm || 
                f.name.toLowerCase().includes(currentSearchTerm.toLowerCase()) || 
                (f.description && f.description.toLowerCase().includes(currentSearchTerm.toLowerCase()));
            return matchesCat && matchesSearch;
        });

        document.getElementById('badge-count-available').innerText = `${availableFeatures.length} Fitur`;
        document.getElementById('mobile-count-available').innerText = availableFeatures.length;

        if (filtered.length === 0) {
            dropzone.innerHTML = `
                <div class="p-8 text-center rounded-2xl bg-[var(--tally-paper-1)] border border-dashed border-[var(--tally-card-border)] text-[var(--tally-ink-2)] text-xs font-mono">
                    ${availableFeatures.length === 0 ? 'Semua fitur sudah dipilih.' : 'Tidak ada fitur yang cocok dengan pencarian.'}
                </div>
            `;
            return;
        }

        filtered.forEach(feature => {
            const isIncludedInPackage = includedFeatureIds.includes(feature.id);
            const card = document.createElement('div');
            card.className = 'feature-card p-4 rounded-2xl tally-card cursor-grab active:cursor-grabbing select-none';
            card.draggable = true;
            card.dataset.id = feature.id;

            card.addEventListener('dragstart', (e) => handleDragStart(e, feature.id, card));
            card.addEventListener('dragend', (e) => handleDragEnd(e, card));

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-2.5 min-w-0">
                        <span class="text-lg shrink-0 mt-0.5">${feature.icon || '⚡'}</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-[var(--tally-ink-0)] truncate">${feature.name}</h4>
                            <span class="inline-block text-[10px] text-[var(--tally-ink-2)] font-mono">${feature.category_name}</span>
                        </div>
                    </div>
                    <button type="button" onclick="selectFeature(${feature.id})" class="tally-btn px-2.5 py-1 rounded-lg bg-indigo-500/10 hover:bg-indigo-600 text-indigo-600 dark:text-indigo-300 hover:text-white text-xs font-mono font-semibold border border-indigo-500/20 shrink-0">
                        + Tambah
                    </button>
                </div>

                ${feature.description ? `<p class="text-[11px] text-[var(--tally-ink-2)] mt-2 line-clamp-2 leading-relaxed">${feature.description}</p>` : ''}

                <div class="mt-3 pt-2.5 border-t border-[var(--tally-card-border)] flex items-center justify-between">
                    <span class="text-xs font-bold text-[var(--tally-ink-0)] font-mono">
                        ${formatRupiah(feature.price)}
                    </span>
                    ${isIncludedInPackage ? `
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 font-mono font-medium">
                            Bawaan Paket
                        </span>
                    ` : `
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-[var(--tally-subtle-bg)] text-[var(--tally-ink-2)] font-mono">
                            Fitur Tambahan
                        </span>
                    `}
                </div>

                ${feature.sub_features && feature.sub_features.length > 0 ? `
                    <div class="mt-2.5 pt-2 border-t border-[var(--tally-card-border)]">
                        <button type="button" onclick="toggleSubFeaturesAvailable(${feature.id})" class="text-[10px] font-mono font-medium text-[var(--tally-ink-2)] hover:text-indigo-600 dark:hover:text-indigo-300 flex items-center justify-between w-full">
                            <span>${feature.sub_features.length} Rincian Sub Fitur</span>
                            <span id="sub-avail-arrow-${feature.id}">▼</span>
                        </button>
                        <div id="sub-avail-list-${feature.id}" class="hidden mt-1.5 p-2 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] space-y-1">
                            ${feature.sub_features.map(sub => `
                                <div class="flex items-center justify-between text-[10px] text-[var(--tally-ink-1)] py-0.5 font-mono">
                                    <span class="truncate pr-2">&bull; ${sub.name}</span>
                                    <span class="text-[var(--tally-ink-2)] shrink-0">${formatRupiah(sub.price)}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            `;

            dropzone.appendChild(card);
        });
    }

    function renderSelectedColumn() {
        const dropzone = document.getElementById('dropzone-selected');
        dropzone.innerHTML = '';

        const selectedFeatures = selectedFeatureIds.map(id => allFeaturesData.find(f => f.id === id)).filter(Boolean);

        document.getElementById('badge-count-selected').innerText = `${selectedFeatures.length} Fitur`;
        document.getElementById('mobile-count-selected').innerText = selectedFeatures.length;

        if (selectedFeatures.length === 0) {
            dropzone.innerHTML = `
                <div class="p-8 text-center rounded-2xl bg-[var(--tally-paper-1)] border border-dashed border-indigo-500/30 text-[var(--tally-ink-2)] text-xs font-mono">
                    Tarik fitur ke area ini atau klik <strong>+ Tambah</strong> pada kolom kiri.
                </div>
            `;
            return;
        }

        selectedFeatures.forEach((feature) => {
            const isIncludedInPackage = includedFeatureIds.includes(feature.id);
            const hasSubFeatures = feature.sub_features && feature.sub_features.length > 0;
            const selectedSubSet = getSelectedSubFeatureIds(feature.id);
            const currentFeaturePrice = getFeatureCurrentPrice(feature);
            const defaultFullPrice = feature.default_price || feature.price || 0;
            const isExpanded = expandedSubMap[feature.id] ?? true;

            const card = document.createElement('div');
            card.className = 'feature-card p-4 rounded-2xl tally-card border-indigo-500/30 transition-all cursor-grab active:cursor-grabbing select-none';
            card.draggable = true;
            card.dataset.id = feature.id;

            card.addEventListener('dragstart', (e) => handleDragStart(e, feature.id, card));
            card.addEventListener('dragend', (e) => handleDragEnd(e, card));

            let statusBadgeHtml = '';
            if (isIncludedInPackage) {
                if (currentFeaturePrice < defaultFullPrice) {
                    const deduction = defaultFullPrice - currentFeaturePrice;
                    statusBadgeHtml = `
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 font-mono font-semibold">
                            ✓ Paket (-${formatRupiah(deduction)})
                        </span>
                    `;
                } else {
                    statusBadgeHtml = `
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 font-mono font-medium">
                            ✓ Termasuk Paket
                        </span>
                    `;
                }
            } else {
                statusBadgeHtml = `
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30 font-mono font-bold">
                        + Tambahan
                    </span>
                `;
            }

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-2.5 min-w-0">
                        <span class="text-lg shrink-0 mt-0.5">${feature.icon || '⚡'}</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-[var(--tally-ink-0)] truncate">${feature.name}</h4>
                            <span class="inline-block text-[10px] text-indigo-600 dark:text-indigo-300 font-mono">${feature.category_name}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <div class="flex items-center bg-[var(--tally-subtle-bg)] rounded-lg border border-[var(--tally-card-border)] p-0.5 font-mono">
                            <button type="button" onclick="moveFeaturePosition(${feature.id}, -1)" class="w-5 h-5 rounded hover:bg-[var(--tally-paper-3)] text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)] text-[10px] flex items-center justify-center transition-colors" title="Pindah Ke Atas">
                                ▲
                            </button>
                            <button type="button" onclick="moveFeaturePosition(${feature.id}, 1)" class="w-5 h-5 rounded hover:bg-[var(--tally-paper-3)] text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)] text-[10px] flex items-center justify-center transition-colors" title="Pindah Ke Bawah">
                                ▼
                            </button>
                        </div>
                        <button type="button" onclick="removeFeature(${feature.id})" class="w-6 h-6 rounded-lg bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/80 text-rose-600 dark:text-rose-300 text-xs font-bold flex items-center justify-center border border-rose-500/30 shrink-0 transition-colors" title="Hapus Fitur">
                            ×
                        </button>
                    </div>
                </div>

                <div class="mt-3 pt-2.5 border-t border-[var(--tally-card-border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-[var(--tally-ink-0)] font-mono">
                            ${formatRupiah(currentFeaturePrice)}
                        </span>
                        ${hasSubFeatures && currentFeaturePrice !== defaultFullPrice ? `
                            <span class="text-[10px] text-[var(--tally-ink-3)] line-through font-mono ml-1">
                                ${formatRupiah(defaultFullPrice)}
                            </span>
                        ` : ''}
                    </div>
                    ${statusBadgeHtml}
                </div>

                ${hasSubFeatures ? `
                    <div class="mt-3 pt-2.5 border-t border-[var(--tally-card-border)]">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="toggleSubFeatures(${feature.id})" class="text-[11px] font-mono font-medium text-[var(--tally-ink-1)] hover:text-indigo-600 dark:hover:text-indigo-300 flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 font-mono text-[10px] font-bold">
                                    ${selectedSubSet.size}/${feature.sub_features.length}
                                </span>
                                <span>Kustomisasi Sub Fitur</span>
                                <span id="sub-arrow-${feature.id}" class="text-[10px] text-[var(--tally-ink-2)]">${isExpanded ? '▲' : '▼'}</span>
                            </button>
                            <div class="flex items-center gap-1.5 text-[10px] font-mono">
                                <button type="button" onclick="toggleAllSubFeatures(${feature.id}, true)" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                                    Semua
                                </button>
                                <span class="text-[var(--tally-ink-3)]">&bull;</span>
                                <button type="button" onclick="toggleAllSubFeatures(${feature.id}, false)" class="text-rose-600 dark:text-rose-400 hover:underline font-semibold">
                                    Batal
                                </button>
                            </div>
                        </div>

                        <div id="sub-list-${feature.id}" class="${isExpanded ? '' : 'hidden'} mt-2 p-2 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] space-y-1">
                            ${feature.sub_features.map(sub => {
                                const isSubSelected = selectedSubSet.has(sub.id);
                                return `
                                    <label class="flex items-center justify-between p-1.5 rounded-lg cursor-pointer transition-colors ${isSubSelected ? 'bg-[var(--tally-paper-1)] text-[var(--tally-ink-0)] border border-indigo-500/20 shadow-xs' : 'bg-transparent text-[var(--tally-ink-3)] hover:bg-[var(--tally-paper-3)]/30'}">
                                        <div class="flex items-center gap-2 min-w-0 pr-2">
                                            <input type="checkbox" onchange="toggleSubFeature(${feature.id}, ${sub.id}, this.checked)" ${isSubSelected ? 'checked' : ''} class="w-3.5 h-3.5 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 cursor-pointer shrink-0">
                                            <span class="text-[11px] truncate ${isSubSelected ? 'font-medium text-[var(--tally-ink-0)]' : 'text-[var(--tally-ink-3)] line-through'}">
                                                ${sub.name}
                                            </span>
                                        </div>
                                        <span class="font-mono text-[11px] shrink-0 ${isSubSelected ? 'font-bold text-[var(--tally-ink-0)]' : 'text-[var(--tally-ink-3)] line-through'}">
                                            ${formatRupiah(sub.price)}
                                        </span>
                                    </label>
                                `;
                            }).join('')}
                        </div>
                    </div>
                ` : ''}
            `;

            dropzone.appendChild(card);
        });
    }

    function toggleSubFeature(featureId, subId, isChecked) {
        const selectedSubs = getSelectedSubFeatureIds(featureId);
        if (isChecked) {
            selectedSubs.add(subId);
        } else {
            selectedSubs.delete(subId);
        }
        renderSelectedColumn();
        updateRealtimeCalculation();
    }

    function toggleAllSubFeatures(featureId, selectAll) {
        const feat = allFeaturesData.find(f => f.id === featureId);
        if (!feat || !feat.sub_features) return;
        const selectedSubs = getSelectedSubFeatureIds(featureId);
        if (selectAll) {
            feat.sub_features.forEach(s => selectedSubs.add(s.id));
        } else {
            selectedSubs.clear();
        }
        renderSelectedColumn();
        updateRealtimeCalculation();
    }

    function toggleSubFeatures(featureId) {
        const isCurrentlyExpanded = expandedSubMap[featureId] ?? true;
        expandedSubMap[featureId] = !isCurrentlyExpanded;

        const subList = document.getElementById(`sub-list-${featureId}`);
        const subArrow = document.getElementById(`sub-arrow-${featureId}`);
        if (!subList) return;

        if (subList.classList.contains('hidden')) {
            subList.classList.remove('hidden');
            if (subArrow) subArrow.innerText = '▲';
        } else {
            subList.classList.add('hidden');
            if (subArrow) subArrow.innerText = '▼';
        }
    }

    function toggleSubFeaturesAvailable(featureId) {
        const subList = document.getElementById(`sub-avail-list-${featureId}`);
        const subArrow = document.getElementById(`sub-avail-arrow-${featureId}`);
        if (!subList) return;

        if (subList.classList.contains('hidden')) {
            subList.classList.remove('hidden');
            if (subArrow) subArrow.innerText = '▲';
        } else {
            subList.classList.add('hidden');
            if (subArrow) subArrow.innerText = '▼';
        }
    }

    function updateRealtimeCalculation() {
        const summaryList = document.getElementById('summary-additional-list');
        const summaryAddTotal = document.getElementById('summary-additional-total');
        const summaryTotalPrice = document.getElementById('summary-total-price');

        let additionalTotal = 0;
        let additionalItems = [];
        let totalIncludedDeduction = 0;

        includedFeatureIds.forEach(id => {
            const feat = allFeaturesData.find(f => f.id === id);
            if (!feat) return;

            const defaultFullPrice = feat.default_price || feat.price || 0;
            if (selectedFeatureIds.includes(feat.id)) {
                const currentPrice = getFeatureCurrentPrice(feat);
                if (currentPrice < defaultFullPrice) {
                    totalIncludedDeduction += (defaultFullPrice - currentPrice);
                }
            } else {
                totalIncludedDeduction += defaultFullPrice;
            }
        });

        selectedFeatureIds.forEach(id => {
            const feat = allFeaturesData.find(f => f.id === id);
            if (!feat) return;

            const isIncluded = includedFeatureIds.includes(feat.id);
            const currentPrice = getFeatureCurrentPrice(feat);

            if (!isIncluded) {
                additionalTotal += currentPrice;
                const subSet = getSelectedSubFeatureIds(feat.id);
                additionalItems.push({
                    name: feat.name,
                    price: currentPrice,
                    subCount: subSet.size,
                    totalSub: feat.sub_features ? feat.sub_features.length : 0
                });
            }
        });

        const deductionRow = document.getElementById('summary-deduction-row');
        const adjustedRow = document.getElementById('summary-adjusted-package-row');
        const deductionTotalEl = document.getElementById('summary-deduction-total');
        const adjustedPriceEl = document.getElementById('summary-adjusted-package-price');

        const adjustedPackagePrice = Math.max(0, currentPackage.price - totalIncludedDeduction);

        if (totalIncludedDeduction > 0) {
            deductionRow.classList.remove('hidden');
            adjustedRow.classList.remove('hidden');
            deductionTotalEl.innerText = '- ' + formatRupiah(totalIncludedDeduction);
            adjustedPriceEl.innerText = formatRupiah(adjustedPackagePrice);
        } else {
            deductionRow.classList.add('hidden');
            adjustedRow.classList.add('hidden');
        }

        if (additionalItems.length === 0) {
            summaryList.innerHTML = '<p class="text-[var(--tally-ink-3)] italic py-1 font-mono text-[11px]">Tidak ada fitur tambahan.</p>';
        } else {
            summaryList.innerHTML = additionalItems.map(item => `
                <div class="flex items-center justify-between py-1 border-b border-[var(--tally-card-border)] last:border-0 font-mono text-xs">
                    <div class="min-w-0 pr-2">
                        <span class="truncate block text-[var(--tally-ink-0)] font-sans">${item.name}</span>
                        ${item.totalSub > 0 ? `<span class="text-[10px] text-[var(--tally-ink-2)] font-mono">${item.subCount}/${item.totalSub} sub-fitur</span>` : ''}
                    </div>
                    <span class="font-bold text-[var(--tally-ink-0)] shrink-0">${formatRupiah(item.price)}</span>
                </div>
            `).join('');
        }

        const grandTotal = adjustedPackagePrice + additionalTotal;

        summaryAddTotal.innerText = formatRupiah(additionalTotal);
        summaryTotalPrice.innerText = formatRupiah(grandTotal);

        const allActiveSubIds = [];
        selectedFeatureIds.forEach(fId => {
            const subSet = getSelectedSubFeatureIds(fId);
            subSet.forEach(sId => allActiveSubIds.push(sId));
        });

        document.getElementById('pdf_package_id').value = currentPackage.id;
        document.getElementById('pdf_feature_ids').value = selectedFeatureIds.join(',');
        document.getElementById('pdf_sub_feature_ids').value = allActiveSubIds.join(',');
    }

    function selectFeature(featureId) {
        if (!selectedFeatureIds.includes(featureId)) {
            selectedFeatureIds.push(featureId);
            const feat = allFeaturesData.find(f => f.id === featureId);
            if (feat && feat.sub_features) {
                selectedSubFeatureMap[featureId] = new Set(feat.sub_features.map(s => s.id));
            }
            renderKanban();
            showToast('Fitur ditambahkan ke pilihan.', 'success');
        }
    }

    function removeFeature(featureId) {
        selectedFeatureIds = selectedFeatureIds.filter(id => id !== featureId);
        delete selectedSubFeatureMap[featureId];
        delete expandedSubMap[featureId];
        renderKanban();
        showToast('Fitur dikembalikan ke daftar tersedia.', 'info');
    }

    function moveFeaturePosition(featureId, direction) {
        const idx = selectedFeatureIds.indexOf(featureId);
        if (idx === -1) return;
        const targetIdx = idx + direction;
        if (targetIdx < 0 || targetIdx >= selectedFeatureIds.length) return;

        selectedFeatureIds.splice(idx, 1);
        selectedFeatureIds.splice(targetIdx, 0, featureId);
        renderKanban();
        showToast('Posisi urutan diperbarui.', 'info');
    }

    function resetToPackageDefaults() {
        selectedFeatureIds = [...includedFeatureIds];
        selectedSubFeatureMap = {};
        includedFeatureIds.forEach(fId => {
            getSelectedSubFeatureIds(fId);
        });
        renderKanban();
        showToast('Pilihan fitur dikembalikan ke bawaan.', 'info');
    }

    function switchPackage(slug) {
        window.location.href = `{{ route('calculator') }}?package=${slug}`;
    }

    function setCategoryFilter(categoryId) {
        activeCategoryFilter = categoryId;
        document.querySelectorAll('.cat-pill').forEach(btn => {
            if (btn.dataset.category == categoryId) {
                btn.className = 'cat-pill active px-3 py-1 rounded-lg text-[11px] font-medium transition-all whitespace-nowrap bg-indigo-600 text-white font-mono';
            } else {
                btn.className = 'cat-pill px-3 py-1 rounded-lg text-[11px] font-medium transition-all whitespace-nowrap bg-[var(--tally-subtle-bg)] text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)] border border-[var(--tally-card-border)] font-mono';
            }
        });
        renderAvailableColumn();
    }

    function filterAvailableFeatures() {
        currentSearchTerm = document.getElementById('featureSearch').value.trim();
        renderAvailableColumn();
    }

    const blankDragImg = new Image();
    blankDragImg.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    let floatingDragBadge = null;

    document.addEventListener('dragover', (e) => {
        if (floatingDragBadge && floatingDragBadge.style.display !== 'none' && e.clientX > 0 && e.clientY > 0) {
            floatingDragBadge.style.left = `${e.clientX + 15}px`;
            floatingDragBadge.style.top = `${e.clientY + 15}px`;
        }
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.feature-card:not(.card-dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function handleDragStart(e, featureId, cardElement) {
        draggedFeatureId = featureId;
        e.dataTransfer.setData('text/plain', featureId);
        e.dataTransfer.effectAllowed = 'move';

        try {
            e.dataTransfer.setDragImage(blankDragImg, 0, 0);
        } catch (err) {}

        const feat = allFeaturesData.find(f => f.id === featureId);
        if (feat) {
            if (!floatingDragBadge) {
                floatingDragBadge = document.createElement('div');
                floatingDragBadge.id = 'floating-drag-badge';
                document.body.appendChild(floatingDragBadge);
            }

            const isDark = document.documentElement.classList.contains('dark');
            floatingDragBadge.className = 'fixed z-[999999] pointer-events-none px-4 py-3 rounded-2xl border border-indigo-500 shadow-2xl flex items-center gap-3 w-64 select-none font-mono';
            floatingDragBadge.style.cssText = `
                position: fixed;
                top: ${e.clientY + 15}px;
                left: ${e.clientX + 15}px;
                z-index: 999999;
                pointer-events: none;
                background-color: ${isDark ? '#07090e' : '#ffffff'} !important;
                color: ${isDark ? '#ffffff' : '#0f172a'} !important;
                opacity: 1 !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4), 0 0 20px rgba(99, 102, 241, 0.3) !important;
                transform: rotate(2deg);
                display: flex;
            `;

            floatingDragBadge.innerHTML = `
                <span class="text-2xl shrink-0">${feat.icon || '⚡'}</span>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold truncate font-sans">${feat.name}</div>
                    <div class="text-[11px] text-indigo-600 dark:text-indigo-400 font-mono font-bold">${formatRupiah(getFeatureCurrentPrice(feat))}</div>
                </div>
                <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-[10px] font-bold shadow-md shrink-0">Pindah</span>
            `;
        }

        setTimeout(() => {
            if (cardElement) cardElement.classList.add('card-dragging');
            document.getElementById('dropzone-available')?.classList.add('dropzone-active');
            document.getElementById('dropzone-selected')?.classList.add('dropzone-active');
        }, 0);
    }

    function handleDragEnd(e, cardElement) {
        draggedFeatureId = null;
        if (floatingDragBadge) {
            floatingDragBadge.style.display = 'none';
        }
        document.getElementById('drop-insertion-indicator')?.remove();
        document.querySelectorAll('.card-dragging').forEach(el => el.classList.remove('card-dragging'));
        document.getElementById('dropzone-available')?.classList.remove('dropzone-active', 'dropzone-hover');
        document.getElementById('dropzone-selected')?.classList.remove('dropzone-active', 'dropzone-hover');
    }

    function handleDragOver(e, dropzoneId) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        const dz = document.getElementById(dropzoneId);
        if (!dz) return;

        if (!dz.classList.contains('dropzone-hover')) {
            dz.classList.add('dropzone-hover');
        }

        if (dropzoneId === 'dropzone-selected') {
            const afterElement = getDragAfterElement(dz, e.clientY);
            let indicator = document.getElementById('drop-insertion-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'drop-insertion-indicator';
                indicator.className = 'h-1 rounded-full bg-indigo-500 shadow-lg shadow-indigo-500/50 my-1 pointer-events-none transition-all';
            }
            if (afterElement == null) {
                dz.appendChild(indicator);
            } else {
                dz.insertBefore(indicator, afterElement);
            }
        }
    }

    function handleDragLeave(e, dropzoneId) {
        const dz = document.getElementById(dropzoneId);
        if (dz && !dz.contains(e.relatedTarget)) {
            dz.classList.remove('dropzone-hover');
            document.getElementById('drop-insertion-indicator')?.remove();
        }
    }

    function handleDrop(e, targetColumn) {
        e.preventDefault();
        if (floatingDragBadge) {
            floatingDragBadge.style.display = 'none';
        }
        document.getElementById('drop-insertion-indicator')?.remove();
        document.getElementById('dropzone-available')?.classList.remove('dropzone-active', 'dropzone-hover');
        document.getElementById('dropzone-selected')?.classList.remove('dropzone-active', 'dropzone-hover');

        const featureId = parseInt(e.dataTransfer.getData('text/plain') || draggedFeatureId);
        if (!featureId) return;

        if (targetColumn === 'selected') {
            const dz = document.getElementById('dropzone-selected');
            const afterElement = getDragAfterElement(dz, e.clientY);
            let targetIndex = selectedFeatureIds.length;

            if (afterElement) {
                const afterId = parseInt(afterElement.dataset.id);
                const idx = selectedFeatureIds.indexOf(afterId);
                if (idx !== -1) {
                    targetIndex = idx;
                }
            }

            const oldIndex = selectedFeatureIds.indexOf(featureId);
            if (oldIndex !== -1) {
                selectedFeatureIds.splice(oldIndex, 1);
                if (oldIndex < targetIndex) {
                    targetIndex--;
                }
                selectedFeatureIds.splice(targetIndex, 0, featureId);
                renderKanban();
                showToast('Posisi urutan fitur diperbarui.', 'info');
            } else {
                selectedFeatureIds.splice(targetIndex, 0, featureId);
                const feat = allFeaturesData.find(f => f.id === featureId);
                if (feat && feat.sub_features) {
                    selectedSubFeatureMap[featureId] = new Set(feat.sub_features.map(s => s.id));
                }
                renderKanban();
                showToast('Fitur ditambahkan pada posisi yang dipilih.', 'success');
            }
        } else if (targetColumn === 'available') {
            if (selectedFeatureIds.includes(featureId)) {
                removeFeature(featureId);
            }
        }
    }

    function switchMobileTab(tab) {
        const colAvailable = document.getElementById('col-available');
        const colSelected = document.getElementById('col-selected');
        const colSummary = document.getElementById('col-summary');

        const btnAvailable = document.getElementById('tab-btn-available');
        const btnSelected = document.getElementById('tab-btn-selected');
        const btnSummary = document.getElementById('tab-btn-summary');

        colAvailable.classList.add('hidden');
        colSelected.classList.add('hidden');
        colSummary.classList.add('hidden');

        [btnAvailable, btnSelected, btnSummary].forEach(btn => {
            btn.className = 'flex-1 py-2 text-xs font-mono font-bold rounded-xl transition-all text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]';
        });

        if (tab === 'available') {
            colAvailable.classList.remove('hidden');
            btnAvailable.className = 'flex-1 py-2 text-xs font-mono font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md';
        } else if (tab === 'selected') {
            colSelected.classList.remove('hidden');
            btnSelected.className = 'flex-1 py-2 text-xs font-mono font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md';
        } else if (tab === 'summary') {
            colSummary.classList.remove('hidden');
            btnSummary.className = 'flex-1 py-2 text-xs font-mono font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md';
        }
    }

    function generatePDF() {
        const form = document.getElementById('pdfForm');
        document.getElementById('pdf_package_id').value = currentPackage.id;
        document.getElementById('pdf_feature_ids').value = selectedFeatureIds.join(',');

        const allActiveSubIds = [];
        selectedFeatureIds.forEach(fId => {
            const subSet = getSelectedSubFeatureIds(fId);
            subSet.forEach(sId => allActiveSubIds.push(sId));
        });
        document.getElementById('pdf_sub_feature_ids').value = allActiveSubIds.join(',');

        form.submit();
    }
</script>
@endpush
