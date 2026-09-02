@extends('layouts.app')

@section('title', 'Konfigurasi Proyek: ' . $project->name)

@section('content')
<div class="py-8 sm:py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Top Action & Status Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                @php
                    $statusBadge = match($project->status) {
                        'approved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'pending' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                        'completed' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                        'rejected' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                        default => 'bg-slate-800 text-slate-300 border-slate-700',
                    };
                    $statusLabel = match($project->status) {
                        'approved' => 'Quotation Disetujui',
                        'pending' => 'Menunggu Review / Penawaran Resmi',
                        'completed' => 'Proyek Selesai Dikerjakan',
                        'rejected' => 'Penawaran Ditolak',
                        default => 'Draft Konfigurasi',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $statusBadge }}">
                    {{ $statusLabel }}
                </span>
                @if ($project->quotation)
                    <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">
                        {{ $project->quotation->quotation_number }}
                    </span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $project->name }}</h1>
            <p class="text-xs text-slate-400">
                Disimpan pada {{ $project->created_at->translatedFormat('d F Y, H:i') }} &bull; Paket: <strong class="text-indigo-300">{{ $project->package?->name ?? 'Custom' }}</strong>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('projects.pdf', $project) }}" target="_blank"
                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Download Quotation PDF</span>
            </a>

            @if ($project->isDraft())
                <form action="{{ route('projects.request-quotation', $project) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                        <span>Ajukan Penawaran Resmi &rarr;</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('calculator', ['package' => $project->package?->slug]) }}"
                class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition-colors">
                Buka di Kalkulator
            </a>
        </div>
    </div>

    <!-- Customer & Pricing Highlights Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Info -->
        <div class="md:col-span-2 bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 pb-2 border-b border-slate-800">
                Informasi Pemesan / PIC
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-500 block text-[11px]">Nama Lengkap:</span>
                    <span class="font-bold text-white text-sm">{{ $project->customer_name ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Perusahaan / Toko:</span>
                    <span class="font-bold text-white text-sm">{{ $project->customer_company ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Alamat Email:</span>
                    <span class="font-mono text-indigo-300">{{ $project->customer_email ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">WhatsApp / Telepon:</span>
                    <span class="font-mono text-emerald-400">{{ $project->customer_phone ?: '—' }}</span>
                </div>
            </div>

            @if ($project->notes)
                <div class="pt-2">
                    <span class="text-slate-500 block text-[11px]">Catatan Kebutuhan:</span>
                    <p class="text-slate-300 text-xs mt-1 whitespace-pre-line bg-slate-800/40 p-3 rounded-xl border border-slate-800">
                        {{ $project->notes }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Grand Total Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 rounded-2xl p-6 shadow-xl flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 block mb-1">Total Estimasi Investasi</span>
                <div class="text-3xl font-black text-white font-mono tracking-tight bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">
                    Rp {{ number_format((float)$project->total_selling_price, 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-400 mt-1">/ tahun (Model Paket Sewa)</p>
            </div>

            <div class="pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                <div class="flex justify-between">
                    <span class="text-slate-400">Paket Dasar (Snapshot):</span>
                    <span class="font-mono font-semibold text-white">Rp {{ number_format((float)$project->package_price_snapshot, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Jumlah Fitur:</span>
                    <span class="font-mono font-semibold text-indigo-400">{{ $project->projectFeatures->count() }} Fitur</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Jumlah Add-ons:</span>
                    <span class="font-mono font-semibold text-rose-400">{{ $project->projectAddons->count() }} Add-on</span>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-800/60 text-[11px] text-slate-400">
                🛡️ Biaya sudah mencakup Hosting, Domain, SSL, Backup & Maintenance.
            </div>
        </div>
    </div>

    <!-- Selected Features Table (Frozen Snapshot) -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
        <div>
            <h2 class="text-base font-bold text-white">Rincian Fitur Terpilih (Price Snapshot)</h2>
            <p class="text-xs text-slate-400">Harga dan ketersediaan beku pada saat konfigurasi proyek dibuat</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-[10px] uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                    <tr>
                        <th class="p-3">Nama Fitur</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3 text-center">Kompleksitas</th>
                        <th class="p-3 text-center">Status dalam Paket</th>
                        <th class="p-3 text-right">Harga Jual</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach ($project->projectFeatures as $pf)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="p-3 font-semibold text-white">
                                {{ $pf->feature_name }}
                            </td>
                            <td class="p-3 text-slate-400">
                                {{ $pf->category_name ?? '—' }}
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300">
                                    {{ $pf->complexity }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                @if ($pf->is_included_in_package)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        ✓ Termasuk dalam Paket
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        ＋ Add-on Tambahan
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono text-slate-300">
                                {{ $pf->selling_price !== null ? 'Rp ' . number_format((float)$pf->selling_price, 0, ',', '.') : '—' }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold {{ $pf->is_included_in_package ? 'text-slate-500' : 'text-emerald-400' }}">
                                {{ $pf->is_included_in_package ? 'Termasuk (Rp 0)' : 'Rp ' . number_format((float)$pf->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Selected Add-ons Table (If any) -->
    @if ($project->projectAddons->isNotEmpty())
        <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
            <div>
                <h2 class="text-base font-bold text-white">Rincian Add-ons & Custom Development</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-[10px] uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                        <tr>
                            <th class="p-3">Nama Modul Add-on</th>
                            <th class="p-3 text-center">Jumlah (Qty)</th>
                            <th class="p-3 text-right">Harga Jual</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach ($project->projectAddons as $pa)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="p-3 font-semibold text-white">{{ $pa->addon_name }}</td>
                                <td class="p-3 text-center font-mono">{{ $pa->quantity }}</td>
                                <td class="p-3 text-right font-mono text-slate-300">
                                    {{ $pa->selling_price !== null ? 'Rp ' . number_format((float)$pa->selling_price, 0, ',', '.') : ($pa->price_min ? 'Rp ' . number_format((float)$pa->price_min, 0, ',', '.') : '—') }}
                                </td>
                                <td class="p-3 text-right font-mono font-bold text-emerald-400">
                                    Rp {{ number_format((float)$pa->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Included Infrastructure Section -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
        <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
            <span class="text-emerald-400">🛡️</span>
            <span>Infrastruktur & Layanan Pemeliharaan Termasuk</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 space-y-1">
                <div class="text-lg">🖥️</div>
                <div class="font-bold text-white">Hosting / VPS</div>
                <div class="text-[10px] text-slate-400">Performa tinggi</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 space-y-1">
                <div class="text-lg">🌐</div>
                <div class="font-bold text-white">Domain Website</div>
                <div class="text-[10px] text-slate-400">1 tahun sewa</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 space-y-1">
                <div class="text-lg">🔒</div>
                <div class="font-bold text-white">SSL / HTTPS</div>
                <div class="text-[10px] text-slate-400">Enkripsi aman</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 space-y-1">
                <div class="text-lg">💾</div>
                <div class="font-bold text-white">Backup Rutin</div>
                <div class="text-[10px] text-slate-400">Data aman</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 space-y-1 col-span-2 sm:col-span-1">
                <div class="text-lg">🔧</div>
                <div class="font-bold text-white">Maintenance</div>
                <div class="text-[10px] text-slate-400">Update berkala</div>
            </div>
        </div>
    </div>
</div>
@endsection
