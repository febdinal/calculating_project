<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Website Feature Configurator') — Kalkulator Fitur & Estimasi Biaya</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-100 bg-slate-950 selection:bg-indigo-500 selection:text-white flex flex-col">
    <!-- Ambient Background Glow -->
    <div class="fixed top-0 left-1/4 w-[600px] h-[350px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-1/4 w-[600px] h-[350px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-base tracking-tight text-white flex items-center gap-1.5">
                        Website Feature <span class="text-indigo-400 font-extrabold">Configurator</span>
                    </span>
                    <p class="text-[11px] text-slate-400 font-medium">Kalkulator Fitur & Estimasi Biaya</p>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1.5">
                <a href="{{ route('packages.select') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('packages.select') || request()->routeIs('home') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-900' }}">
                    1. Pilihan Paket
                </a>
                <a href="{{ route('calculator') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('calculator') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-900' }}">
                    2. Kanban Configurator
                </a>
            </nav>

            <!-- Action Buttons / Admin Link -->
            <div class="flex items-center gap-3">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-indigo-500/30 text-indigo-300 text-xs font-semibold flex items-center gap-1.5 transition-all">
                            <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                            <span>Admin Panel</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-xs font-medium text-slate-400 hover:text-rose-400 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl text-slate-400 hover:text-slate-200 text-xs font-semibold transition-colors">
                        Admin Login
                    </a>
                @endauth

                <a href="{{ route('calculator') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center gap-2">
                    <span>Buka Kalkulator</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-slate-950 py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} Website Feature Configurator — Kalkulator Fitur & Estimasi Biaya.</p>
            <div class="flex items-center gap-4 text-slate-400">
                <span>Konfigurasi Cepat &bull; Realtime Calculation &bull; Unduh PDF Langsung</span>
            </div>
        </div>
    </footer>

    <!-- Global Toast Script -->
    <script>
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto px-4 py-3 rounded-xl border text-xs font-semibold shadow-2xl flex items-center gap-2.5 transition-all duration-300 transform translate-y-4 opacity-0 ${
                type === 'success' ? 'bg-emerald-950/90 border-emerald-500/40 text-emerald-300' :
                type === 'warning' ? 'bg-amber-950/90 border-amber-500/40 text-amber-300' :
                type === 'error' ? 'bg-rose-950/90 border-rose-500/40 text-rose-300' :
                'bg-slate-900/90 border-slate-700 text-slate-200'
            }`;

            toast.innerHTML = `
                <span>${type === 'success' ? '✓' : type === 'warning' ? '⚠️' : type === 'error' ? '✕' : 'ℹ️'}</span>
                <span>${message}</span>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }
    </script>
    @stack('scripts')
</body>
</html>
