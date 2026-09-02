@extends('layouts.admin')

@section('title', 'Kelola Paket')
@section('page-title', 'Manajemen Paket Website')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-400">Kelola daftar paket website, harga, periode, dan fitur bawaan.</p>
        <a href="{{ route('admin.packages.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-indigo-600/30">
            <span>+ Tambah Paket</span>
        </a>
    </div>

    <!-- Packages Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-800/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Urutan</th>
                        <th class="py-3.5 px-4">Nama Paket</th>
                        <th class="py-3.5 px-4">Harga</th>
                        <th class="py-3.5 px-4">Periode</th>
                        <th class="py-3.5 px-4">Fitur Bawaan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse ($packages as $package)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-slate-400">{{ $package->sort_order }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-white">{{ $package->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ Str::limit($package->description, 50) }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-indigo-400">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 capitalize">
                                {{ $package->period }}
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.packages.features', $package) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-300 hover:bg-indigo-500/20 border border-indigo-500/20 font-semibold transition-colors">
                                    <span>✓</span>
                                    <span>{{ $package->features_count }} Fitur</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.packages.toggle-status', $package) }}">
                                    @csrf
                                    <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $package->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-700 text-slate-400' }}">
                                        {{ $package->status }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.packages.features', $package) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium" title="Atur Fitur">
                                        Fitur
                                    </a>
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" onsubmit="return confirm('Hapus paket {{ $package->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 text-[11px] font-medium border border-rose-500/20">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada paket. Silakan tambahkan paket baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
