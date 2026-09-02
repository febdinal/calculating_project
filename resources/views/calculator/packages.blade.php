@extends('layouts.app')

@section('title', 'Pilihan Paket Website')

@push('styles')
<style>
    @keyframes shimmerGlow {
        0%, 100% {
            background-position: 0% 50%;
            filter: drop-shadow(0 0 8px rgba(244, 63, 94, 0.5));
        }
        50% {
            background-position: 100% 50%;
            filter: drop-shadow(0 0 16px rgba(236, 72, 153, 0.85));
        }
    }

    .animate-shimmer-glow {
        background: linear-gradient(90deg, #ec4899, #f43f5e, #fb7185, #f59e0b, #ec4899);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmerGlow 3s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="py-12 md:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold">
            <span>✨</span>
            <span>Langkah 1: Pilih Paket Dasar Website</span>
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight">
            Kalkulator Konfigurasi Fitur & <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Estimasi Biaya</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-400 max-w-2xl mx-auto leading-relaxed">
            Pilih paket dasar website atau mulai dari paket custom. Anda dapat menyesuaikan dan menambahkan fitur secara bebas di langkah berikutnya melalui papan Kanban interaktif.
        </p>
    </div>

    <!-- Package Cards Grid (4 Columns) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
        @foreach ($packages as $pkg)
            @php
                $isPopular = ($pkg->slug === 'medium');
                $isCustom = ($pkg->slug === 'custom');
            @endphp
            <div class="relative rounded-3xl bg-slate-900/70 border {{ $isPopular ? 'border-indigo-500/80 shadow-2xl shadow-indigo-500/20 ring-1 ring-indigo-500/50' : ($isCustom ? 'border-pink-500/30 hover:border-pink-500/60 shadow-lg shadow-pink-500/5' : 'border-slate-800') }} p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:border-slate-700">
                @if ($isPopular)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-[10px] font-extrabold uppercase tracking-wider shadow-md">
                        Paling Populer
                    </div>
                @elseif ($isCustom)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 text-white text-[10px] font-extrabold uppercase tracking-wider shadow-md">
                        Full Fleksibel
                    </div>
                @endif

                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <h3 class="text-lg font-bold text-white">{{ $pkg->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-lg {{ $isCustom ? 'bg-pink-500/10 text-pink-300 border border-pink-500/20' : 'bg-slate-800 text-slate-300' }} text-[11px] font-semibold">
                            {{ $pkg->features_count > 0 ? $pkg->features_count . ' Fitur Bawaan' : 'Bebas Pilih' }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-400 leading-relaxed min-h-[48px] mb-5">
                        {{ $pkg->description }}
                    </p>

                    <!-- Price Box (Uniform & Single Line Fit) -->
                    <div class="p-3.5 rounded-2xl bg-slate-800/40 border border-slate-800/80 mb-5 min-h-[72px] flex flex-col justify-center">
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Harga Paket Dasar</p>
                        
                        @if ($isCustom)
                            <div class="flex items-center mt-0.5">
                                <span class="animate-shimmer-glow text-base xl:text-lg font-black tracking-wider uppercase font-mono">
                                    ✨ AFFORDABLE
                                </span>
                            </div>
                        @else
                            <div class="flex items-baseline gap-1 mt-0.5 whitespace-nowrap overflow-hidden">
                                <span class="text-sm xl:text-base font-extrabold text-white font-mono tracking-tight shrink-0">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] xl:text-[11px] text-slate-400 font-medium shrink-0">/ {{ $pkg->period }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Highlighted Features List -->
                    <div class="space-y-2 mb-6">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            {{ $isCustom ? 'Keunggulan Custom:' : 'Termasuk di Paket:' }}
                        </p>
                        
                        @if ($isCustom || $pkg->features->isEmpty())
                            <ul class="space-y-2 text-xs text-slate-300">
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-400">✓</span>
                                    <span>Mulai dari nol (Kanvas Kosong)</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-400">✓</span>
                                    <span>Bebas pilih fitur apa saja</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-400">✓</span>
                                    <span>Kustomisasi sub-fitur penuh</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-400">✓</span>
                                    <span>Biaya realtime sesuai pilihan</span>
                                </li>
                            </ul>
                        @else
                            <ul class="space-y-2 text-xs text-slate-300">
                                @foreach ($pkg->features->take(4) as $feat)
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-400">✓</span>
                                        <span class="truncate">{{ $feat->name }}</span>
                                    </li>
                                @endforeach
                                @if ($pkg->features->count() > 4)
                                    <li class="text-[10px] text-indigo-400 font-medium pl-5">
                                        + {{ $pkg->features->count() - 4 }} fitur lainnya
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('calculator', ['package' => $pkg->slug]) }}" class="w-full py-3 px-4 rounded-xl {{ $isPopular ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold shadow-lg shadow-indigo-600/30' : ($isCustom ? 'bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-bold shadow-lg shadow-pink-600/30' : 'bg-slate-800 hover:bg-slate-700 text-white font-semibold') }} text-center text-xs transition-all flex items-center justify-center gap-2 group">
                        <span>{{ $isCustom ? 'Rancang Paket Custom' : 'Pilih Paket ' . $pkg->name }}</span>
                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Simple Information Note -->
    <div class="mt-16 text-center text-xs text-slate-400 max-w-xl mx-auto">
        💡 <span class="font-semibold text-slate-300">Fleksibel & Transparan:</span> Anda dapat menambah atau mengurangi fitur pada papan Kanban di halaman berikutnya untuk melihat total kalkulasi secara realtime.
    </div>
</div>
@endsection
