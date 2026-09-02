@extends('layouts.app')

@section('title', 'Pilihan Paket E-Commerce')

@section('content')
<div class="py-12 sm:py-16 space-y-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold">
            <span>✨</span>
            <span>Perencanaan Anggaran Teknis E-Commerce — Model Paket Sewa Tahunan</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
            Pilih Paket Dasar, <br class="hidden sm:inline">
            <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Sesuaikan Kebutuhan Fitur Anda</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-400 leading-relaxed">
            Aplikasi E-Commerce Project Configurator visual berbasis Kanban. Pilih paket awal yang paling sesuai, lalu tambahkan atau kurangi fitur secara interaktif.
        </p>
    </div>

    <!-- 4 Package Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
        @foreach ($packages as $package)
            @php
                $isMedium = $package->slug === 'medium';
                $isProfessional = $package->slug === 'professional';
                $isCustom = $package->slug === 'web-custom';
            @endphp
            <div class="bg-slate-900/90 border {{ $isMedium ? 'border-indigo-500 ring-2 ring-indigo-500/30 shadow-indigo-500/10' : 'border-slate-800/80' }} rounded-3xl p-6 sm:p-7 shadow-2xl flex flex-col justify-between relative group hover:border-slate-700 transition-all duration-300">
                @if ($isMedium)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-[11px] font-bold uppercase tracking-wider rounded-full shadow-lg">
                        ⭐ Paling Populer (Rekomendasi)
                    </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Paket Sewa</span>
                            <span class="text-xl">
                                {{ $package->slug === 'basic' ? '🌱' : ($isMedium ? '🚀' : ($isProfessional ? '👑' : '💎')) }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight">{{ $package->name }}</h2>
                        <p class="text-xs text-slate-400 mt-2 min-h-[36px] leading-relaxed">{{ $package->description }}</p>
                    </div>

                    <!-- Price Display -->
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                        <div class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight">
                            {{ $package->formatted_price }}
                        </div>
                        @if (!$package->isCustomPriced())
                            <span class="text-xs text-slate-400 block mt-0.5">/ tahun (sewa layanan lengkap)</span>
                        @else
                            <span class="text-xs text-amber-400 block mt-0.5">Scope & kompleksitas khusus</span>
                        @endif
                    </div>

                    <!-- Target Segment -->
                    @if ($package->target_user)
                        <div class="text-xs text-slate-300 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Cocok Untuk:</span>
                            <p class="text-slate-300 font-medium leading-relaxed">{{ $package->target_user }}</p>
                        </div>
                    @endif

                    <!-- Key Feature Highlights -->
                    <div class="space-y-2 pt-2 border-t border-slate-800 text-xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Fitur Inti:</span>
                        @if ($package->slug === 'basic')
                            <ul class="space-y-1.5 text-slate-300">
                                <li class="flex items-center gap-2">✓ <span>Katalog Produk Online</span></li>
                                <li class="flex items-center gap-2">✓ <span>Pemesanan via WhatsApp</span></li>
                                <li class="flex items-center gap-2">✓ <span>Website Responsive & Banner</span></li>
                                <li class="flex items-center gap-2">✓ <span>SEO Dasar & Google Maps</span></li>
                            </ul>
                        @elseif ($isMedium)
                            <ul class="space-y-1.5 text-slate-300">
                                <li class="flex items-center gap-2">✓ <span>Keranjang Belanja & Checkout</span></li>
                                <li class="flex items-center gap-2">✓ <span>Payment Gateway Online</span></li>
                                <li class="flex items-center gap-2">✓ <span>Integrasi Ongkir Ekspedisi</span></li>
                                <li class="flex items-center gap-2">✓ <span>Akun Pelanggan & Laporan</span></li>
                            </ul>
                        @elseif ($isProfessional)
                            <ul class="space-y-1.5 text-slate-300">
                                <li class="flex items-center gap-2">✓ <span>Semua Fitur Paket Medium</span></li>
                                <li class="flex items-center gap-2">✓ <span>Voucher, Wishlist & Review</span></li>
                                <li class="flex items-center gap-2">✓ <span>Multi-role & Notifikasi WA</span></li>
                                <li class="flex items-center gap-2">✓ <span>SEO Lanjutan & API Integrasi</span></li>
                            </ul>
                        @else
                            <ul class="space-y-1.5 text-slate-300">
                                <li class="flex items-center gap-2">✓ <span>Full Custom Architecture</span></li>
                                <li class="flex items-center gap-2">✓ <span>Integrasi Sistem Khusus / ERP</span></li>
                                <li class="flex items-center gap-2">✓ <span>Skalabilitas Dedicated</span></li>
                                <li class="flex items-center gap-2">✓ <span>SLA & Support Prioritas</span></li>
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <a href="{{ route('calculator', ['package' => $package->slug]) }}"
                        class="w-full py-3 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all duration-200 {{ $isMedium ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 hover:border-slate-600' }}">
                        <span>Konfigurasikan Paket Ini</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Included Infrastructure Section -->
    <div class="bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-slate-950 border border-slate-800/80 rounded-3xl p-6 sm:p-10 shadow-2xl">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="max-w-xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
                    <span>🛡️</span>
                    <span>Sudah Termasuk Dalam Semua Paket</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Infrastruktur & Pemeliharaan Teknis
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Setiap paket sewa tahunan sudah memperhitungkan kebutuhan infrastruktur dasar tanpa biaya tambahan tersembunyi.
                </p>
            </div>

            <!-- 5 Infrastructure Pillars -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                <div class="p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/60 space-y-1">
                    <div class="text-lg">🖥️</div>
                    <div class="font-bold text-white">Hosting / VPS</div>
                    <div class="text-[11px] text-slate-400">Server handal & uptime tinggi</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/60 space-y-1">
                    <div class="text-lg">🌐</div>
                    <div class="font-bold text-white">Domain Website</div>
                    <div class="text-[11px] text-slate-400">Registrasi & perpanjangan</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/60 space-y-1">
                    <div class="text-lg">🔒</div>
                    <div class="font-bold text-white">SSL / HTTPS</div>
                    <div class="text-[11px] text-slate-400">Keamanan enkripsi data</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/60 space-y-1">
                    <div class="text-lg">💾</div>
                    <div class="font-bold text-white">Backup Otomatis</div>
                    <div class="text-[11px] text-slate-400">Pencadangan data berkala</div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/60 space-y-1 sm:col-span-2">
                    <div class="text-lg">🔧</div>
                    <div class="font-bold text-white">Maintenance Teknis</div>
                    <div class="text-[11px] text-slate-400">Update patch keamanan & troubleshooting operasional</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add-ons Showcase -->
    <div class="space-y-6">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <h2 class="text-2xl font-bold text-white tracking-tight">Kebutuhan Add-on & Pengembangan Khusus</h2>
            <p class="text-xs text-slate-400">Dapat ditambahkan ke paket mana pun secara fleksibel melalui papan Kanban.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach ($addons as $addon)
                <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-indigo-500/40 transition-colors space-y-2">
                    <div class="text-2xl">{{ $addon->icon ?? '🧩' }}</div>
                    <h3 class="font-bold text-sm text-white">{{ $addon->name }}</h3>
                    <p class="text-[11px] text-slate-400 line-clamp-2">{{ $addon->description }}</p>
                    <div class="pt-2 text-xs font-mono font-semibold text-emerald-400">
                        {{ $addon->formatted_price }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
