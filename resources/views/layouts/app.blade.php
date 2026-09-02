<!DOCTYPE html>
<html lang="id" class="h-full light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Funix Configurator') — Funix Pricing & Feature Engine</title>
    
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

    <!-- Google Fonts: Geist, Geist Mono, Plus Jakarta Sans, Instrument Serif -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600;700&family=Geist:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-[var(--tally-ink-0)] hallmark-bg selection:bg-indigo-600 selection:text-white flex flex-col min-h-screen">

    <!-- Hallmark N5 Floating Pill Navigation Bar (Fully Responsive) -->
    <div class="fixed top-3 inset-x-0 z-50 flex justify-center px-3 sm:px-4 pointer-events-none">
        <header class="pointer-events-auto flex items-center justify-between gap-1.5 sm:gap-6 px-2.5 sm:px-5 py-2 sm:py-2.5 rounded-full bg-[var(--tally-nav-bg)] backdrop-blur-2xl border border-[var(--tally-card-border)] shadow-[var(--tally-nav-shadow)] max-w-5xl w-full transition-all">
            
            <!-- Brand Mark -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] p-1 flex items-center justify-center shadow-xs">
                    <img src="{{ asset('images/logo.png') }}" alt="Funix Logo" class="w-4 h-4 sm:w-5 sm:h-5 object-contain dark:invert group-hover:scale-105 transition-transform">
                </div>
                <div class="flex items-center gap-1 font-mono">
                    <span class="font-bold text-xs tracking-tight text-[var(--tally-ink-0)]">
                        Funix
                    </span>
                    <span class="hidden md:inline text-indigo-600 dark:text-indigo-400 font-extrabold text-xs">Configurator</span>
                </div>
            </a>

            <!-- Segmented Route Links (Responsive Labels) -->
            <nav class="flex items-center gap-0.5 sm:gap-1 bg-[var(--tally-subtle-bg)] p-0.5 sm:p-1 rounded-full border border-[var(--tally-card-border)] text-[10px] sm:text-[11px] font-medium font-mono shrink-0">
                <a href="{{ route('packages.select') }}" class="px-2.5 sm:px-3 py-1 rounded-full transition-all {{ request()->routeIs('packages.select') || request()->routeIs('home') ? 'bg-indigo-600 text-white font-semibold shadow-xs' : 'text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]' }}">
                    <span class="hidden sm:inline">1. </span>Paket
                </a>
                <a href="{{ route('calculator') }}" class="px-2.5 sm:px-3 py-1 rounded-full transition-all {{ request()->routeIs('calculator') ? 'bg-indigo-600 text-white font-semibold shadow-xs' : 'text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)]' }}">
                    <span class="hidden sm:inline">2. </span>Kanban
                </a>
            </nav>

            <!-- Actions, Theme Switcher & CTA -->
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                <!-- Theme Mode Toggle Button -->
                <button type="button" onclick="toggleThemeMode()" id="themeToggleBtn" class="w-7 h-7 rounded-full bg-[var(--tally-subtle-bg)] border border-[var(--tally-card-border)] text-xs flex items-center justify-center hover:scale-105 active:scale-95 transition-all text-[var(--tally-ink-1)]" title="Ganti Mode Light / Dark">
                    <span id="themeToggleIcon">☀️</span>
                </button>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex px-2.5 sm:px-3 py-1 rounded-full bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-300 text-[10px] sm:text-[11px] font-semibold border border-indigo-500/30 transition-colors font-mono">
                            Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-[11px] text-[var(--tally-ink-2)] hover:text-[var(--tally-ink-0)] px-2 py-1 transition-colors font-medium font-mono">
                        Login
                    </a>
                @endauth

                <a href="{{ route('calculator') }}" class="tally-btn px-2.5 sm:px-3.5 py-1.5 rounded-full bg-indigo-600 hover:bg-indigo-700 dark:bg-white dark:text-[#07090e] dark:hover:bg-slate-200 text-white text-[11px] sm:text-xs font-bold transition-all shadow-[0_2px_10px_rgba(79,70,229,0.25)] dark:shadow-[0_2px_10px_rgba(255,255,255,0.2)] flex items-center gap-1 font-mono">
                    <span>Mulai</span>
                    <span class="text-[10px]">&rarr;</span>
                </a>
            </div>
        </header>
    </div>

    <!-- Main Content Area (Spaced for floating pill nav) -->
    <main class="flex-1 pt-18 sm:pt-20">
        @yield('content')
    </main>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <!-- Hallmark Minimalist Footer -->
    <footer class="border-t border-[var(--tally-card-border)] bg-[var(--tally-paper-1)] py-8 text-xs text-[var(--tally-ink-2)] mt-16 transition-colors">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 font-mono">
            <div class="flex items-center gap-2">
                <span class="tally-dot-live"></span>
                <span>Funix &bull; Website Feature Configurator</span>
            </div>
            <div class="text-[var(--tally-ink-2)]">
                <span>Funix Precision Pricing &bull; Realtime Calculation &bull; Zero Fluff</span>
            </div>
        </div>
    </footer>

    <!-- Theme Toggle & Toast Scripts -->
    <script>
        function updateThemeIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            const iconEl = document.getElementById('themeToggleIcon');
            if (iconEl) {
                iconEl.innerText = isDark ? '🌙' : '☀️';
            }
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
            updateThemeIcon();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon();
        });

        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto px-4 py-2.5 rounded-2xl border text-xs font-semibold shadow-2xl flex items-center gap-2.5 transition-all duration-300 transform translate-y-3 opacity-0 backdrop-blur-xl ${
                type === 'success' ? 'bg-emerald-50 dark:bg-[#0f291e]/90 border-emerald-500/40 text-emerald-800 dark:text-emerald-300' :
                type === 'warning' ? 'bg-amber-50 dark:bg-[#291f0f]/90 border-amber-500/40 text-amber-800 dark:text-amber-300' :
                type === 'error' ? 'bg-rose-50 dark:bg-[#290f14]/90 border-rose-500/40 text-rose-800 dark:text-rose-300' :
                'bg-white dark:bg-[#141b2d]/90 border-[var(--tally-card-border)] text-[var(--tally-ink-0)]'
            }`;

            toast.innerHTML = `
                <span>${type === 'success' ? '✓' : type === 'warning' ? '⚠️' : type === 'error' ? '✕' : 'ℹ️'}</span>
                <span>${message}</span>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-3', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 250);
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>
</html>
