<!DOCTYPE html>
<html lang="id" class="h-full light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Funix Admin Console</title>
    
    <!-- Instant Theme Detection Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.add('light');
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Geist, Geist Mono, Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600;700&family=Geist:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-[var(--tally-ink-0)] hallmark-bg flex flex-col min-h-screen">
    <!-- Mobile Topbar (< md) -->
    <header class="md:hidden sticky top-0 z-40 h-15 border-b border-[var(--tally-card-border)] bg-[var(--tally-paper-1)]/90 backdrop-blur-xl px-4 flex items-center justify-between transition-colors">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-full bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] p-1 flex items-center justify-center shadow-xs">
                <img src="{{ asset('images/logo.png') }}" alt="Funix Logo" class="w-4 h-4 object-contain dark:invert">
            </div>
            <div>
                <h1 class="text-xs font-bold text-[var(--tally-ink-0)] leading-tight font-mono">Funix Console</h1>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <!-- Theme Toggle Button Mobile -->
            <button type="button" onclick="toggleThemeMode()" class="w-7 h-7 rounded-full bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] text-xs flex items-center justify-center hover:scale-105 active:scale-95 transition-all text-[var(--tally-ink-1)]" title="Ganti Mode Light / Dark">
                <span class="theme-icon-slot">☀️</span>
            </button>

            <!-- Mobile Hamburger Button -->
            <button type="button" onclick="toggleMobileNav()" id="mobileMenuBtn" class="w-8 h-8 rounded-xl bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] flex items-center justify-center text-[var(--tally-ink-0)] hover:bg-[var(--tally-paper-3)] transition-colors" aria-label="Toggle navigation">
                <svg id="hamburgerIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg id="closeIcon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer (< md) -->
    <div id="mobileNavDrawer" class="hidden md:hidden border-b border-[var(--tally-card-border)] bg-[var(--tally-paper-1)]/95 backdrop-blur-2xl px-5 py-4 space-y-3 font-mono transition-all">
        <p class="text-[10px] font-bold uppercase tracking-wider text-[var(--tally-ink-2)]">Master Data Menu</p>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-[var(--tally-ink-1)] bg-[var(--tally-subtle-bg)] hover:bg-[var(--tally-paper-3)]' }}">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-[var(--tally-ink-1)] bg-[var(--tally-subtle-bg)] hover:bg-[var(--tally-paper-3)]' }}">
                <span>📦</span>
                <span>Paket</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-[var(--tally-ink-1)] bg-[var(--tally-subtle-bg)] hover:bg-[var(--tally-paper-3)]' }}">
                <span>🏷️</span>
                <span>Kategori</span>
            </a>
            <a href="{{ route('admin.features.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.features.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-[var(--tally-ink-1)] bg-[var(--tally-subtle-bg)] hover:bg-[var(--tally-paper-3)]' }}">
                <span>⚡</span>
                <span>Fitur & Sub</span>
            </a>
        </div>
        <div class="pt-2 border-t border-[var(--tally-card-border)] flex items-center justify-between text-xs">
            <a href="{{ route('calculator') }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 font-semibold flex items-center gap-1.5">
                <span>🚀 Lihat Kalkulator</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-rose-600 dark:text-rose-400 font-semibold flex items-center gap-1">
                    <span>🚪 Logout</span>
                </button>
            </form>
        </div>
    </div>

    <div class="flex-1 flex flex-col md:flex-row">
        <!-- Desktop Sidebar Navigation (>= md) -->
        <aside class="hidden md:flex w-64 bg-[var(--tally-paper-1)] border-r border-[var(--tally-card-border)] p-5 flex-col justify-between shrink-0 transition-colors">
            <div>
                <!-- Brand -->
                <div class="flex items-center gap-3 pb-6 border-b border-[var(--tally-card-border)]">
                    <div class="w-9 h-9 rounded-full bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] p-1.5 flex items-center justify-center shadow-xs">
                        <img src="{{ asset('images/logo.png') }}" alt="Funix Logo" class="w-6 h-6 object-contain dark:invert">
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-[var(--tally-ink-0)] leading-tight font-mono">Funix Console</h1>
                        <p class="text-[11px] text-[var(--tally-ink-2)] font-mono">Admin Control Bench</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 space-y-1.5 font-mono">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-[var(--tally-ink-2)] px-3 pb-2">Master Data</p>

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-[var(--tally-ink-1)] hover:bg-[var(--tally-subtle-bg)] hover:text-[var(--tally-ink-0)]' }}">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-[var(--tally-ink-1)] hover:bg-[var(--tally-subtle-bg)] hover:text-[var(--tally-ink-0)]' }}">
                        <span>📦</span>
                        <span>Paket</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-[var(--tally-ink-1)] hover:bg-[var(--tally-subtle-bg)] hover:text-[var(--tally-ink-0)]' }}">
                        <span>🏷️</span>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('admin.features.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.features.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-[var(--tally-ink-1)] hover:bg-[var(--tally-subtle-bg)] hover:text-[var(--tally-ink-0)]' }}">
                        <span>⚡</span>
                        <span>Fitur & Sub Fitur</span>
                    </a>
                </nav>
            </div>

            <!-- Footer / Quick Links -->
            <div class="pt-6 border-t border-[var(--tally-card-border)] space-y-2 font-mono">
                <a href="{{ route('calculator') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium text-[var(--tally-ink-2)] hover:bg-[var(--tally-subtle-bg)] hover:text-[var(--tally-ink-0)] transition-colors">
                    <span class="flex items-center gap-2">
                        <span>🚀</span>
                        <span>Lihat Kalkulator</span>
                    </span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <span>🚪</span>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Admin Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-transparent">
            <!-- Desktop Admin Topbar (>= md) -->
            <header class="hidden md:flex h-16 border-b border-[var(--tally-card-border)] bg-[var(--tally-paper-1)]/60 backdrop-blur-xl px-6 items-center justify-between gap-4 transition-colors">
                <div>
                    <h2 class="text-sm font-bold text-[var(--tally-ink-0)]">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle Button -->
                    <button type="button" onclick="toggleThemeMode()" id="adminThemeToggleBtn" class="w-7 h-7 rounded-full bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] text-xs flex items-center justify-center hover:scale-105 active:scale-95 transition-all text-[var(--tally-ink-1)]" title="Ganti Mode Light / Dark">
                        <span class="theme-icon-slot">☀️</span>
                    </button>

                    <div class="text-right font-mono">
                        <p class="text-xs font-semibold text-[var(--tally-ink-0)]">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-indigo-600 dark:text-indigo-400">Admin Mode</p>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 md:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-[#0f291e]/90 border border-emerald-500/40 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between font-mono">
                        <div class="flex items-center gap-2">
                            <span>✓</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-[#290f14]/90 border border-rose-500/40 text-rose-800 dark:text-rose-300 text-xs font-semibold font-mono">
                        <p class="font-bold mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Theme Toggle, Mobile Nav & Rupiah Input Masker Script -->
    <script>
        function toggleMobileNav() {
            const drawer = document.getElementById('mobileNavDrawer');
            const hamburgerIcon = document.getElementById('hamburgerIcon');
            const closeIcon = document.getElementById('closeIcon');
            if (drawer) {
                const isHidden = drawer.classList.contains('hidden');
                if (isHidden) {
                    drawer.classList.remove('hidden');
                    hamburgerIcon?.classList.add('hidden');
                    closeIcon?.classList.remove('hidden');
                } else {
                    drawer.classList.add('hidden');
                    hamburgerIcon?.classList.remove('hidden');
                    closeIcon?.classList.add('hidden');
                }
            }
        }

        function updateAdminThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon-slot').forEach(el => {
                el.innerText = isDark ? '🌙' : '☀️';
            });
        }

        function toggleThemeMode() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateAdminThemeIcon();
        }

        function formatRupiahJs(amount) {
            if (amount === null || amount === undefined || amount === '') return 'Rp 0';
            const clean = String(amount).replace(/[^0-9]/g, '');
            if (!clean) return 'Rp 0';
            return 'Rp ' + Number(clean).toLocaleString('id-ID');
        }

        function maskRupiah(input) {
            const cursorPosition = input.selectionStart;
            const originalLength = input.value.length;
            const cleanDigits = input.value.replace(/[^0-9]/g, '');

            if (!cleanDigits) {
                input.value = '';
                return;
            }

            const formatted = 'Rp ' + Number(cleanDigits).toLocaleString('id-ID');
            input.value = formatted;

            const newLength = formatted.length;
            const diff = newLength - originalLength;
            input.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateAdminThemeIcon();
            document.querySelectorAll('.input-rupiah').forEach(input => {
                if (input.value && !input.value.startsWith('Rp ')) {
                    input.value = formatRupiahJs(input.value);
                }
                input.addEventListener('input', function() {
                    maskRupiah(this);
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
