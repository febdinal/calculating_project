@extends('layouts.admin')

@section('title', 'Dashboard Master Data')
@section('page-title', 'Ringkasan Master Data')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Paket -->
        <div class="p-5 rounded-2xl tally-card relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--tally-ink-2)] font-mono">Total Paket</p>
                    <p class="text-2xl font-bold text-[var(--tally-ink-0)] mt-1 font-mono">{{ $totalPackages }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-xl">
                    📦
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[var(--tally-card-border)] flex items-center justify-between text-[11px] font-mono">
                <a href="{{ route('admin.packages.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                    Kelola Paket &rarr;
                </a>
            </div>
        </div>

        <!-- Kategori -->
        <div class="p-5 rounded-2xl tally-card relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--tally-ink-2)] font-mono">Total Kategori</p>
                    <p class="text-2xl font-bold text-[var(--tally-ink-0)] mt-1 font-mono">{{ $totalCategories }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-xl">
                    🏷️
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[var(--tally-card-border)] flex items-center justify-between text-[11px] font-mono">
                <a href="{{ route('admin.categories.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline font-semibold flex items-center gap-1">
                    Kelola Kategori &rarr;
                </a>
            </div>
        </div>

        <!-- Fitur Utama -->
        <div class="p-5 rounded-2xl tally-card relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--tally-ink-2)] font-mono">Fitur Utama</p>
                    <p class="text-2xl font-bold text-[var(--tally-ink-0)] mt-1 font-mono">{{ $totalMainFeatures }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-xl">
                    ⚡
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[var(--tally-card-border)] flex items-center justify-between text-[11px] font-mono">
                <a href="{{ route('admin.features.index') }}" class="text-cyan-600 dark:text-cyan-400 hover:underline font-semibold flex items-center gap-1">
                    Kelola Fitur &rarr;
                </a>
            </div>
        </div>

        <!-- Sub Fitur -->
        <div class="p-5 rounded-2xl tally-card relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--tally-ink-2)] font-mono">Total Sub Fitur</p>
                    <p class="text-2xl font-bold text-[var(--tally-ink-0)] mt-1 font-mono">{{ $totalSubFeatures }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-xl">
                    📑
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[var(--tally-card-border)] flex items-center justify-between text-[11px] font-mono">
                <span class="text-[var(--tally-ink-2)]">Hierarki Rincian Fitur</span>
            </div>
        </div>
    </div>

    <!-- Quick Overview Sections (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Packages Card -->
        <div class="p-6 rounded-2xl tally-card">
            <div class="flex items-center justify-between pb-4 border-b border-[var(--tally-card-border)]">
                <h3 class="text-sm font-bold text-[var(--tally-ink-0)] flex items-center gap-2">
                    <span>📦</span>
                    <span>Daftar Paket Aktif</span>
                </h3>
                <a href="{{ route('admin.packages.create') }}" class="tally-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold font-mono">
                    + Tambah Paket
                </a>
            </div>
            <div class="mt-4 space-y-3">
                @foreach ($packages as $pkg)
                    <div class="p-3.5 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-bold text-[var(--tally-ink-0)]">{{ $pkg->name }}</p>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-semibold {{ $pkg->status === 'active' ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                    {{ $pkg->status }}
                                </span>
                            </div>
                            <p class="text-[11px] text-[var(--tally-ink-2)] mt-0.5 font-mono">{{ $pkg->features_count }} Fitur Bawaan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                                {{ $pkg->slug === 'custom' ? '✨ Affordable' : 'Rp ' . number_format($pkg->price, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-[var(--tally-ink-2)] font-mono">/ {{ $pkg->period }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Categories & Features Overview -->
        <div class="p-6 rounded-2xl tally-card">
            <div class="flex items-center justify-between pb-4 border-b border-[var(--tally-card-border)]">
                <h3 class="text-sm font-bold text-[var(--tally-ink-0)] flex items-center gap-2">
                    <span>🏷️</span>
                    <span>Kategori & Jumlah Fitur</span>
                </h3>
                <a href="{{ route('admin.categories.create') }}" class="tally-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold font-mono">
                    + Tambah Kategori
                </a>
            </div>
            <div class="mt-4 space-y-3">
                @foreach ($categories as $cat)
                    <div class="p-3.5 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-base">{{ $cat->icon ?? '🏷️' }}</span>
                            <div>
                                <p class="text-xs font-bold text-[var(--tally-ink-0)]">{{ $cat->name }}</p>
                                <p class="text-[11px] text-[var(--tally-ink-2)]">{{ $cat->description }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-[var(--tally-paper-1)] text-[var(--tally-ink-1)] border border-[var(--tally-card-border)] text-xs font-bold font-mono">
                            {{ $cat->main_features_count }} Fitur
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
