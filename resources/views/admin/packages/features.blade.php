@extends('layouts.admin')

@section('title', 'Fitur Paket ' . $package->name)
@section('page-title', 'Fitur Bawaan: Paket ' . $package->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs text-slate-400">Pilih fitur-fitur yang sudah termasuk ke dalam harga paket <span class="text-white font-bold">{{ $package->name }}</span> (Rp {{ number_format($package->price, 0, ',', '.') }} / {{ $package->period }}).</p>
        </div>
        <a href="{{ route('admin.packages.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Kembali ke Daftar Paket
        </a>
    </div>

    <form method="POST" action="{{ route('admin.packages.features.update', $package) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Categories and Features Grid -->
        <div class="space-y-6">
            @foreach ($categories as $category)
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <span class="text-lg">{{ $category->icon ?? '🏷️' }}</span>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $category->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $category->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @forelse ($category->mainFeatures as $feature)
                            @php
                                $isChecked = in_array($feature->id, $includedFeatureIds);
                            @endphp
                            <label class="relative flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all {{ $isChecked ? 'bg-indigo-950/40 border-indigo-500/50 text-white' : 'bg-slate-800/30 border-slate-800/80 text-slate-300 hover:border-slate-700' }}">
                                <input type="checkbox" name="feature_ids[]" value="{{ $feature->id }}" {{ $isChecked ? 'checked' : '' }} class="mt-0.5 rounded border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 bg-slate-800">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <p class="text-xs font-bold text-white truncate">{{ $feature->name }}</p>
                                        <span class="text-[10px] font-mono text-indigo-400 shrink-0">Rp {{ number_format($feature->price, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($feature->subFeatures->count() > 0)
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            {{ $feature->subFeatures->count() }} sub-fitur ({{ $feature->subFeatures->pluck('name')->take(2)->join(', ') }}...)
                                        </p>
                                    @endif
                                </div>
                            </label>
                        @empty
                            <p class="col-span-3 text-xs text-slate-400 py-2">Belum ada fitur dalam kategori ini.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <div class="sticky bottom-6 p-4 rounded-2xl bg-slate-900/95 border border-indigo-500/30 backdrop-blur-xl shadow-2xl flex items-center justify-between gap-4">
            <div class="text-xs text-slate-300">
                Centang fitur yang sudah termasuk paket. Fitur yang tidak dicentang akan otomatis menjadi fitur tambahan berbayar bagi pengguna.
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
                    Simpan Fitur Paket
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
