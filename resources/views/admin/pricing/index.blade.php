@extends('layouts.admin')

@section('title', 'Master Pricing & Margin Internal')
@section('page-title', 'Kelola Harga & Margin Internal')
@section('page-subtitle', 'Pengaturan Cost Price (Rahasia Internal), Selling Price (Harga User), estimasi laba kotor, dan persentase margin')

@section('header-actions')
    <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono">
            Terisi: {{ $pricedVariants }} / {{ $totalVariants }}
        </span>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Notice Card -->
    <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 text-slate-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-white">Privasi Biaya Internal (Cost Price Isolation)</h2>
                <p class="text-xs text-slate-400">Nilai <strong class="text-amber-400">Cost Price</strong>, <strong class="text-emerald-400">Profit</strong>, dan <strong class="text-purple-400">Margin</strong> terenkapsulasi secara server-side dan tidak pernah dikirim ke frontend publik/user.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pricing.index', ['filter' => 'unpriced']) }}" class="px-3 py-1.5 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-xs font-semibold rounded-lg whitespace-nowrap transition-colors">
                Tampilkan Hanya Belum Diisi ({{ $unpricedVariants }})
            </a>
            @if (request()->hasAny(['filter', 'category', 'search']))
                <a href="{{ route('admin.pricing.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg transition-colors">
                    Semua
                </a>
            @endif
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.pricing.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari fitur..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="category" onchange="this.form.submit()"
                class="w-full sm:w-auto px-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Master Interactive Pricing Form -->
    <form action="{{ route('admin.pricing.batch-update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-[11px] uppercase font-bold text-slate-400 bg-slate-800/80 border-b border-slate-700/60 sticky top-0 z-10 backdrop-blur">
                        <tr>
                            <th class="p-3.5">Fitur & Kategori</th>
                            <th class="p-3.5 text-center">Kompleksitas</th>
                            <th class="p-3.5 text-right w-44">Internal Cost Price (Rp)</th>
                            <th class="p-3.5 text-right w-44">Selling Price (Rp)</th>
                            <th class="p-3.5 text-right">Laba Kotor (Profit)</th>
                            <th class="p-3.5 text-center">Margin %</th>
                            <th class="p-3.5 text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach ($features as $feature)
                            @foreach ($feature->prices as $idx => $price)
                                @php
                                    $profit = $price->calculateProfit();
                                    $margin = $price->calculateMarginPercentage();
                                @endphp
                                <tr class="hover:bg-slate-800/30 transition-colors group">
                                    <td class="p-3.5">
                                        @if ($idx === 0)
                                            <div class="flex items-center gap-2">
                                                <span>{{ $feature->icon ?? '⚙️' }}</span>
                                                <span class="font-bold text-white text-sm">{{ $feature->name }}</span>
                                            </div>
                                            <div class="text-[11px] text-slate-400 pl-6">{{ $feature->category?->name }}</div>
                                        @else
                                            <div class="text-[11px] text-slate-500 pl-6">&rdquor; Varian lain</div>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                            {{ $price->complexity === 'basic' ? 'bg-slate-800 text-slate-300' : '' }}
                                            {{ $price->complexity === 'standard' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : '' }}
                                            {{ $price->complexity === 'advanced' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : '' }}
                                            {{ $price->complexity === 'custom' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : '' }}">
                                            {{ $price->complexity }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] font-mono text-amber-400/80">Rp</span>
                                            <input type="number" step="50000" min="0" 
                                                name="prices[{{ $price->id }}][cost_price]" 
                                                value="{{ $price->cost_price !== null ? (int)$price->cost_price : '' }}" 
                                                placeholder="Belum diisi"
                                                id="cost-{{ $price->id }}"
                                                oninput="calculateLiveRow({{ $price->id }})"
                                                class="w-full pl-8 pr-2.5 py-1.5 bg-slate-800/90 border border-slate-700 rounded-lg text-amber-300 text-xs font-mono text-right focus:outline-none focus:ring-1 focus:ring-amber-500">
                                        </div>
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] font-mono text-slate-400">Rp</span>
                                            <input type="number" step="50000" min="0" 
                                                name="prices[{{ $price->id }}][selling_price]" 
                                                value="{{ $price->selling_price !== null ? (int)$price->selling_price : '' }}" 
                                                placeholder="Belum diisi"
                                                id="selling-{{ $price->id }}"
                                                oninput="calculateLiveRow({{ $price->id }})"
                                                class="w-full pl-8 pr-2.5 py-1.5 bg-slate-800/90 border border-slate-700 rounded-lg text-white text-xs font-mono text-right focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        </div>
                                    </td>
                                    <td class="p-3.5 text-right font-mono text-xs font-semibold" id="profit-{{ $price->id }}">
                                        @if ($profit !== null)
                                            <span class="{{ $profit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                Rp {{ number_format($profit, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-slate-600 font-normal">Belum diisi</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center font-mono text-xs" id="margin-{{ $price->id }}">
                                        @if ($margin !== null)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $margin >= 35 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($margin >= 20 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30') }}">
                                                {{ $margin }}%
                                            </span>
                                        @else
                                            <span class="text-slate-600">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <a href="{{ route('admin.pricing.feature', $feature) }}" class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-lg inline-block transition-colors" title="Detail Varian">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sticky Save Floating Bar -->
        <div class="sticky bottom-6 z-30 bg-slate-900/90 border border-slate-800/90 backdrop-blur-xl rounded-2xl p-4 shadow-2xl flex items-center justify-between">
            <div class="text-xs text-slate-400">
                Ubah angka di kolom input langsung dan klik <strong class="text-white">Simpan Perubahan Harga</strong> untuk update massal.
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Perubahan Harga (Batch Save)</span>
            </button>
        </div>
    </form>
</div>

<script>
    function calculateLiveRow(id) {
        const costInput = document.getElementById('cost-' + id);
        const sellInput = document.getElementById('selling-' + id);
        const profitEl = document.getElementById('profit-' + id);
        const marginEl = document.getElementById('margin-' + id);

        const cost = parseFloat(costInput.value) || 0;
        const sell = parseFloat(sellInput.value) || 0;

        if (costInput.value === '' || sellInput.value === '') {
            profitEl.innerHTML = '<span class="text-slate-600 font-normal">Belum diisi</span>';
            marginEl.innerHTML = '<span class="text-slate-600">-</span>';
            return;
        }

        const profit = sell - cost;
        const margin = sell > 0 ? ((profit / sell) * 100).toFixed(1) : 0;

        profitEl.innerHTML = `<span class="${profit >= 0 ? 'text-emerald-400' : 'text-rose-400'} font-mono">Rp ${profit.toLocaleString('id-ID')}</span>`;
        
        let badgeColor = 'bg-rose-500/20 text-rose-300 border border-rose-500/30';
        if (margin >= 35) {
            badgeColor = 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
        } else if (margin >= 20) {
            badgeColor = 'bg-amber-500/20 text-amber-300 border border-amber-500/30';
        }

        marginEl.innerHTML = `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${badgeColor} font-mono">${margin}%</span>`;
    }
</script>
@endsection
