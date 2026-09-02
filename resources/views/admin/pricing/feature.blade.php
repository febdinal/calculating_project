@extends('layouts.admin')

@section('title', 'Atur Harga: ' . $feature->name)
@section('page-title', 'Atur Harga: ' . $feature->name)
@section('page-subtitle', 'Konfigurasi varian kompleksitas (Basic, Standard, Advanced, Custom), harga modal (cost), dan harga jual (selling)')

@section('header-actions')
    <a href="{{ route('admin.pricing.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">
        &larr; Kembali ke Master Pricing
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Feature Info Banner -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-2xl">
                {{ $feature->icon ?? '⚙️' }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-white">{{ $feature->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                        {{ $feature->category?->name }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">{{ $feature->description }}</p>
            </div>
        </div>
    </div>

    <!-- Complexity Variants Pricing Form -->
    <form action="{{ route('admin.pricing.update', $feature) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            @foreach ($complexities as $complexity)
                @php
                    $priceRow = $existingPrices->get($complexity);
                    $isActive = $priceRow && $priceRow->status === 'active';
                    $isDefault = $priceRow ? $priceRow->is_default : ($complexity === 'standard');
                    $costPrice = $priceRow && $priceRow->cost_price !== null ? (int)$priceRow->cost_price : '';
                    $sellingPrice = $priceRow && $priceRow->selling_price !== null ? (int)$priceRow->selling_price : '';
                    $profit = $priceRow ? $priceRow->calculateProfit() : null;
                    $margin = $priceRow ? $priceRow->calculateMarginPercentage() : null;
                @endphp
                <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4 relative overflow-hidden group hover:border-slate-700 transition-all">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="prices[{{ $complexity }}][is_active]" value="1" {{ $isActive ? 'checked' : '' }}
                                    id="active-{{ $complexity }}"
                                    onchange="toggleComplexityCard('{{ $complexity }}')"
                                    class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-bold uppercase tracking-wider text-white">Varian {{ ucfirst($complexity) }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-400">
                                <input type="radio" name="default_complexity" value="{{ $complexity }}" {{ $isDefault ? 'checked' : '' }}
                                    class="w-3.5 h-3.5 text-indigo-600 focus:ring-indigo-500 bg-slate-800 border-slate-700">
                                <span>Pilihan Default</span>
                            </label>
                        </div>
                    </div>

                    <div id="fields-{{ $complexity }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 {{ $isActive ? '' : 'opacity-40 pointer-events-none' }}">
                        <!-- Price Type -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Tipe Harga</label>
                            <select name="prices[{{ $complexity }}][price_type]"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="fixed" {{ ($priceRow?->price_type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>Tetap (Fixed)</option>
                                <option value="range" {{ ($priceRow?->price_type ?? '') === 'range' ? 'selected' : '' }}>Rentang (Range)</option>
                                <option value="custom" {{ ($priceRow?->price_type ?? '') === 'custom' ? 'selected' : '' }}>Kustom</option>
                            </select>
                        </div>

                        <!-- Cost Price (Internal) -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-amber-400 mb-1.5 flex items-center justify-between">
                                <span>Cost Price (Internal)</span>
                                <span class="text-[9px] text-amber-400/80 font-normal">Rahasia</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-mono text-amber-400">Rp</span>
                                <input type="number" step="50000" min="0" 
                                    name="prices[{{ $complexity }}][cost_price]" 
                                    value="{{ $costPrice }}" 
                                    placeholder="Contoh: 800000"
                                    id="detail-cost-{{ $complexity }}"
                                    oninput="calculateDetailProfit('{{ $complexity }}')"
                                    class="w-full pl-9 pr-3 py-2 bg-slate-800 border border-amber-500/30 rounded-xl text-amber-300 text-xs font-mono focus:outline-none focus:ring-1 focus:ring-amber-500">
                            </div>
                        </div>

                        <!-- Selling Price -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-300 mb-1.5 flex items-center justify-between">
                                <span>Selling Price (User)</span>
                                <span class="text-[9px] text-slate-400 font-normal">Tampil di web</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-mono text-slate-400">Rp</span>
                                <input type="number" step="50000" min="0" 
                                    name="prices[{{ $complexity }}][selling_price]" 
                                    value="{{ $sellingPrice }}" 
                                    placeholder="Contoh: 1500000"
                                    id="detail-sell-{{ $complexity }}"
                                    oninput="calculateDetailProfit('{{ $complexity }}')"
                                    class="w-full pl-9 pr-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Live Calculated Profit & Margin -->
                        <div class="p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60 flex flex-col justify-center text-xs">
                            <div class="flex justify-between items-center text-[11px] text-slate-400">
                                <span>Laba:</span>
                                <span id="detail-profit-{{ $complexity }}" class="font-mono font-semibold {{ ($profit ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $profit !== null ? 'Rp ' . number_format($profit, 0, ',', '.') : '—' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] text-slate-400 mt-1">
                                <span>Margin:</span>
                                <span id="detail-margin-{{ $complexity }}" class="font-mono font-bold text-purple-300">
                                    {{ $margin !== null ? $margin . '%' : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.pricing.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                Simpan Konfigurasi Harga
            </button>
        </div>
    </form>
</div>

<script>
    function toggleComplexityCard(complexity) {
        const checkbox = document.getElementById('active-' + complexity);
        const fields = document.getElementById('fields-' + complexity);
        if (checkbox.checked) {
            fields.classList.remove('opacity-40', 'pointer-events-none');
        } else {
            fields.classList.add('opacity-40', 'pointer-events-none');
        }
    }

    function calculateDetailProfit(complexity) {
        const costVal = parseFloat(document.getElementById('detail-cost-' + complexity).value) || 0;
        const sellVal = parseFloat(document.getElementById('detail-sell-' + complexity).value) || 0;
        const profitEl = document.getElementById('detail-profit-' + complexity);
        const marginEl = document.getElementById('detail-margin-' + complexity);

        if (!sellVal && !costVal) {
            profitEl.innerText = '—';
            marginEl.innerText = '—';
            return;
        }

        const profit = sellVal - costVal;
        const margin = sellVal > 0 ? ((profit / sellVal) * 100).toFixed(1) : 0;

        profitEl.innerText = 'Rp ' + profit.toLocaleString('id-ID');
        profitEl.className = profit >= 0 ? 'font-mono font-semibold text-emerald-400' : 'font-mono font-semibold text-rose-400';
        marginEl.innerText = margin + '%';
    }
</script>
@endsection
