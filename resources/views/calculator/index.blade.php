@extends('layouts.app')

@section('title', 'Konfigurasi Fitur — ' . $selectedPackage->name)

@push('styles')
<style>
    /* Sleek Drag & Drop Animations */
    .card-dragging {
        opacity: 0.25 !important;
        transform: scale(0.96) !important;
        border-style: dashed !important;
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, 0.05) !important;
        box-shadow: none !important;
    }

    .dropzone-active {
        border-color: rgba(99, 102, 241, 0.5) !important;
        background: rgba(99, 102, 241, 0.03) !important;
        transition: all 0.2s ease-in-out;
    }

    .dropzone-hover {
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, 0.1) !important;
        box-shadow: 0 0 30px rgba(99, 102, 241, 0.25), inset 0 0 15px rgba(99, 102, 241, 0.1) !important;
        transform: scale(1.003);
    }

    .feature-card {
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .feature-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    
    <!-- Top Header & Package Switcher Bar -->
    <div class="p-4 sm:p-6 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl backdrop-blur-xl flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    Paket {{ $selectedPackage->name }}
                </span>
                <span class="text-xs text-slate-400 font-mono">
                    Rp {{ number_format($selectedPackage->price, 0, ',', '.') }} / {{ $selectedPackage->period }}
                </span>
            </div>
            <h1 class="text-lg sm:text-xl font-extrabold text-white tracking-tight">
                Website Feature Configurator & Price Calculator
            </h1>
            <p class="text-xs text-slate-400">
                Pilih dan kustomisasi sub-fitur website Anda secara interaktif dengan Drag & Drop.
            </p>
        </div>

        <!-- Package Switcher & Reset Button -->
        <div class="flex items-center gap-2 shrink-0">
            <div class="relative">
                <select onchange="switchPackage(this.value)" class="appearance-none pl-3 pr-8 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach ($allPackages as $pkg)
                        <option value="{{ $pkg->slug }}" {{ $pkg->id === $selectedPackage->id ? 'selected' : '' }}>
                            Paket {{ $pkg->name }} (Rp {{ number_format($pkg->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <button type="button" onclick="resetToPackageDefaults()" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white text-xs font-medium transition-colors flex items-center gap-1.5" title="Reset ke Fitur Bawaan Paket">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Default</span>
            </button>
        </div>
    </div>

    <!-- Mobile View Tab Switcher (Visible on < lg screens) -->
    <div class="flex lg:hidden rounded-2xl bg-slate-900 border border-slate-800 p-1 gap-1">
        <button type="button" onclick="switchMobileTab('available')" id="tab-btn-available" class="flex-1 py-2.5 text-xs font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md">
            Fitur Tersedia (<span id="mobile-count-available">0</span>)
        </button>
        <button type="button" onclick="switchMobileTab('selected')" id="tab-btn-selected" class="flex-1 py-2.5 text-xs font-bold rounded-xl transition-all text-slate-400 hover:text-white">
            Fitur Dipilih (<span id="mobile-count-selected">0</span>)
        </button>
        <button type="button" onclick="switchMobileTab('summary')" id="tab-btn-summary" class="flex-1 py-2.5 text-xs font-bold rounded-xl transition-all text-slate-400 hover:text-white">
            Ringkasan Biaya
        </button>
    </div>

    <!-- 3-Column Kanban Board Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- COLUMN 1: FITUR TERSEDIA (4 cols) -->
        <div id="col-available" class="lg:col-span-4 space-y-4">
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Fitur Tersedia</h2>
                    </div>
                    <span id="badge-count-available" class="text-[11px] px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 font-mono">
                        0 Fitur
                    </span>
                </div>

                <p class="text-xs text-slate-400">
                    Tarik kartu atau klik <strong>+ Tambah</strong> untuk menambahkan fitur ke konfigurasi.
                </p>

                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="featureSearch" oninput="filterAvailableFeatures()" placeholder="Cari nama atau deskripsi fitur..."
                        class="w-full pl-9 pr-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <!-- Category Filter Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-thin">
                    <button type="button" onclick="setCategoryFilter('')" data-category="" class="cat-pill active px-3 py-1 rounded-lg text-[11px] font-semibold transition-all whitespace-nowrap bg-indigo-600 text-white">
                        Semua
                    </button>
                    @foreach ($categories as $cat)
                        <button type="button" onclick="setCategoryFilter({{ $cat->id }})" data-category="{{ $cat->id }}" class="cat-pill px-3 py-1 rounded-lg text-[11px] font-semibold transition-all whitespace-nowrap bg-slate-800 text-slate-400 hover:text-white">
                            {{ $cat->icon }} {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Available Features Dropzone & List -->
            <div id="dropzone-available" class="space-y-3 min-h-[400px] p-2 rounded-2xl bg-slate-900/40 border border-dashed border-slate-800 transition-all duration-200"
                ondragover="handleDragOver(event, 'dropzone-available')" ondragleave="handleDragLeave(event, 'dropzone-available')" ondrop="handleDrop(event, 'available')">
                <!-- Cards will be populated by JavaScript -->
            </div>
        </div>

        <!-- COLUMN 2: FITUR DIPILIH (5 cols) -->
        <div id="col-selected" class="hidden lg:block lg:col-span-5 space-y-4">
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-indigo-500/30 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Fitur Dipilih</h2>
                    </div>
                    <span id="badge-count-selected" class="text-[11px] px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 font-mono border border-indigo-500/30">
                        0 Fitur
                    </span>
                </div>
                <p class="text-xs text-slate-400">
                    Kustomisasi sub-fitur di bawah. Menonaktifkan sub-fitur langsung mengurangi harga secara realtime di ringkasan sebelah kanan.
                </p>
            </div>

            <!-- Selected Features Dropzone & List -->
            <div id="dropzone-selected" class="space-y-3 min-h-[400px] p-2 rounded-2xl bg-indigo-950/10 border-2 border-dashed border-indigo-500/30 transition-all duration-200"
                ondragover="handleDragOver(event, 'dropzone-selected')" ondragleave="handleDragLeave(event, 'dropzone-selected')" ondrop="handleDrop(event, 'selected')">
                <!-- Cards will be populated by JavaScript -->
            </div>
        </div>

        <!-- COLUMN 3: RINGKASAN BIAYA & GENERATE PDF (3 cols) -->
        <div id="col-summary" class="hidden lg:block lg:col-span-3 space-y-4 sticky top-20">
            <div class="p-5 rounded-2xl bg-slate-900/95 border border-slate-800 shadow-2xl backdrop-blur-xl space-y-5">
                
                <div class="border-b border-slate-800 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Konfigurasi</h3>
                    <p class="text-sm font-bold text-white mt-1">Paket {{ $selectedPackage->name }}</p>
                </div>

                <!-- Price Breakdown -->
                <div class="space-y-3 text-xs">
                    <!-- Base Package Price -->
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Harga Paket Dasar</span>
                        <span class="font-bold text-white font-mono">
                            Rp {{ number_format($selectedPackage->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Included Package Deductions (Visible when sub-features of included features are unchecked) -->
                    <div id="summary-deduction-row" class="hidden flex items-center justify-between text-emerald-400 font-semibold py-1">
                        <div class="flex items-center gap-1">
                            <span>Potongan Sub-Fitur Paket</span>
                        </div>
                        <span id="summary-deduction-total" class="font-mono text-emerald-400 font-bold">- Rp 0</span>
                    </div>

                    <!-- Adjusted Package Subtotal (Visible when deduction > 0) -->
                    <div id="summary-adjusted-package-row" class="hidden flex items-center justify-between text-slate-300 pt-1 border-t border-slate-800/60">
                        <span class="text-[11px] text-slate-400">Paket Disesuaikan</span>
                        <span id="summary-adjusted-package-price" class="font-bold text-slate-200 font-mono">Rp 0</span>
                    </div>

                    <!-- Additional Features List -->
                    <div class="pt-2 border-t border-slate-800/80">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-2">
                            Fitur Tambahan Terpilih:
                        </span>
                        <div id="summary-additional-list" class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-slate-300 scrollbar-thin">
                            <!-- Additional feature items will appear here -->
                        </div>
                    </div>

                    <!-- Additional Total Subtotal -->
                    <div class="pt-2 border-t border-slate-800 flex items-center justify-between text-slate-300">
                        <span>Total Fitur Tambahan</span>
                        <span id="summary-additional-total" class="font-bold text-indigo-300 font-mono">
                            Rp 0
                        </span>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="pt-4 border-t-2 border-slate-800 space-y-1 bg-slate-950/40 -mx-5 -mb-5 p-5 rounded-b-2xl">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Estimasi Biaya</span>
                    <div id="summary-total-price" class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 font-mono">
                        Rp {{ number_format($selectedPackage->price, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-slate-500">Estimasi biaya sewa per {{ $selectedPackage->period }}. Belum termasuk PPN jika berlaku.</p>

                    <!-- Generate PDF Button -->
                    <div class="pt-4">
                        <button type="button" onclick="generatePDF()" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 hover:from-indigo-500 hover:to-purple-600 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all duration-200 flex items-center justify-center gap-2 group">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Generate PDF Estimasi</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Hidden PDF Form for Form Submission -->
<form id="pdfForm" method="POST" action="{{ route('calculator.pdf') }}" target="_blank" class="hidden">
    @csrf
    <input type="hidden" name="package_id" id="pdf_package_id" value="{{ $selectedPackage->id }}">
    <input type="hidden" name="feature_ids" id="pdf_feature_ids" value="">
    <input type="hidden" name="sub_feature_ids" id="pdf_sub_feature_ids" value="">
</form>

@endsection

@push('scripts')
<script>
    // Master data passed from Laravel Backend
    const currentPackage = {
        id: {{ $selectedPackage->id }},
        name: "{{ $selectedPackage->name }}",
        slug: "{{ $selectedPackage->slug }}",
        price: {{ (float) $selectedPackage->price }},
        period: "{{ $selectedPackage->period }}"
    };

    const allFeaturesData = @json($featuresData);
    const includedFeatureIds = @json($includedFeatureIds);

    // Frontend State: IDs of features in Selected Column
    let selectedFeatureIds = [...includedFeatureIds];
    
    // Map of selected sub-feature IDs per feature: { [featureId]: Set([subId1, subId2]) }
    let selectedSubFeatureMap = {};

    // Track which feature cards have expanded sub-features accordion
    let expandedSubMap = {};

    let activeCategoryFilter = '';
    let currentSearchTerm = '';
    let draggedFeatureId = null;

    // Helper: Initialize or retrieve selected sub-feature IDs for a feature
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

    // Helper: Calculate current price of a feature based on selected sub-features
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

    // Format Rupiah Helper
    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    // Initialize UI on Load
    document.addEventListener('DOMContentLoaded', () => {
        includedFeatureIds.forEach(fId => {
            getSelectedSubFeatureIds(fId);
        });
        renderKanban();
    });

    // Render Both Columns and Summary
    function renderKanban() {
        renderAvailableColumn();
        renderSelectedColumn();
        updateRealtimeCalculation();
    }

    // 1. Render Available Column
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
                <div class="p-8 text-center rounded-xl bg-slate-800/20 border border-dashed border-slate-800 text-slate-400 text-xs">
                    ${availableFeatures.length === 0 ? 'Semua fitur sudah dipilih ke kolom tengah.' : 'Tidak ada fitur yang cocok dengan filter pencarian.'}
                </div>
            `;
            return;
        }

        filtered.forEach(feature => {
            const isIncludedInPackage = includedFeatureIds.includes(feature.id);
            const card = document.createElement('div');
            card.className = 'feature-card p-4 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 shadow-md cursor-grab active:cursor-grabbing hover:shadow-indigo-500/10 select-none';
            card.draggable = true;
            card.dataset.id = feature.id;

            card.addEventListener('dragstart', (e) => handleDragStart(e, feature.id, card));
            card.addEventListener('dragend', (e) => handleDragEnd(e, card));

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <span class="text-xl shrink-0 mt-0.5">${feature.icon || '⚡'}</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-white truncate">${feature.name}</h4>
                            <span class="inline-block text-[10px] text-slate-400 font-medium">${feature.category_name}</span>
                        </div>
                    </div>
                    <button type="button" onclick="selectFeature(${feature.id})" class="px-2.5 py-1 rounded-lg bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white text-xs font-semibold border border-indigo-500/30 transition-all shrink-0">
                        + Tambah
                    </button>
                </div>

                ${feature.description ? `<p class="text-[11px] text-slate-400 mt-2 line-clamp-2">${feature.description}</p>` : ''}

                <div class="mt-3 pt-2.5 border-t border-slate-700/50 flex items-center justify-between">
                    <span class="text-xs font-bold text-white font-mono">
                        ${formatRupiah(feature.price)}
                    </span>
                    ${isIncludedInPackage ? `
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 font-semibold">
                            Bawaan Paket
                        </span>
                    ` : `
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-slate-700/50 text-slate-400 font-medium">
                            Fitur Tambahan
                        </span>
                    `}
                </div>

                ${feature.sub_features && feature.sub_features.length > 0 ? `
                    <div class="mt-2.5 pt-2 border-t border-slate-700/40">
                        <button type="button" onclick="toggleSubFeaturesAvailable(${feature.id})" class="text-[10px] font-semibold text-slate-400 hover:text-indigo-300 flex items-center justify-between w-full">
                            <span>${feature.sub_features.length} Rincian Sub Fitur</span>
                            <span id="sub-avail-arrow-${feature.id}">▼</span>
                        </button>
                        <div id="sub-avail-list-${feature.id}" class="hidden mt-1.5 p-2 rounded-lg bg-slate-900/60 border border-slate-800 space-y-1">
                            ${feature.sub_features.map(sub => `
                                <div class="flex items-center justify-between text-[10px] text-slate-300 py-0.5">
                                    <span class="truncate pr-2">&bull; ${sub.name}</span>
                                    <span class="font-mono text-slate-400 font-medium shrink-0">${formatRupiah(sub.price)}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            `;

            dropzone.appendChild(card);
        });
    }

    // 2. Render Selected Column
    function renderSelectedColumn() {
        const dropzone = document.getElementById('dropzone-selected');
        dropzone.innerHTML = '';

        const selectedFeatures = selectedFeatureIds.map(id => allFeaturesData.find(f => f.id === id)).filter(Boolean);

        document.getElementById('badge-count-selected').innerText = `${selectedFeatures.length} Fitur`;
        document.getElementById('mobile-count-selected').innerText = selectedFeatures.length;

        if (selectedFeatures.length === 0) {
            dropzone.innerHTML = `
                <div class="p-8 text-center rounded-xl bg-slate-800/20 border border-dashed border-indigo-500/30 text-slate-400 text-xs">
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
            card.className = 'feature-card p-4 rounded-xl bg-slate-800/90 border border-indigo-500/30 shadow-md transition-all cursor-grab active:cursor-grabbing select-none';
            card.draggable = true;
            card.dataset.id = feature.id;

            card.addEventListener('dragstart', (e) => handleDragStart(e, feature.id, card));
            card.addEventListener('dragend', (e) => handleDragEnd(e, card));

            let statusBadgeHtml = '';
            if (isIncludedInPackage) {
                if (currentFeaturePrice < defaultFullPrice) {
                    const deduction = defaultFullPrice - currentFeaturePrice;
                    statusBadgeHtml = `
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-semibold">
                            ✓ Paket (Potongan -${formatRupiah(deduction)})
                        </span>
                    `;
                } else {
                    statusBadgeHtml = `
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-semibold">
                            ✓ Termasuk Paket (Rp 0 Tambahan)
                        </span>
                    `;
                }
            } else {
                statusBadgeHtml = `
                    <span class="text-[10px] px-2 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-bold">
                        + Tambahan Biaya
                    </span>
                `;
            }

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <span class="text-xl shrink-0 mt-0.5">${feature.icon || '⚡'}</span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-white">${feature.name}</h4>
                            <span class="inline-block text-[10px] text-indigo-300 font-medium">${feature.category_name}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <div class="flex items-center bg-slate-900/60 rounded-lg border border-slate-700/50 p-0.5" title="Pindah Posisi Urutan">
                            <button type="button" onclick="moveFeaturePosition(${feature.id}, -1)" class="w-5 h-5 rounded hover:bg-slate-700 text-slate-400 hover:text-white text-[10px] flex items-center justify-center transition-colors" title="Pindah Ke Atas">
                                ▲
                            </button>
                            <button type="button" onclick="moveFeaturePosition(${feature.id}, 1)" class="w-5 h-5 rounded hover:bg-slate-700 text-slate-400 hover:text-white text-[10px] flex items-center justify-center transition-colors" title="Pindah Ke Bawah">
                                ▼
                            </button>
                        </div>
                        <button type="button" onclick="removeFeature(${feature.id})" class="w-6 h-6 rounded-lg bg-rose-950/50 hover:bg-rose-900 text-rose-300 text-xs font-bold flex items-center justify-center border border-rose-500/30 shrink-0 transition-colors" title="Hapus Fitur">
                            ×
                        </button>
                    </div>
                </div>

                <div class="mt-3 pt-2.5 border-t border-slate-700/60 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-white font-mono">
                            ${formatRupiah(currentFeaturePrice)}
                        </span>
                        ${hasSubFeatures && currentFeaturePrice !== defaultFullPrice ? `
                            <span class="text-[10px] text-slate-500 line-through font-mono ml-1">
                                ${formatRupiah(defaultFullPrice)}
                            </span>
                        ` : ''}
                    </div>
                    ${statusBadgeHtml}
                </div>

                ${hasSubFeatures ? `
                    <div class="mt-3 pt-2.5 border-t border-slate-700/40">
                        <div class="flex items-center justify-between">
                            <button type="button" onclick="toggleSubFeatures(${feature.id})" class="text-[11px] font-semibold text-slate-300 hover:text-indigo-300 flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded bg-indigo-500/20 text-indigo-300 font-mono text-[10px] font-bold">
                                    ${selectedSubSet.size}/${feature.sub_features.length}
                                </span>
                                <span>Kustomisasi Sub Fitur</span>
                                <span id="sub-arrow-${feature.id}" class="text-[10px] text-slate-400">${isExpanded ? '▲' : '▼'}</span>
                            </button>
                            <div class="flex items-center gap-1.5 text-[10px]">
                                <button type="button" onclick="toggleAllSubFeatures(${feature.id}, true)" class="text-indigo-400 hover:text-indigo-300 font-semibold hover:underline">
                                    Semua
                                </button>
                                <span class="text-slate-600">&bull;</span>
                                <button type="button" onclick="toggleAllSubFeatures(${feature.id}, false)" class="text-rose-400 hover:text-rose-300 font-semibold hover:underline">
                                    Batal
                                </button>
                            </div>
                        </div>

                        <div id="sub-list-${feature.id}" class="${isExpanded ? '' : 'hidden'} mt-2 p-2 rounded-xl bg-slate-900/80 border border-slate-700/60 space-y-1">
                            ${feature.sub_features.map(sub => {
                                const isSubSelected = selectedSubSet.has(sub.id);
                                return `
                                    <label class="flex items-center justify-between p-1.5 rounded-lg cursor-pointer transition-colors ${isSubSelected ? 'bg-slate-800/80 text-white border border-indigo-500/20' : 'bg-slate-900/30 text-slate-500 hover:bg-slate-800/40'}">
                                        <div class="flex items-center gap-2 min-w-0 pr-2">
                                            <input type="checkbox" onchange="toggleSubFeature(${feature.id}, ${sub.id}, this.checked)" ${isSubSelected ? 'checked' : ''} class="w-3.5 h-3.5 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500 cursor-pointer shrink-0">
                                            <span class="text-[11px] truncate ${isSubSelected ? 'font-medium text-slate-200' : 'text-slate-500 line-through'}">
                                                ${sub.name}
                                            </span>
                                        </div>
                                        <span class="font-mono text-[11px] shrink-0 ${isSubSelected ? 'font-bold text-slate-200' : 'text-slate-600 line-through'}">
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

    // Toggle individual sub-feature selection
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

    // Toggle all sub-features of a feature
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

    // Toggle Expand/Collapse Sub-features (Selected Column)
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

    // Toggle Expand/Collapse Sub-features (Available Column)
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

    // 3. Update Realtime Calculation
    function updateRealtimeCalculation() {
        const summaryList = document.getElementById('summary-additional-list');
        const summaryAddTotal = document.getElementById('summary-additional-total');
        const summaryTotalPrice = document.getElementById('summary-total-price');

        let additionalTotal = 0;
        let additionalItems = [];
        let totalIncludedDeduction = 0;

        // 1. Calculate deductions from default included features
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

        // 2. Calculate additional features total
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

        // 3. Render Deduction Rows in Summary
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

        // 4. Render Additional Items in Summary
        if (additionalItems.length === 0) {
            summaryList.innerHTML = '<p class="text-slate-500 italic py-1">Tidak ada fitur tambahan berbayar.</p>';
        } else {
            summaryList.innerHTML = additionalItems.map(item => `
                <div class="flex items-center justify-between py-1 border-b border-slate-800/50 last:border-0">
                    <div class="min-w-0 pr-2">
                        <span class="truncate block text-slate-200">${item.name}</span>
                        ${item.totalSub > 0 ? `<span class="text-[10px] text-slate-400 font-mono">${item.subCount}/${item.totalSub} sub-fitur</span>` : ''}
                    </div>
                    <span class="font-semibold text-slate-200 shrink-0 font-mono">${formatRupiah(item.price)}</span>
                </div>
            `).join('');
        }

        const grandTotal = adjustedPackagePrice + additionalTotal;

        summaryAddTotal.innerText = formatRupiah(additionalTotal);
        summaryTotalPrice.innerText = formatRupiah(grandTotal);

        // Collect all active sub-feature IDs across all selected features
        const allActiveSubIds = [];
        selectedFeatureIds.forEach(fId => {
            const subSet = getSelectedSubFeatureIds(fId);
            subSet.forEach(sId => allActiveSubIds.push(sId));
        });

        // Update Hidden PDF Form Values
        document.getElementById('pdf_package_id').value = currentPackage.id;
        document.getElementById('pdf_feature_ids').value = selectedFeatureIds.join(',');
        document.getElementById('pdf_sub_feature_ids').value = allActiveSubIds.join(',');
    }

    // Select Feature Action
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

    // Remove Feature Action
    function removeFeature(featureId) {
        selectedFeatureIds = selectedFeatureIds.filter(id => id !== featureId);
        delete selectedSubFeatureMap[featureId];
        delete expandedSubMap[featureId];
        renderKanban();
        showToast('Fitur dikembalikan ke daftar tersedia.', 'info');
    }

    // Move Feature Position Up (-1) or Down (+1) manually
    function moveFeaturePosition(featureId, direction) {
        const idx = selectedFeatureIds.indexOf(featureId);
        if (idx === -1) return;
        const targetIdx = idx + direction;
        if (targetIdx < 0 || targetIdx >= selectedFeatureIds.length) return;

        selectedFeatureIds.splice(idx, 1);
        selectedFeatureIds.splice(targetIdx, 0, featureId);
        renderKanban();
        showToast('Posisi urutan fitur diperbarui.', 'info');
    }

    // Reset to Package Defaults
    function resetToPackageDefaults() {
        selectedFeatureIds = [...includedFeatureIds];
        selectedSubFeatureMap = {};
        includedFeatureIds.forEach(fId => {
            getSelectedSubFeatureIds(fId);
        });
        renderKanban();
        showToast('Pilihan fitur dikembalikan ke bawaan paket.', 'info');
    }

    // Switch Package
    function switchPackage(slug) {
        window.location.href = `{{ route('calculator') }}?package=${slug}`;
    }

    // Filter Handlers
    function setCategoryFilter(categoryId) {
        activeCategoryFilter = categoryId;
        document.querySelectorAll('.cat-pill').forEach(btn => {
            if (btn.dataset.category == categoryId) {
                btn.className = 'cat-pill active px-3 py-1 rounded-lg text-[11px] font-semibold transition-all whitespace-nowrap bg-indigo-600 text-white';
            } else {
                btn.className = 'cat-pill px-3 py-1 rounded-lg text-[11px] font-semibold transition-all whitespace-nowrap bg-slate-800 text-slate-400 hover:text-white';
            }
        });
        renderAvailableColumn();
    }

    function filterAvailableFeatures() {
        currentSearchTerm = document.getElementById('featureSearch').value.trim();
        renderAvailableColumn();
    }

    // 1x1 Transparent pixel to suppress native browser semi-transparent ghost
    const blankDragImg = new Image();
    blankDragImg.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    let floatingDragBadge = null;

    // Track mouse movement during drag across the entire window
    document.addEventListener('dragover', (e) => {
        if (floatingDragBadge && floatingDragBadge.style.display !== 'none' && e.clientX > 0 && e.clientY > 0) {
            floatingDragBadge.style.left = `${e.clientX + 15}px`;
            floatingDragBadge.style.top = `${e.clientY + 15}px`;
        }
    });

    // Helper: Find element after which to insert dragged card based on mouse Y position
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

    // Drag and Drop Event Handlers with 100% Solid Non-Transparent Custom Floating Preview
    function handleDragStart(e, featureId, cardElement) {
        draggedFeatureId = featureId;
        e.dataTransfer.setData('text/plain', featureId);
        e.dataTransfer.effectAllowed = 'move';

        // 1. Suppress native semi-transparent ghost image completely
        try {
            e.dataTransfer.setDragImage(blankDragImg, 0, 0);
        } catch (err) {}

        // 2. Create or reuse 100% Solid Non-Transparent Floating Badge
        const feat = allFeaturesData.find(f => f.id === featureId);
        if (feat) {
            if (!floatingDragBadge) {
                floatingDragBadge = document.createElement('div');
                floatingDragBadge.id = 'floating-drag-badge';
                document.body.appendChild(floatingDragBadge);
            }

            floatingDragBadge.className = 'fixed z-[999999] pointer-events-none px-4 py-3 rounded-2xl border-2 border-indigo-500 shadow-2xl flex items-center gap-3 w-64 select-none';
            floatingDragBadge.style.cssText = `
                position: fixed;
                top: ${e.clientY + 15}px;
                left: ${e.clientX + 15}px;
                z-index: 999999;
                pointer-events: none;
                background-color: #0f172a !important;
                opacity: 1 !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.95), 0 0 25px rgba(99, 102, 241, 0.6) !important;
                transform: rotate(2deg);
                display: flex;
            `;

            floatingDragBadge.innerHTML = `
                <span class="text-2xl shrink-0">${feat.icon || '⚡'}</span>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-bold text-white truncate">${feat.name}</div>
                    <div class="text-[11px] text-indigo-300 font-mono font-bold">${formatRupiah(getFeatureCurrentPrice(feat))}</div>
                </div>
                <span class="px-2 py-0.5 rounded bg-indigo-600 text-white text-[10px] font-bold shadow-md shrink-0">Pindah</span>
            `;
        }

        // 3. Set source card style & activate dropzones
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

        // Render sleek insertion line indicator
        if (dropzoneId === 'dropzone-selected') {
            const afterElement = getDragAfterElement(dz, e.clientY);
            let indicator = document.getElementById('drop-insertion-indicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'drop-insertion-indicator';
                indicator.className = 'h-1.5 rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 shadow-lg shadow-indigo-500/50 my-1 pointer-events-none transition-all';
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
                // Reordering inside Selected column (Move up or down anywhere!)
                selectedFeatureIds.splice(oldIndex, 1);
                if (oldIndex < targetIndex) {
                    targetIndex--;
                }
                selectedFeatureIds.splice(targetIndex, 0, featureId);
                renderKanban();
                showToast('Urutan posisi fitur diperbarui.', 'info');
            } else {
                // Dragging from Available column into Selected column at target position
                selectedFeatureIds.splice(targetIndex, 0, featureId);
                const feat = allFeaturesData.find(f => f.id === featureId);
                if (feat && feat.sub_features) {
                    selectedSubFeatureMap[featureId] = new Set(feat.sub_features.map(s => s.id));
                }
                renderKanban();
                showToast('Fitur disisipkan pada posisi yang dipilih.', 'success');
            }
        } else if (targetColumn === 'available') {
            if (selectedFeatureIds.includes(featureId)) {
                removeFeature(featureId);
            }
        }
    }

    // Mobile Tab Switcher
    function switchMobileTab(tab) {
        const colAvailable = document.getElementById('col-available');
        const colSelected = document.getElementById('col-selected');
        const colSummary = document.getElementById('col-summary');

        const btnAvailable = document.getElementById('tab-btn-available');
        const btnSelected = document.getElementById('tab-btn-selected');
        const btnSummary = document.getElementById('tab-btn-summary');

        // Hide all columns on mobile
        colAvailable.classList.add('hidden');
        colSelected.classList.add('hidden');
        colSummary.classList.add('hidden');

        // Reset button styles
        [btnAvailable, btnSelected, btnSummary].forEach(btn => {
            btn.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl transition-all text-slate-400 hover:text-white';
        });

        if (tab === 'available') {
            colAvailable.classList.remove('hidden');
            btnAvailable.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md';
        } else if (tab === 'selected') {
            colSelected.classList.remove('hidden');
            btnSelected.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md';
        } else if (tab === 'summary') {
            colSummary.classList.remove('hidden');
            btnSummary.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-md';
        }
    }

    // Generate PDF Submission
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
