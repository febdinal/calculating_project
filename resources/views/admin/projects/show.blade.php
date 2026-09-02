@extends('layouts.admin')

@section('title', 'Detail Proyek: ' . $project->name)
@section('page-title', 'Detail Proyek: ' . $project->name)
@section('page-subtitle', 'Snapshot harga beku saat proyek disimpan, perincian fitur terpilih, biaya modal, dan estimasi laba')

@section('header-actions')
    <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">
        &larr; Kembali ke Daftar Proyek
    </a>
@endsection

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Top Summary Banner -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client & Project Info -->
        <div class="lg:col-span-2 bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase 
                        {{ $project->status === 'approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : '' }}
                        {{ $project->status === 'pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : '' }}
                        {{ $project->status === 'completed' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : '' }}
                        {{ $project->status === 'draft' ? 'bg-slate-800 text-slate-400' : '' }}
                        {{ $project->status === 'rejected' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : '' }}">
                        Status: {{ strtoupper($project->status) }}
                    </span>
                    <h2 class="text-xl font-bold text-white mt-2">{{ $project->name }}</h2>
                    <p class="text-xs text-slate-400">Dibuat pada {{ $project->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>

                <div class="text-right">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 block">Paket Pilihan:</span>
                    <span class="text-base font-bold text-indigo-400">{{ $project->package?->name ?? 'Web Custom' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-800 text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Nama Klien:</span>
                    <span class="font-semibold text-white">{{ $project->customer_name ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Perusahaan:</span>
                    <span class="font-semibold text-white">{{ $project->customer_company ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Email:</span>
                    <span class="font-mono text-indigo-300">{{ $project->customer_email ?: '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">No. Telepon / WhatsApp:</span>
                    <span class="font-mono text-emerald-400">{{ $project->customer_phone ?: '—' }}</span>
                </div>
            </div>

            @if ($project->notes)
                <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/60 text-xs space-y-1">
                    <span class="font-bold text-slate-300 block">Catatan Tambahan:</span>
                    <p class="text-slate-400 whitespace-pre-line">{{ $project->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Financial Summary Card (Internal Admin) -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-800/80 rounded-2xl p-6 shadow-xl flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Finansial</h3>
                    <span class="text-[10px] px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30 font-mono">Secret</span>
                </div>

                <div class="space-y-3 mt-4 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Paket Sewa (Snapshot):</span>
                        <span class="font-mono text-white">Rp {{ number_format((float)$project->package_price_snapshot, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Selling Price Total:</span>
                        <span class="font-mono font-bold text-white text-sm">Rp {{ number_format((float)$project->total_selling_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-400 font-semibold">Cost Modal Internal:</span>
                        <span class="font-mono text-amber-300 font-semibold">Rp {{ number_format((float)$project->total_cost_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-emerald-400 font-bold">Laba Kotor (Profit):</span>
                        <span class="font-mono font-black text-emerald-400 text-base">Rp {{ number_format((float)$project->total_profit, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Margin Keuntungan:</span>
                        <span class="font-mono font-bold text-purple-300 text-sm">
                            {{ $project->calculateMarginPercentage() ?? 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Updater Form -->
            <form action="{{ route('admin.projects.status', $project) }}" method="POST" class="pt-4 border-t border-slate-800 space-y-3">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status-select" class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Ubah Status Proyek</label>
                    <select id="status-select" name="status"
                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="draft" {{ $project->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ $project->status === 'pending' ? 'selected' : '' }}>Pending (Diajukan Klien)</option>
                        <option value="approved" {{ $project->status === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed (Selesai Dikerjakan)</option>
                        <option value="rejected" {{ $project->status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div>
                    <input type="text" name="notes" placeholder="Catatan admin baru (opsional)..."
                        class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-xs shadow transition-all">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <!-- Snapshot Features Table -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Snapshot Fitur Terpilih ({{ $project->projectFeatures->count() }} Fitur)</h3>
                <p class="text-xs text-slate-400">Harga dan status included tersimpan secara permanen pada saat konfigurasi disimpan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-[10px] uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                    <tr>
                        <th class="p-3">Fitur</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3 text-center">Kompleksitas</th>
                        <th class="p-3 text-center">Status Paket</th>
                        <th class="p-3 text-right">Selling Price</th>
                        <th class="p-3 text-right">Cost Price (Secret)</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($project->projectFeatures as $pf)
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
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        ✓ Included in Package
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        ＋ Add-on Tambahan
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono text-slate-300">
                                {{ $pf->selling_price !== null ? 'Rp ' . number_format((float)$pf->selling_price, 0, ',', '.') : '—' }}
                            </td>
                            <td class="p-3 text-right font-mono text-amber-300">
                                {{ $pf->cost_price !== null ? 'Rp ' . number_format((float)$pf->cost_price, 0, ',', '.') : '—' }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold {{ $pf->is_included_in_package ? 'text-slate-500' : 'text-emerald-400' }}">
                                {{ $pf->is_included_in_package ? 'Included (Rp 0)' : 'Rp ' . number_format((float)$pf->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">
                                Tidak ada fitur yang terdaftar dalam proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Snapshot Add-ons Table -->
    @if ($project->projectAddons->isNotEmpty())
        <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl space-y-4">
            <div>
                <h3 class="text-base font-bold text-white">Snapshot Add-ons Terpilih ({{ $project->projectAddons->count() }} Add-on)</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-[10px] uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                        <tr>
                            <th class="p-3">Nama Add-on</th>
                            <th class="p-3 text-center">Jumlah (Qty)</th>
                            <th class="p-3 text-right">Selling Price</th>
                            <th class="p-3 text-right">Cost Price (Secret)</th>
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
                                <td class="p-3 text-right font-mono text-amber-300">
                                    {{ $pa->cost_price !== null ? 'Rp ' . number_format((float)$pa->cost_price, 0, ',', '.') : '—' }}
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
</div>
@endsection
