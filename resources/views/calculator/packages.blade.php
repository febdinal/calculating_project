@extends('layouts.app')

@section('title', 'Pilihan Paket Website')

@push('styles')
<style>
    @keyframes shimmerGlow {
        0%, 100% {
            background-position: 0% 50%;
            filter: drop-shadow(0 0 8px rgba(244, 63, 94, 0.45));
        }
        50% {
            background-position: 100% 50%;
            filter: drop-shadow(0 0 16px rgba(236, 72, 153, 0.8));
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
<div class="py-12 md:py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Hallmark Tally Hero Section -->
    <div class="text-center max-w-3xl mx-auto space-y-4 mb-14">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-[var(--tally-ink-0)] tracking-tight leading-[1.15]">
            Konfigurasi Fitur & <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-emerald-500 dark:from-indigo-400 dark:via-indigo-300 dark:to-emerald-300 bg-clip-text text-transparent">Kalkulator Biaya</span>
        </h1>
        
        <p class="text-sm sm:text-base text-[var(--tally-ink-2)] max-w-2xl mx-auto leading-relaxed font-normal">
            Pilih paket dasar sebagai pondasi atau mulai dari paket custom. Sesuaikan dan kustomisasi sub-fitur di papan Kanban interaktif berikutnya.
        </p>
    </div>

    <!-- Hallmark Tally 4-Column Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 items-stretch">
        @foreach ($packages as $pkg)
            @php
                $isPopular = ($pkg->slug === 'gold');
                $isCustom = ($pkg->slug === 'custom');
            @endphp
            <div class="relative rounded-3xl tally-card p-6 flex flex-col justify-between transition-all duration-200 {{ $isPopular ? 'border-indigo-500/50 shadow-[0_0_35px_-5px_rgba(99,102,241,0.18)] dark:shadow-[0_0_35px_-5px_rgba(99,102,241,0.25)] ring-1 ring-indigo-500/30' : ($isCustom ? 'border-pink-500/30 shadow-[0_0_35px_-5px_rgba(244,63,94,0.12)] dark:shadow-[0_0_35px_-5px_rgba(244,63,94,0.15)]' : '') }}">
                
                @if ($isPopular)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full bg-indigo-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-md border border-indigo-400/30">
                        ★ Paling Populer
                    </div>
                @elseif ($isCustom)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full bg-gradient-to-r from-pink-600 to-rose-600 text-white text-[10px] font-mono font-bold uppercase tracking-wider shadow-md border border-pink-400/30">
                        ⚡ Full Fleksibel
                    </div>
                @endif

                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <h3 class="text-lg font-bold text-[var(--tally-ink-0)] tracking-tight">{{ $pkg->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full {{ $isCustom ? 'bg-pink-500/10 text-pink-700 dark:text-pink-300 border border-pink-500/20' : 'bg-[var(--tally-subtle-bg)] text-[var(--tally-ink-1)] border border-[var(--tally-card-border)]' }} text-[11px] font-mono font-medium">
                            {{ $pkg->features_count > 0 ? $pkg->features_count . ' Fitur' : 'Bebas Pilih' }}
                        </span>
                    </div>

                    <p class="text-xs text-[var(--tally-ink-2)] leading-relaxed min-h-[46px] mb-5">
                        {{ $pkg->description }}
                    </p>

                    <!-- Price Box (Uniform & Single Line Fit) -->
                    <div class="p-3.5 rounded-2xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] mb-5 min-h-[70px] flex flex-col justify-center">
                        <p class="text-[10px] font-mono uppercase tracking-wider text-[var(--tally-ink-2)] mb-0.5">Harga Paket Dasar</p>
                        
                        @if ($isCustom)
                            <div class="flex items-center mt-0.5">
                                <span class="animate-shimmer-glow text-base xl:text-lg font-black tracking-wider uppercase font-mono">
                                    ✨ AFFORDABLE
                                </span>
                            </div>
                        @else
                            <div class="flex items-baseline gap-1 mt-0.5 whitespace-nowrap overflow-hidden">
                                <span class="text-sm xl:text-base font-bold text-[var(--tally-ink-0)] font-mono tracking-tight shrink-0">
                                    Rp {{ number_format($pkg->calculated_price, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] xl:text-[11px] text-[var(--tally-ink-2)] font-mono shrink-0">/ {{ $pkg->period }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Features Checklist -->
                    <div class="space-y-2 mb-6">
                        <p class="text-[10px] font-mono uppercase tracking-wider text-[var(--tally-ink-2)]">
                            {{ $isCustom ? 'Keunggulan Custom:' : 'Fitur Bawaan Paket:' }}
                        </p>
                        
                        @if ($isCustom || $pkg->features->isEmpty())
                            <ul class="space-y-2 text-xs text-[var(--tally-ink-1)]">
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-600 dark:text-pink-400 font-bold">✓</span>
                                    <span>Mulai dari nol (Kanvas Kosong)</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-600 dark:text-pink-400 font-bold">✓</span>
                                    <span>Bebas pilih fitur apa saja</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-600 dark:text-pink-400 font-bold">✓</span>
                                    <span>Kustomisasi sub-fitur penuh</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-pink-600 dark:text-pink-400 font-bold">✓</span>
                                    <span>Biaya realtime sesuai pilihan</span>
                                </li>
                            </ul>
                        @else
                            <ul class="space-y-2 text-xs text-[var(--tally-ink-1)]">
                                @foreach ($pkg->features->take(4) as $feat)
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓</span>
                                        <span class="truncate">{{ $feat->name }}</span>
                                    </li>
                                @endforeach
                                @if ($pkg->features->count() > 4)
                                    <li class="text-[11px] text-indigo-600 dark:text-indigo-400 font-mono font-medium pl-5">
                                        + {{ $pkg->features->count() - 4 }} fitur lainnya
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('calculator', ['package' => $pkg->slug]) }}" class="tally-btn w-full py-3 px-4 rounded-xl {{ $isPopular ? 'bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-[0_4px_20px_rgba(79,70,229,0.3)]' : ($isCustom ? 'bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-bold shadow-[0_4px_20px_rgba(244,63,94,0.3)]' : 'bg-[var(--tally-subtle-bg)] hover:bg-[var(--tally-paper-3)] text-[var(--tally-ink-0)] font-semibold border border-[var(--tally-card-border)]') }} text-center text-xs transition-all flex items-center justify-center gap-2 group">
                        <span>{{ $isCustom ? 'Rancang Paket Custom' : 'Pilih Paket ' . $pkg->name }}</span>
                        <span class="group-hover:translate-x-0.5 transition-transform font-mono">&rarr;</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Information Footnote -->
    <div class="mt-14 text-center text-xs text-[var(--tally-ink-2)] max-w-xl mx-auto font-mono">
        <span>💡 Fleksibel & Transparan:</span> Anda dapat menambah atau mengurangi fitur pada papan Kanban di langkah berikutnya.
    </div>
</div>
@endsection
