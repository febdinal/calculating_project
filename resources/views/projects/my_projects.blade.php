@extends('layouts.app')

@section('title', 'Riwayat Proyek Tersimpan')

@section('content')
<div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Riwayat Proyek & Quotation Anda</h1>
            <p class="text-xs text-slate-400">Daftar konfigurasi toko online yang telah Anda simpan atau ajukan</p>
        </div>

        <a href="{{ route('calculator') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-2 transition-all w-max">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Konfigurasi Baru</span>
        </a>
    </div>

    @if ($projects->isEmpty())
        <div class="p-12 text-center text-slate-500 border border-dashed border-slate-800 rounded-3xl bg-slate-900/60 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-2xl mx-auto">
                📋
            </div>
            <h3 class="text-base font-bold text-white">Belum Ada Proyek Tersimpan</h3>
            <p class="text-xs text-slate-400 max-w-sm mx-auto">Gunakan kalkulator berbasis Kanban untuk merancang fitur toko online Anda dan simpan konfigurasinya.</p>
            <div class="pt-2">
                <a href="{{ route('calculator') }}" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl inline-block shadow">
                    Buka Kalkulator Sekarang &rarr;
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach ($projects as $project)
                @php
                    $statusBadge = match($project->status) {
                        'approved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'pending' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                        'completed' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                        'rejected' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                        default => 'bg-slate-800 text-slate-400 border-slate-700',
                    };
                @endphp
                <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800/80 hover:border-indigo-500/40 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xl">
                    <div class="space-y-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-white truncate">{{ $project->name }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $statusBadge }}">
                                {{ $project->status }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-400 flex flex-wrap items-center gap-2">
                            <span>Paket: <strong class="text-indigo-300">{{ $project->package?->name ?? 'Custom' }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ $project->project_features_count }} Fitur</span>
                            <span>&bull;</span>
                            <span>{{ $project->project_addons_count }} Add-on</span>
                            <span>&bull;</span>
                            <span class="text-slate-500">{{ $project->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-800">
                        <div class="text-right">
                            <span class="text-[10px] text-slate-500 block">Total Investasi</span>
                            <span class="text-base font-bold font-mono text-emerald-400">
                                Rp {{ number_format((float)$project->total_selling_price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('projects.show', $project) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg transition-colors">
                                Lihat Rincian &rarr;
                            </a>
                            <a href="{{ route('projects.pdf', $project) }}" target="_blank" class="p-1.5 bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 rounded-lg transition-colors" title="Download PDF Quotation">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
