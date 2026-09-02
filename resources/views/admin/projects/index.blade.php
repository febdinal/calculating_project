@extends('layouts.admin')

@section('title', 'Daftar Proyek & Snapshot Biaya')
@section('page-title', 'Daftar Proyek (Project Snapshots)')
@section('page-subtitle', 'Riwayat proyek yang dikonfigurasikan klien dengan snapshot harga, biaya modal, dan estimasi laba')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-4 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.projects.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama proyek, nama klien, email, atau perusahaan..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="status" onchange="this.form.submit()"
                class="w-full sm:w-auto px-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending / Diajukan</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved / Disetujui</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed / Selesai</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected / Ditolak</option>
            </select>

            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.projects.index') }}" class="text-xs text-rose-400 hover:underline whitespace-nowrap">Reset</a>
            @endif
        </form>
    </div>

    <!-- Projects Table -->
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase font-bold text-slate-400 bg-slate-800/60 border-b border-slate-700/60">
                    <tr>
                        <th class="p-4">Nama Proyek & Klien</th>
                        <th class="p-4">Paket Pilihan</th>
                        <th class="p-4 text-center">Fitur / Addon</th>
                        <th class="p-4 text-right">Selling Price</th>
                        <th class="p-4 text-right">Cost Price (Secret)</th>
                        <th class="p-4 text-right">Profit Kotor</th>
                        <th class="p-4 text-center">Margin</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-xs">
                    @forelse ($projects as $project)
                        @php
                            $margin = $project->calculateMarginPercentage();
                            $statusColors = [
                                'draft' => 'bg-slate-700 text-slate-300',
                                'pending' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
                                'approved' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
                                'completed' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
                                'rejected' => 'bg-rose-500/20 text-rose-300 border border-rose-500/30',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-white text-sm">{{ $project->name }}</div>
                                <div class="text-slate-400 text-[11px] mt-0.5">
                                    {{ $project->customer_name ?: 'Anonim' }}
                                    @if ($project->customer_company)
                                        &bull; <span class="text-slate-300">{{ $project->customer_company }}</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5">{{ $project->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                    {{ $project->package?->name ?? 'Custom' }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-mono text-slate-300">
                                <span class="text-indigo-400">{{ $project->project_features_count }} F</span> / 
                                <span class="text-rose-400">{{ $project->project_addons_count }} A</span>
                            </td>
                            <td class="p-4 text-right font-mono font-bold text-white">
                                Rp {{ number_format((float)$project->total_selling_price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right font-mono text-amber-300">
                                Rp {{ number_format((float)$project->total_cost_price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right font-mono font-semibold text-emerald-400">
                                Rp {{ number_format((float)$project->total_profit, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                @if ($margin !== null)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono {{ $margin >= 35 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($margin >= 20 ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30') }}">
                                        {{ $margin }}%
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2.5 py-0.5 rounded text-[10px] uppercase font-bold {{ $statusColors[$project->status] ?? 'bg-slate-700 text-slate-300' }}">
                                    {{ $project->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-lg transition-colors" title="Lihat Detail Snapshot">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Hapus proyek ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors" title="Hapus Proyek">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500">
                                Belum ada proyek yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($projects->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
