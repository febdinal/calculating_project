@extends('layouts.admin')

@section('title', 'Manajemen Paket Sewa')
@section('page-title', 'Paket Sewa (Packages)')
@section('page-subtitle', 'Kelola paket sewa tahunan e-commerce, harga dasar, target pengguna, dan matriks fitur')

@section('header-actions')
    <a href="{{ route('admin.packages.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/20 flex items-center gap-2 transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Paket Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($packages as $package)
            <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 shadow-xl flex flex-col justify-between relative group hover:border-indigo-500/50 transition-all duration-200">
                <!-- Status Badge -->
                <div class="flex items-center justify-between mb-4">
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md {{ $package->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                        {{ $package->status }}
                    </span>
                    @if ($package->is_featured)
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 rounded-md">
                            Featured
                        </span>
                    @endif
                </div>

                <!-- Package Info -->
                <div class="space-y-3">
                    <h2 class="text-xl font-bold text-white tracking-tight">{{ $package->name }}</h2>
                    <div class="text-2xl font-black text-emerald-400 font-mono">
                        {{ $package->formatted_price }}
                        @if (!$package->isCustomPriced())
                            <span class="text-xs font-normal text-slate-400 font-sans">/ {{ $package->billing_period === 'annual' ? 'tahun' : 'bulan' }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">{{ $package->description }}</p>

                    @if ($package->target_user)
                        <div class="p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60 text-xs text-slate-300">
                            <span class="font-semibold text-slate-400 block text-[10px] uppercase">Target Pengguna:</span>
                            {{ $package->target_user }}
                        </div>
                    @endif

                    <div class="pt-3 border-t border-slate-800 grid grid-cols-2 gap-2 text-center text-xs">
                        <div class="p-2 rounded-lg bg-slate-800/40">
                            <span class="text-slate-400 block text-[10px]">Fitur Termasuk</span>
                            <span class="text-sm font-bold text-indigo-400">{{ $package->included_count }} Fitur</span>
                        </div>
                        <div class="p-2 rounded-lg bg-slate-800/40">
                            <span class="text-slate-400 block text-[10px]">Total Proyek</span>
                            <span class="text-sm font-bold text-cyan-400">{{ $package->projects_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions Button Group -->
                <div class="pt-5 mt-4 border-t border-slate-800/80 flex flex-col gap-2">
                    <a href="{{ route('admin.packages.features', $package) }}" class="w-full py-2 px-3 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 hover:border-indigo-500/60 font-semibold rounded-xl text-xs flex items-center justify-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Atur Matriks Fitur ({{ $package->included_count }})</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="flex-1 py-1.5 px-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg text-xs text-center transition-colors">
                            Edit Paket
                        </a>
                        <form action="{{ route('admin.packages.toggle-status', $package) }}" method="POST">
                            @csrf
                            <button type="submit" class="py-1.5 px-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs transition-colors" title="Toggle Status Aktif">
                                {{ $package->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
