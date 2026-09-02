@extends('layouts.admin')

@section('title', 'Add-ons & Custom Development')
@section('page-title', 'Add-ons & Custom Development')
@section('page-subtitle', 'Layanan tambahan di luar paket standar (Aplikasi Mobile, Marketplace, Integrasi ERP/POS, AI, Loyalty)')

@section('header-actions')
    <a href="{{ route('admin.addons.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/20 flex items-center gap-2 transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Add-on Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($addons as $addon)
            @php
                $profit = $addon->calculateProfit();
                $margin = $addon->calculateMarginPercentage();
            @endphp
            <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl flex flex-col justify-between hover:border-slate-700 transition-all">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $addon->icon ?? '🧩' }}</span>
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-400 border border-slate-700">
                                    {{ $addon->category ?? 'Add-on' }}
                                </span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $addon->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400' }}">
                            {{ $addon->status }}
                        </span>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-white">{{ $addon->name }}</h2>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $addon->description }}</p>
                    </div>

                    <!-- Pricing Info Box -->
                    <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/60 space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Tipe Harga:</span>
                            <span class="font-semibold text-white uppercase text-[10px] px-1.5 py-0.5 rounded bg-slate-700">{{ $addon->price_type }}</span>
                        </div>

                        @if ($addon->price_type === 'range')
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Rentang Harga:</span>
                                <span class="font-mono font-bold text-emerald-400">
                                    Rp {{ number_format((float)$addon->price_min, 0, ',', '.') }} – {{ number_format((float)$addon->price_max, 0, ',', '.') }}
                                </span>
                            </div>
                        @elseif ($addon->price_type === 'fixed')
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Selling Price:</span>
                                <span class="font-mono font-bold text-white">
                                    {{ $addon->selling_price ? 'Rp ' . number_format((float)$addon->selling_price, 0, ',', '.') : 'Belum diisi' }}
                                </span>
                            </div>
                        @else
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Harga:</span>
                                <span class="font-semibold text-amber-400">Kustom / Sesuai Kesepakatan</span>
                            </div>
                        @endif

                        <!-- Internal Cost (Admin Only) -->
                        <div class="pt-2 border-t border-slate-700/50 flex justify-between items-center text-[11px]">
                            <span class="text-amber-400/90 font-semibold">Cost Modal Internal:</span>
                            <span class="font-mono text-amber-300">
                                {{ $addon->cost_price !== null ? 'Rp ' . number_format((float)$addon->cost_price, 0, ',', '.') : 'Belum diisi' }}
                            </span>
                        </div>

                        @if ($profit !== null)
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-400">Estimasi Margin:</span>
                                <span class="font-mono font-bold text-purple-300">{{ $margin }}%</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-800 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.addons.edit', $addon) }}" class="flex-1 py-1.5 px-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg text-xs text-center transition-colors">
                        Edit & Harga
                    </a>
                    <form action="{{ route('admin.addons.toggle-status', $addon) }}" method="POST">
                        @csrf
                        <button type="submit" class="py-1.5 px-3 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-slate-200 rounded-lg text-xs transition-colors">
                            {{ $addon->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
