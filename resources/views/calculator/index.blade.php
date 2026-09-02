@extends('layouts.app')

@section('title', 'Kanban Project Configurator')

@section('content')
<div class="py-6 sm:py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 select-none">
    <!-- Top Header Bar with Package Switcher & Breadcrumb -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-xl shadow-lg shadow-indigo-600/20 shrink-0">
                🎛️
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-lg font-bold text-white tracking-tight">E-Commerce Project Configurator</h1>
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Kanban</span>
                </div>
                <p class="text-xs text-slate-400">Tarik kartu untuk menyusun & <span class="text-indigo-300 font-semibold">atur urutan fitur</span> — <span class="text-emerald-400 font-semibold">biaya bergerak dinamis real-time</span></p>
            </div>
        </div>

        <!-- Package Switcher & Reset -->
        <div class="flex items-center gap-2.5">
            <label for="package-switch" class="text-xs font-semibold text-slate-400 hidden sm:inline">Paket Dasar:</label>
            <select id="package-switch" onchange="switchPackage(this.value)"
                class="px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach ($allPackages as $pkg)
                    <option value="{{ $pkg->slug }}" {{ $selectedPackage->id === $pkg->id ? 'selected' : '' }}>
                        {{ $pkg->name }} — {{ $pkg->formatted_price }}
                    </option>
                @endforeach
            </select>
            <button type="button" onclick="resetToPackageDefaults()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-slate-200 text-xs transition-colors" title="Reset Fitur ke Default Paket">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile View Tab Switcher (Visible on small screens) -->
    <div class="lg:hidden grid grid-cols-3 gap-2 p-1.5 rounded-2xl bg-slate-900 border border-slate-800 text-xs font-bold">
        <button type="button" onclick="switchMobileTab('available')" id="tab-btn-available"
            class="py-2.5 px-2 rounded-xl transition-all bg-indigo-600 text-white shadow">
            1. Fitur Tersedia
        </button>
        <button type="button" onclick="switchMobileTab('selected')" id="tab-btn-selected"
            class="py-2.5 px-2 rounded-xl transition-all text-slate-400 hover:text-white flex items-center justify-center gap-1">
            <span>2. Terpilih</span>
            <span id="mobile-selected-count" class="px-1.5 py-0.2 rounded-full bg-slate-800 text-[10px] font-mono text-indigo-300">0</span>
        </button>
        <button type="button" onclick="switchMobileTab('summary')" id="tab-btn-summary"
            class="py-2.5 px-2 rounded-xl transition-all text-slate-400 hover:text-white">
            3. Ringkasan
        </button>
    </div>

    <!-- 3-Column Desktop Kanban Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- COLUMN 1: AVAILABLE FEATURES (Fitur Tersedia) -->
        <div id="col-available" class="lg:col-span-4 bg-slate-900/90 border border-slate-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex flex-col space-y-4 max-h-[840px]">
            <!-- Column Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-white">Fitur Tersedia</h2>
                </div>
                <span id="available-count-badge" class="px-2 py-0.5 rounded-full text-[11px] font-mono font-bold bg-slate-800 text-slate-300 border border-slate-700">
                    39 Fitur
                </span>
            </div>

            <!-- Search & Filter Controls -->
            <div class="space-y-2.5">
                <div class="relative">
                    <input type="text" id="feature-search" oninput="filterAvailableFeatures()" placeholder="Cari fitur (misal: voucher, checkout, ongkir)..."
                        class="w-full pl-9 pr-8 py-2 bg-slate-800/90 border border-slate-700/80 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <button type="button" id="clear-search-btn" onclick="clearSearch()" class="hidden absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Category Horizontal Scroll Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs scrollbar-thin">
                    <button type="button" onclick="selectCategoryFilter('all')" data-cat="all"
                        class="cat-filter-btn px-2.5 py-1 rounded-lg font-semibold whitespace-nowrap bg-indigo-600 text-white shadow-sm transition-all text-[11px]">
                        Semua Kategori
                    </button>
                    @foreach ($categories as $cat)
                        <button type="button" onclick="selectCategoryFilter({{ $cat->id }})" data-cat="{{ $cat->id }}"
                            class="cat-filter-btn px-2.5 py-1 rounded-lg font-medium whitespace-nowrap bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all text-[11px]">
                            {{ $cat->icon }} {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Features Cards List -->
            <div id="available-cards-container" 
                class="flex-1 overflow-y-auto space-y-2.5 pr-1 min-h-[320px] transition-all rounded-xl p-1 relative">
                <!-- Dynamic cards populated via JavaScript -->
            </div>
        </div>

        <!-- COLUMN 2: SELECTED FEATURES (Fitur Terpilih - Bisa Diatur Urutan) -->
        <div id="col-selected" class="hidden lg:flex lg:col-span-5 bg-slate-900/90 border border-slate-800/80 rounded-2xl p-4 sm:p-5 shadow-xl flex-col space-y-4 max-h-[840px]">
            <!-- Column Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-white">Fitur Terpilih</h2>
                    <span id="selected-count-badge" class="px-2 py-0.5 rounded-full text-[11px] font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        0 Fitur
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-slate-500 hidden sm:inline">↕ Geser untuk atur urutan</span>
                    <button type="button" onclick="clearSelectedFeatures()" class="text-xs text-rose-400 hover:text-rose-300 font-semibold hover:underline">
                        Kosongkan
                    </button>
                </div>
            </div>

            <!-- Selected Cards Container / Dropzone -->
            <div id="selected-cards-container"
                class="flex-1 overflow-y-auto space-y-3 pr-1 min-h-[350px] transition-all rounded-xl p-1 relative">
                <!-- Dynamic selected items populated via JavaScript -->
            </div>
        </div>

        <!-- COLUMN 3: PROJECT SUMMARY (Sticky Ringkasan & Total) -->
        <div id="col-summary" class="hidden lg:block lg:col-span-3 space-y-4">
            <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800/90 rounded-2xl p-5 shadow-2xl space-y-5 sticky top-24">
                <!-- Summary Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400">Ringkasan Estimasi</span>
                        <h3 class="text-base font-extrabold text-white">Project Summary</h3>
                    </div>
                    <span class="text-xl">📊</span>
                </div>

                <!-- Package Info Card -->
                <div class="p-3.5 rounded-xl bg-slate-800/70 border border-slate-700/60 space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Paket Dasar:</span>
                        <span id="summary-package-name" class="font-bold text-white">{{ $selectedPackage->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Harga Dasar:</span>
                        <span id="summary-package-price" class="font-mono font-bold text-emerald-400">{{ $selectedPackage->formatted_price }}</span>
                    </div>
                    <div class="text-[10px] text-slate-400 pt-1 border-t border-slate-700/50">
                        Periode: <strong class="text-slate-200">Sewa Layanan Tahunan</strong>
                    </div>
                </div>

                <!-- Feature Breakdown Stats -->
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center text-slate-300">
                        <span>✓ Fitur Inti Paket:</span>
                        <span id="stat-included-count" class="font-mono font-bold text-white">0</span>
                    </div>
                    <div id="stat-deduction-row" class="hidden justify-between items-center text-rose-300">
                        <span>📉 Pengurangan Fitur Paket:</span>
                        <span id="stat-deduction-amount" class="font-mono font-bold">-Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-300">
                        <span>＋ Fitur Add-on / Tambahan:</span>
                        <span id="stat-optional-amount" class="font-mono font-bold text-amber-300">+Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-300">
                        <span>🧩 Add-on Modul Eksternal:</span>
                        <span id="stat-addons-amount" class="font-mono font-bold text-cyan-300">+Rp 0</span>
                    </div>
                </div>

                <!-- Add-ons Selection Trigger Modal/Accordion -->
                <div class="pt-2 border-t border-slate-800">
                    <button type="button" onclick="toggleAddonsDrawer()" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700/80 border border-slate-700 text-xs font-semibold text-slate-200 flex items-center justify-between transition-colors">
                        <span class="flex items-center gap-1.5">
                            <span>🧩</span>
                            <span>Pilih Add-on Khusus</span>
                        </span>
                        <span id="addon-badge-count" class="px-1.5 py-0.2 rounded bg-indigo-500/20 text-indigo-300 font-mono text-[10px]">0</span>
                    </button>
                </div>

                <!-- Included Infrastructure Accordion -->
                <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800/80 text-[11px] text-slate-400 space-y-1.5">
                    <div class="font-bold text-slate-300 text-xs flex items-center gap-1.5">
                        <span class="text-emerald-400">🛡️</span>
                        <span>Infrastruktur Termasuk:</span>
                    </div>
                    <div class="grid grid-cols-2 gap-1 text-[10px] text-slate-300 pt-1">
                        <div>✓ Hosting / VPS</div>
                        <div>✓ Domain Web</div>
                        <div>✓ SSL / HTTPS</div>
                        <div>✓ Backup Data</div>
                        <div class="col-span-2">✓ Maintenance Teknis</div>
                    </div>
                </div>

                <!-- Total Estimation Display -->
                <div class="pt-4 border-t border-slate-800 space-y-2">
                    <div class="text-xs text-slate-400">Total Estimasi Sewa:</div>
                    <div id="summary-total-price" class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Rp 0
                    </div>
                    <div class="text-[11px] text-slate-400 font-medium">/ tahun (sudah termasuk paket & fitur)</div>
                </div>

                <!-- Action Buttons: Save & Request Quote -->
                <div class="space-y-2 pt-2">
                    <button type="button" onclick="openSaveModal('quote')" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 hover:from-indigo-500 hover:to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>AJUKAN QUOTATION</span>
                    </button>
                    <button type="button" onclick="openSaveModal('save')" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs border border-slate-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>SIMPAN KONFIGURASI</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD-ONS DRAWER / MODAL -->
<div id="addons-drawer" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-2xl w-full max-h-[85vh] flex flex-col shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>🧩</span>
                    <span>Pilihan Add-ons & Custom Development</span>
                </h3>
                <p class="text-xs text-slate-400">Pilih modul tambahan untuk toko online Anda</p>
            </div>
            <button type="button" onclick="toggleAddonsDrawer()" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Addons List -->
        <div id="addons-modal-list" class="flex-1 overflow-y-auto space-y-2.5 pr-1">
            <!-- Populated via JavaScript -->
        </div>

        <div class="pt-3 border-t border-slate-800 flex justify-end">
            <button type="button" onclick="toggleAddonsDrawer()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow">
                Selesai Memilih Add-ons
            </button>
        </div>
    </div>
</div>

<!-- SAVE PROJECT / REQUEST QUOTATION MODAL -->
<div id="save-project-modal" class="fixed inset-0 z-50 bg-slate-950/85 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div>
                <h3 id="modal-title" class="text-lg font-bold text-white">Simpan Konfigurasi Proyek</h3>
                <p class="text-xs text-slate-400">Data konfigurasi akan dibekukan ke dalam snapshot harga</p>
            </div>
            <button type="button" onclick="closeSaveModal()" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="save-project-form" onsubmit="handleSaveProjectSubmit(event)" class="space-y-4">
            <div>
                <label for="input-project-name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Proyek <span class="text-rose-400">*</span></label>
                <input type="text" id="input-project-name" required placeholder="Contoh: E-Commerce Toko Fashion Kita"
                    class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="input-customer-name" class="block text-xs font-semibold text-slate-300 mb-1">Nama Pemesan / PIC <span class="text-rose-400">*</span></label>
                    <input type="text" id="input-customer-name" required placeholder="Nama Anda"
                        class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="input-customer-company" class="block text-xs font-semibold text-slate-300 mb-1">Nama Perusahaan / Toko</label>
                    <input type="text" id="input-customer-company" placeholder="Nama Brand / PT"
                        class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="input-customer-email" class="block text-xs font-semibold text-slate-300 mb-1">Email <span class="text-rose-400">*</span></label>
                    <input type="email" id="input-customer-email" required placeholder="email@domain.com"
                        class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="input-customer-phone" class="block text-xs font-semibold text-slate-300 mb-1">No. WhatsApp / Telepon <span class="text-rose-400">*</span></label>
                    <input type="tel" id="input-customer-phone" required placeholder="081234567890"
                        class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label for="input-notes" class="block text-xs font-semibold text-slate-300 mb-1">Catatan Tambahan / Kebutuhan Khusus</label>
                <textarea id="input-notes" rows="2" placeholder="Tuliskan jika ada kebutuhan integrasi atau timeline khusus..."
                    class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <button type="button" onclick="closeSaveModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl">
                    Batal
                </button>
                <button type="submit" id="save-submit-btn" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30">
                    Konfirmasi & Simpan Proyek
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Server-injected initial state
    const currentPackage = @json($selectedPackage);
    const allFeatures = @json($featuresData);
    const allAddons = @json($addons);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Client State
    let selectedFeatures = [];
    let selectedAddonIds = new Set();
    let currentCategoryFilter = 'all';
    let newlyAddedFeatureId = null;

    // Active Pointer Drag Tracker
    let activeDrag = null;

    document.addEventListener('DOMContentLoaded', () => {
        resetToPackageDefaults();
        renderAddonsModal();
    });

    /**
     * Reset selected features to exactly what is included in current package
     */
    function resetToPackageDefaults() {
        selectedFeatures = [];
        allFeatures.forEach(feature => {
            if (feature.package_status === 'included') {
                selectedFeatures.push({
                    id: feature.id,
                    complexity: 'standard',
                    quantity: 1
                });
            }
        });
        selectedAddonIds.clear();
        renderKanban();
        showToast(`Fitur direset sesuai default paket ${currentPackage.name}.`, 'info');
    }

    /**
     * Switch Package
     */
    function switchPackage(slug) {
        window.location.href = `{{ route('calculator') }}?package=${slug}`;
    }

    /**
     * Category filtering for available column
     */
    function selectCategoryFilter(catId) {
        currentCategoryFilter = catId;
        document.querySelectorAll('.cat-filter-btn').forEach(btn => {
            if (btn.getAttribute('data-cat') == catId) {
                btn.className = 'cat-filter-btn px-2.5 py-1 rounded-lg font-semibold whitespace-nowrap bg-indigo-600 text-white shadow-sm transition-all text-[11px]';
            } else {
                btn.className = 'cat-filter-btn px-2.5 py-1 rounded-lg font-medium whitespace-nowrap bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all text-[11px]';
            }
        });
        filterAvailableFeatures();
    }

    function clearSearch() {
        document.getElementById('feature-search').value = '';
        document.getElementById('clear-search-btn').classList.add('hidden');
        filterAvailableFeatures();
    }

    function filterAvailableFeatures() {
        const query = document.getElementById('feature-search').value.toLowerCase().trim();
        const clearBtn = document.getElementById('clear-search-btn');
        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        renderAvailableColumn(query);
    }

    /**
     * Master Kanban Render Function
     */
    function renderKanban() {
        const query = document.getElementById('feature-search').value.toLowerCase().trim();
        renderAvailableColumn(query);
        renderSelectedColumn();
        updateSummary();
    }

    /**
     * Render Column 1: Available Features
     */
    function renderAvailableColumn(searchQuery = '') {
        const container = document.getElementById('available-cards-container');
        if (!container) return;

        const selectedIds = new Set(selectedFeatures.map(f => f.id));

        // Filter features that are NOT selected, match category, and match search query
        const availableList = allFeatures.filter(feature => {
            const isNotSelected = !selectedIds.has(feature.id);
            const matchesCat = currentCategoryFilter === 'all' || feature.category_id == currentCategoryFilter;
            const matchesSearch = !searchQuery || 
                feature.name.toLowerCase().includes(searchQuery) || 
                (feature.description && feature.description.toLowerCase().includes(searchQuery));
            return isNotSelected && matchesCat && matchesSearch;
        });

        document.getElementById('available-count-badge').innerText = `${availableList.length} Fitur`;

        if (availableList.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center text-slate-500 border border-dashed border-slate-800 rounded-xl space-y-2">
                    <span class="text-2xl">✨</span>
                    <p class="text-xs font-semibold">Semua fitur di kategori ini telah dipilih atau tidak ada yang cocok.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = availableList.map(feature => {
            let statusBadge = '';
            let priceLabel = '';
            const stdPrice = feature.prices['standard']?.selling_price || 0;
            
            if (feature.package_status === 'included') {
                statusBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">✓ INCLUDED</span>`;
                priceLabel = `<span class="text-emerald-400 font-semibold font-mono text-[11px]">+Rp 0 dlm Paket</span>`;
            } else if (feature.package_status === 'optional') {
                statusBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">＋ ADD-ON</span>`;
                priceLabel = stdPrice > 0 ? `<span class="text-amber-300 font-mono text-[11px] font-bold">+Rp ${stdPrice.toLocaleString('id-ID')}</span>` : `<span class="text-slate-400 text-[11px]">Add-on</span>`;
            } else {
                statusBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">🔒 PRO</span>`;
                priceLabel = stdPrice > 0 ? `<span class="text-purple-300 font-mono text-[11px] font-bold">+Rp ${stdPrice.toLocaleString('id-ID')}</span>` : `<span class="text-slate-400 text-[11px]">Fitur Khusus</span>`;
            }

            const depNotice = feature.required_feature_names.length > 0
                ? `<div class="text-[10px] text-amber-400/90 flex items-center gap-1 mt-1"><span>Prasyarat:</span> <span class="font-medium">${feature.required_feature_names.join(', ')}</span></div>`
                : '';

            return `
                <div id="feature-card-${feature.id}" 
                    onpointerdown="initPointerDrag(event, ${feature.id}, 'available')"
                    class="kanban-card p-3.5 rounded-xl bg-slate-800/80 border border-slate-700/70 hover:border-indigo-500/60 hover:bg-slate-800 transition-all cursor-grab active:cursor-grabbing group shadow-sm flex flex-col justify-between gap-2.5 touch-none">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2 pointer-events-none">
                                <span class="text-lg shrink-0">${feature.icon}</span>
                                <h4 class="font-bold text-xs text-white group-hover:text-indigo-300 transition-colors">${feature.name}</h4>
                            </div>
                            <span class="text-slate-500 group-hover:text-indigo-400 transition-colors select-none text-base">⠿</span>
                        </div>
                        ${feature.description ? `<p class="text-[11px] text-slate-400 mt-1 pl-6 line-clamp-2 pointer-events-none">${feature.description}</p>` : ''}
                        ${depNotice}
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-700/50">
                        <div class="flex items-center gap-2 pointer-events-none">
                            ${statusBadge}
                            ${priceLabel}
                        </div>
                        <button type="button" onclick="addFeature(${feature.id})" class="px-2.5 py-1 rounded-lg bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white font-bold text-[11px] transition-all flex items-center gap-1">
                            <span>+ Tambah</span>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    /**
     * Render Column 2: Selected Features (With Live Reordering Support)
     */
    function renderSelectedColumn() {
        const container = document.getElementById('selected-cards-container');
        if (!container) return;

        const countBadge = document.getElementById('selected-count-badge');
        const mobileBadge = document.getElementById('mobile-selected-count');
        countBadge.innerText = `${selectedFeatures.length} Fitur`;
        if (mobileBadge) mobileBadge.innerText = selectedFeatures.length;

        if (selectedFeatures.length === 0) {
            container.innerHTML = `
                <div class="h-full min-h-[300px] flex flex-col items-center justify-center p-8 text-center text-slate-500 border-2 border-dashed border-slate-800 rounded-2xl space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-2xl border border-indigo-500/20 animate-pulse-subtle">
                        📥
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-white">Belum Ada Fitur Dipilih</h4>
                        <p class="text-xs text-slate-400 max-w-xs">Tarik kartu fitur dari kolom kiri ke sini, atau klik tombol <strong class="text-indigo-400">+ Tambah</strong>.</p>
                    </div>
                </div>
            `;
            return;
        }

        const selectedIdsSet = new Set(selectedFeatures.map(f => f.id));

        container.innerHTML = selectedFeatures.map((item, index) => {
            const feature = allFeatures.find(f => f.id === item.id);
            if (!feature) return '';

            const isIncluded = feature.package_status === 'included';
            const priceObj = feature.prices[item.complexity] || feature.prices['standard'] || { selling_price: 0 };
            const unitPrice = isIncluded ? 0 : (priceObj.selling_price || 0);
            const subtotal = unitPrice * (item.quantity || 1);

            const hasBasic = !!feature.prices['basic'];
            const hasAdvanced = !!feature.prices['advanced'];

            // Check missing prerequisites
            const missingDeps = feature.required_feature_ids.filter(id => !selectedIdsSet.has(id));
            let depAlert = '';
            if (missingDeps.length > 0) {
                const missingNames = feature.required_feature_names.filter((_, idx) => !selectedIdsSet.has(feature.required_feature_ids[idx]));
                depAlert = `
                    <div class="p-2 rounded-lg bg-amber-500/10 border border-amber-500/30 text-[10px] text-amber-300 flex items-center justify-between gap-2 mt-1">
                        <span>⚠️ Membutuhkan: <strong>${missingNames.join(', ')}</strong></span>
                        <button type="button" onclick="autoAddMissingDeps([${missingDeps.join(',')}])" class="px-2 py-0.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded text-[9px] whitespace-nowrap">
                            + Tambah Syarat
                        </button>
                    </div>
                `;
            }

            const isNewlyAdded = newlyAddedFeatureId === feature.id;

            return `
                <div id="selected-card-${feature.id}" 
                    data-feature-id="${feature.id}"
                    data-index="${index}"
                    onpointerdown="initPointerDrag(event, ${feature.id}, 'selected')"
                    class="kanban-card p-3.5 rounded-xl bg-slate-800/90 border border-slate-700/80 hover:border-indigo-500/50 transition-all space-y-2.5 shadow-md group cursor-grab active:cursor-grabbing touch-none ${isNewlyAdded ? 'animate-card-in ring-2 ring-indigo-500/40' : ''}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 pointer-events-none">
                            <span class="text-lg">${feature.icon}</span>
                            <div>
                                <h4 class="font-bold text-xs text-white">${feature.name}</h4>
                                <span class="text-[10px] text-slate-400">${feature.category_name}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 group-hover:text-indigo-400 select-none text-base transition-colors">⠿</span>
                            <button type="button" onclick="removeFeatureWithAnim(${feature.id})" class="text-slate-400 hover:text-rose-400 p-1 rounded-md hover:bg-slate-700/50 transition-colors" title="Hapus Fitur">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    ${depAlert}

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-700/50 text-xs">
                        <!-- Complexity Selector -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] text-slate-400">Varian:</span>
                            <select onchange="updateComplexity(${feature.id}, this.value)" class="px-2 py-1 bg-slate-900 border border-slate-700 rounded-lg text-white text-[10px] font-semibold focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                ${hasBasic ? `<option value="basic" ${item.complexity === 'basic' ? 'selected' : ''}>Basic</option>` : ''}
                                <option value="standard" ${item.complexity === 'standard' || (!hasBasic && !hasAdvanced) ? 'selected' : ''}>Standard</option>
                                ${hasAdvanced ? `<option value="advanced" ${item.complexity === 'advanced' ? 'selected' : ''}>Advanced</option>` : ''}
                            </select>
                        </div>

                        <!-- Subtotal -->
                        <div class="text-right pointer-events-none">
                            ${isIncluded ? `
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    ✓ Included (Rp 0)
                                </span>
                            ` : `
                                <span class="text-xs font-bold text-amber-300 font-mono">
                                    +Rp ${subtotal.toLocaleString('id-ID')}
                                </span>
                            `}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        // Reset newly added marker after render
        newlyAddedFeatureId = null;
    }

    /**
     * Render Addons Modal Checklist
     */
    function renderAddonsModal() {
        const container = document.getElementById('addons-modal-list');
        if (!container) return;

        container.innerHTML = allAddons.map(addon => {
            const isChecked = selectedAddonIds.has(addon.id);
            let priceDisplay = '';
            if (addon.price_type === 'range') {
                priceDisplay = `Rp ${addon.price_min.toLocaleString('id-ID')} – ${addon.price_max.toLocaleString('id-ID')}`;
            } else if (addon.price_type === 'fixed') {
                priceDisplay = `Rp ${addon.selling_price.toLocaleString('id-ID')}`;
            } else {
                priceDisplay = 'Kustom';
            }

            return `
                <label class="p-3.5 rounded-2xl border ${isChecked ? 'bg-indigo-950/40 border-indigo-500/50' : 'bg-slate-800/60 border-slate-700/60'} hover:border-indigo-500/30 flex items-start gap-3 cursor-pointer transition-all">
                    <input type="checkbox" onchange="toggleAddon(${addon.id})" ${isChecked ? 'checked' : ''}
                        class="w-4 h-4 rounded bg-slate-800 border-slate-600 text-indigo-600 focus:ring-indigo-500 mt-1">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">${addon.icon}</span>
                                <h4 class="font-bold text-xs text-white">${addon.name}</h4>
                            </div>
                            <span class="text-xs font-mono font-bold text-emerald-400">${priceDisplay}</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">${addon.description || ''}</p>
                    </div>
                </label>
            `;
        }).join('');
    }

    /**
     * Feature Manipulation Functions
     */
    function addFeature(featureId, targetIndex = null) {
        const feature = allFeatures.find(f => f.id === featureId);
        if (!feature) return;

        if (!selectedFeatures.some(f => f.id === featureId)) {
            newlyAddedFeatureId = featureId;
            const newItem = {
                id: featureId,
                complexity: 'standard',
                quantity: 1
            };
            if (targetIndex !== null && targetIndex >= 0 && targetIndex <= selectedFeatures.length) {
                selectedFeatures.splice(targetIndex, 0, newItem);
            } else {
                selectedFeatures.push(newItem);
            }
            renderKanban();
            showToast(`Fitur "${feature.name}" ditambahkan ke konfigurasi.`, 'success');
        }
    }

    function removeFeatureWithAnim(featureId) {
        const card = document.getElementById(`selected-card-${featureId}`);
        if (card) {
            card.classList.add('animate-card-out');
            setTimeout(() => {
                removeFeature(featureId);
            }, 190);
        } else {
            removeFeature(featureId);
        }
    }

    function removeFeature(featureId) {
        const feature = allFeatures.find(f => f.id === featureId);
        selectedFeatures = selectedFeatures.filter(f => f.id !== featureId);
        renderKanban();
        if (feature) {
            showToast(`Fitur "${feature.name}" dikeluarkan dari konfigurasi (biaya diperbarui).`, 'info');
        }
    }

    function clearSelectedFeatures() {
        if (confirm('Kosongkan semua fitur yang dipilih?')) {
            selectedFeatures = [];
            renderKanban();
            showToast('Semua fitur dikosongkan.', 'info');
        }
    }

    function updateComplexity(featureId, complexity) {
        const item = selectedFeatures.find(f => f.id === featureId);
        if (item) {
            item.complexity = complexity;
            renderKanban();
            showToast('Tingkat varian diperbarui (biaya disesuaikan).', 'info');
        }
    }

    function autoAddMissingDeps(depIds) {
        depIds.forEach(id => {
            if (!selectedFeatures.some(f => f.id === id)) {
                selectedFeatures.push({
                    id: id,
                    complexity: 'standard',
                    quantity: 1
                });
            }
        });
        renderKanban();
        showToast('Fitur prasyarat berhasil ditambahkan otomatis.', 'success');
    }

    function toggleAddon(addonId) {
        if (selectedAddonIds.has(addonId)) {
            selectedAddonIds.delete(addonId);
        } else {
            selectedAddonIds.add(addonId);
        }
        document.getElementById('addon-badge-count').innerText = selectedAddonIds.size;
        renderAddonsModal();
        updateSummary();
    }

    function toggleAddonsDrawer() {
        const drawer = document.getElementById('addons-drawer');
        drawer.classList.toggle('hidden');
        drawer.classList.toggle('flex');
    }

    /**
     * BUTTERY-SMOOTH CUSTOM POINTER DRAG & SORTABLE ENGINE
     * Supports:
     * 1. Moving between Available & Selected columns with 100% solid visuals.
     * 2. Live Reordering / Sorting cards vertically inside Selected Features column!
     */
    function initPointerDrag(event, featureId, sourceCol) {
        // If clicking interactive controls, ignore drag
        if (event.target.closest('button, select, input, a, textarea')) return;
        if (event.button !== 0 && event.pointerType === 'mouse') return;

        const cardEl = event.currentTarget;
        const rect = cardEl.getBoundingClientRect();

        activeDrag = {
            featureId: featureId,
            sourceCol: sourceCol,
            startX: event.clientX,
            startY: event.clientY,
            offsetX: event.clientX - rect.left,
            offsetY: event.clientY - rect.top,
            width: rect.width,
            cardEl: cardEl,
            cloneEl: null,
            targetIndex: null,
            dropTarget: null,
            isDragging: false
        };

        window.addEventListener('pointermove', onGlobalPointerMove, { passive: false });
        window.addEventListener('pointerup', onGlobalPointerUp);
        window.addEventListener('pointercancel', onGlobalPointerUp);
    }

    function onGlobalPointerMove(event) {
        if (!activeDrag) return;

        const dx = event.clientX - activeDrag.startX;
        const dy = event.clientY - activeDrag.startY;

        // Threshold of 4px before initiating drag
        if (!activeDrag.isDragging) {
            if (Math.abs(dx) > 4 || Math.abs(dy) > 4) {
                activeDrag.isDragging = true;
                createFloatingDragCard(event);
            } else {
                return;
            }
        }

        event.preventDefault();

        // Update floating card location
        if (activeDrag.cloneEl) {
            const x = event.clientX - activeDrag.offsetX;
            const y = event.clientY - activeDrag.offsetY;
            activeDrag.cloneEl.style.transform = `translate3d(${x}px, ${y}px, 0) scale(1.025) rotate(1.2deg)`;
        }

        // Live drop target & sort index calculation
        detectDropTargetAndSortPosition(event.clientX, event.clientY);
    }

    function createFloatingDragCard(event) {
        const clone = activeDrag.cardEl.cloneNode(true);
        clone.id = 'floating-drag-card';
        clone.style.position = 'fixed';
        clone.style.left = '0';
        clone.style.top = '0';
        clone.style.width = activeDrag.width + 'px';
        clone.style.zIndex = '99999';
        clone.style.pointerEvents = 'none';
        clone.style.opacity = '1';
        clone.style.backgroundColor = '#1e1b4b';
        clone.style.borderColor = '#818cf8';
        clone.style.borderWidth = '2px';
        clone.style.borderRadius = '0.75rem';
        clone.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.75), 0 0 25px rgba(99, 102, 241, 0.5)';
        clone.style.willChange = 'transform';
        clone.style.cursor = 'grabbing';

        const x = event.clientX - activeDrag.offsetX;
        const y = event.clientY - activeDrag.offsetY;
        clone.style.transform = `translate3d(${x}px, ${y}px, 0) scale(1.025) rotate(1.2deg)`;

        document.body.appendChild(clone);
        activeDrag.cloneEl = clone;

        // Stylize source placeholder in original column
        activeDrag.cardEl.style.opacity = '0.35';
        activeDrag.cardEl.style.borderStyle = 'dashed';
        activeDrag.cardEl.style.borderColor = '#6366f1';
    }

    function detectDropTargetAndSortPosition(clientX, clientY) {
        const selectedDropzone = document.getElementById('selected-cards-container');
        const availableDropzone = document.getElementById('available-cards-container');
        if (!selectedDropzone || !availableDropzone) return;

        const selectedRect = selectedDropzone.getBoundingClientRect();
        const availableRect = availableDropzone.getBoundingClientRect();

        const isOverSelected = clientX >= selectedRect.left && clientX <= selectedRect.right &&
                               clientY >= selectedRect.top && clientY <= selectedRect.bottom;

        const isOverAvailable = clientX >= availableRect.left && clientX <= availableRect.right &&
                                clientY >= availableRect.top && clientY <= availableRect.bottom;

        if (isOverSelected) {
            activeDrag.dropTarget = 'selected';
            selectedDropzone.classList.add('kanban-dropzone-active');
            availableDropzone.classList.remove('kanban-dropzone-active');
            removeDropPlaceholder(availableDropzone);

            // Dynamic sort positioning inside Selected container
            updateSelectedSortPlaceholder(selectedDropzone, clientY);
        } else if (isOverAvailable && activeDrag.sourceCol === 'selected') {
            activeDrag.dropTarget = 'available';
            availableDropzone.classList.add('kanban-dropzone-active');
            selectedDropzone.classList.remove('kanban-dropzone-active');
            removeDropPlaceholder(selectedDropzone);
            showDropPlaceholder(availableDropzone, '↩ Lepaskan untuk mengeluarkan fitur');
        } else {
            activeDrag.dropTarget = null;
            selectedDropzone.classList.remove('kanban-dropzone-active');
            availableDropzone.classList.remove('kanban-dropzone-active');
            removeDropPlaceholder(selectedDropzone);
            removeDropPlaceholder(availableDropzone);
        }
    }

    /**
     * Compute exact index and insert the placeholder line dynamically
     */
    function updateSelectedSortPlaceholder(container, clientY) {
        const cards = Array.from(container.querySelectorAll('.kanban-card:not(#floating-drag-card)'))
            .filter(card => {
                // If moving within selected, ignore the active source card in index calculation
                if (activeDrag && activeDrag.sourceCol === 'selected' && card.id === `selected-card-${activeDrag.featureId}`) {
                    return false;
                }
                return true;
            });

        let placeholder = document.getElementById('kanban-placeholder');
        if (!placeholder) {
            placeholder = document.createElement('div');
            placeholder.id = 'kanban-placeholder';
            placeholder.className = 'kanban-drop-placeholder';
            placeholder.innerHTML = `<span>↕ Letakkan di urutan ini</span>`;
        }

        let insertBeforeEl = null;
        let targetIndex = cards.length;

        for (let i = 0; i < cards.length; i++) {
            const rect = cards[i].getBoundingClientRect();
            const midY = rect.top + rect.height / 2;
            if (clientY < midY) {
                insertBeforeEl = cards[i];
                targetIndex = i;
                break;
            }
        }

        if (insertBeforeEl) {
            container.insertBefore(placeholder, insertBeforeEl);
        } else {
            container.appendChild(placeholder);
        }

        activeDrag.targetIndex = targetIndex;
    }

    function showDropPlaceholder(container, text) {
        if (!container.querySelector('.kanban-drop-placeholder')) {
            const placeholder = document.createElement('div');
            placeholder.id = 'kanban-placeholder';
            placeholder.className = 'kanban-drop-placeholder';
            placeholder.innerHTML = `<span>${text}</span>`;
            container.appendChild(placeholder);
        }
    }

    function removeDropPlaceholder(container) {
        if (!container) {
            document.querySelectorAll('.kanban-drop-placeholder').forEach(el => el.remove());
            return;
        }
        const placeholder = container.querySelector('.kanban-drop-placeholder');
        if (placeholder) placeholder.remove();
    }

    function onGlobalPointerUp(event) {
        window.removeEventListener('pointermove', onGlobalPointerMove);
        window.removeEventListener('pointerup', onGlobalPointerUp);
        window.removeEventListener('pointercancel', onGlobalPointerUp);

        if (!activeDrag) return;

        if (activeDrag.isDragging) {
            const selectedDropzone = document.getElementById('selected-cards-container');
            const availableDropzone = document.getElementById('available-cards-container');

            if (activeDrag.dropTarget === 'selected') {
                if (activeDrag.sourceCol === 'selected') {
                    // Reorder inside selectedFeatures array
                    const fromIndex = selectedFeatures.findIndex(f => f.id === activeDrag.featureId);
                    if (fromIndex !== -1 && activeDrag.targetIndex !== null) {
                        const [movedItem] = selectedFeatures.splice(fromIndex, 1);
                        let targetIdx = activeDrag.targetIndex;
                        // If moved downward, target index aligns automatically
                        selectedFeatures.splice(targetIdx, 0, movedItem);
                        newlyAddedFeatureId = activeDrag.featureId;
                        renderKanban();
                        showToast('Urutan fitur berhasil diperbarui.', 'info');
                    }
                } else if (activeDrag.sourceCol === 'available') {
                    // Add new feature at specific index
                    addFeature(activeDrag.featureId, activeDrag.targetIndex);
                }
            } else if (activeDrag.dropTarget === 'available' && activeDrag.sourceCol === 'selected') {
                removeFeature(activeDrag.featureId);
            } else {
                // Restore source card appearance if dropped outside valid dropzone
                if (activeDrag.cardEl) {
                    activeDrag.cardEl.style.opacity = '1';
                    activeDrag.cardEl.style.borderStyle = 'solid';
                    activeDrag.cardEl.style.borderColor = '';
                }
            }

            // Remove floating clone
            if (activeDrag.cloneEl) {
                activeDrag.cloneEl.remove();
            }

            // Cleanup dropzone highlights & placeholders
            if (selectedDropzone) selectedDropzone.classList.remove('kanban-dropzone-active');
            if (availableDropzone) availableDropzone.classList.remove('kanban-dropzone-active');
            removeDropPlaceholder();
        }

        activeDrag = null;
    }

    /**
     * Live Calculation & Summary Update
     */
    function updateSummary() {
        const basePackagePrice = parseFloat(currentPackage.price) || 0;
        const selectedIdsSet = new Set(selectedFeatures.map(f => f.id));

        // 1. Calculate deduction for removed default included features
        const defaultIncludedFeatures = allFeatures.filter(f => f.package_status === 'included');
        let includedSelectedCount = 0;
        let packageDeduction = 0;

        defaultIncludedFeatures.forEach(feature => {
            if (selectedIdsSet.has(feature.id)) {
                includedSelectedCount++;
            } else {
                // If user excluded an included feature, grant deduction
                const stdPrice = feature.prices['standard']?.selling_price || 300000;
                packageDeduction += (stdPrice * 0.5);
            }
        });

        const effectivePackagePrice = Math.max(basePackagePrice * 0.3, basePackagePrice - packageDeduction);

        // 2. Calculate optional/add-on features total
        let optionalCount = 0;
        let featuresTotalSelling = 0;

        selectedFeatures.forEach(item => {
            const feature = allFeatures.find(f => f.id === item.id);
            if (!feature) return;

            if (feature.package_status !== 'included') {
                optionalCount++;
                const priceObj = feature.prices[item.complexity] || feature.prices['standard'] || { selling_price: 0 };
                featuresTotalSelling += (priceObj.selling_price || 0) * (item.quantity || 1);
            }
        });

        // 3. Calculate external add-on modules total
        let addonsTotalSelling = 0;
        selectedAddonIds.forEach(addonId => {
            const addon = allAddons.find(a => a.id === addonId);
            if (addon) {
                addonsTotalSelling += (addon.selling_price || addon.price_min || 0);
            }
        });

        // 4. Grand Total
        const grandTotal = effectivePackagePrice + featuresTotalSelling + addonsTotalSelling;

        // UI Updates
        document.getElementById('stat-included-count').innerText = `${includedSelectedCount} / ${defaultIncludedFeatures.length}`;
        
        const deductionRow = document.getElementById('stat-deduction-row');
        const deductionAmount = document.getElementById('stat-deduction-amount');
        if (packageDeduction > 0) {
            deductionRow.classList.remove('hidden');
            deductionRow.classList.add('flex');
            deductionAmount.innerText = `-Rp ${packageDeduction.toLocaleString('id-ID')}`;
        } else {
            deductionRow.classList.add('hidden');
            deductionRow.classList.remove('flex');
        }

        document.getElementById('stat-optional-amount').innerText = `+Rp ${featuresTotalSelling.toLocaleString('id-ID')}`;
        document.getElementById('stat-addons-amount').innerText = `+Rp ${addonsTotalSelling.toLocaleString('id-ID')}`;
        document.getElementById('addon-badge-count').innerText = selectedAddonIds.size;

        const totalEl = document.getElementById('summary-total-price');
        if (currentPackage.price_type === 'custom') {
            totalEl.innerText = 'Custom';
        } else {
            totalEl.innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
        }
    }

    /**
     * Mobile Tab Switcher
     */
    function switchMobileTab(tab) {
        const colAvailable = document.getElementById('col-available');
        const colSelected = document.getElementById('col-selected');
        const colSummary = document.getElementById('col-summary');

        const btnAvailable = document.getElementById('tab-btn-available');
        const btnSelected = document.getElementById('tab-btn-selected');
        const btnSummary = document.getElementById('tab-btn-summary');

        // Hide all
        colAvailable.classList.add('hidden');
        colSelected.classList.add('hidden');
        colSelected.classList.remove('flex');
        colSummary.classList.add('hidden');

        btnAvailable.className = 'py-2.5 px-2 rounded-xl transition-all text-slate-400 hover:text-white';
        btnSelected.className = 'py-2.5 px-2 rounded-xl transition-all text-slate-400 hover:text-white flex items-center justify-center gap-1';
        btnSummary.className = 'py-2.5 px-2 rounded-xl transition-all text-slate-400 hover:text-white';

        if (tab === 'available') {
            colAvailable.classList.remove('hidden');
            btnAvailable.className = 'py-2.5 px-2 rounded-xl transition-all bg-indigo-600 text-white shadow';
        } else if (tab === 'selected') {
            colSelected.classList.remove('hidden');
            colSelected.classList.add('flex');
            btnSelected.className = 'py-2.5 px-2 rounded-xl transition-all bg-indigo-600 text-white shadow flex items-center justify-center gap-1';
        } else if (tab === 'summary') {
            colSummary.classList.remove('hidden');
            btnSummary.className = 'py-2.5 px-2 rounded-xl transition-all bg-indigo-600 text-white shadow';
        }
    }

    /**
     * Save Project / Quotation Modal Handlers
     */
    let activeModalMode = 'save';

    function openSaveModal(mode = 'save') {
        activeModalMode = mode;
        const modal = document.getElementById('save-project-modal');
        const title = document.getElementById('modal-title');
        const submitBtn = document.getElementById('save-submit-btn');

        if (mode === 'quote') {
            title.innerText = 'Ajukan Quotation Proyek';
            submitBtn.innerText = 'Ajukan Quotation Resmi &rarr;';
        } else {
            title.innerText = 'Simpan Konfigurasi Proyek';
            submitBtn.innerText = 'Konfirmasi & Simpan Proyek';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSaveModal() {
        const modal = document.getElementById('save-project-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function handleSaveProjectSubmit(event) {
        event.preventDefault();
        const submitBtn = document.getElementById('save-submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Menyimpan...';

        const payload = {
            package_id: currentPackage.id,
            name: document.getElementById('input-project-name').value,
            customer_name: document.getElementById('input-customer-name').value,
            customer_company: document.getElementById('input-customer-company').value,
            customer_email: document.getElementById('input-customer-email').value,
            customer_phone: document.getElementById('input-customer-phone').value,
            notes: document.getElementById('input-notes').value,
            mode: activeModalMode,
            features: selectedFeatures.map(f => ({
                feature_id: f.id,
                complexity: f.complexity,
                quantity: f.quantity
            })),
            addons: Array.from(selectedAddonIds).map(id => ({
                addon_id: id,
                quantity: 1
            }))
        };

        try {
            const res = await fetch('{{ route("projects.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                const data = await res.json();
                showToast(data.message || 'Proyek berhasil disimpan!', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 600);
            } else {
                const errData = await res.json();
                showToast(errData.message || 'Gagal menyimpan proyek. Periksa kembali isian form.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerText = activeModalMode === 'quote' ? 'Ajukan Quotation Resmi →' : 'Konfirmasi & Simpan Proyek';
            }
        } catch (err) {
            showToast('Koneksi terputus. Silakan coba lagi.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerText = activeModalMode === 'quote' ? 'Ajukan Quotation Resmi →' : 'Konfirmasi & Simpan Proyek';
        }
    }
</script>
@endpush
