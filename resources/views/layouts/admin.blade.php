<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Website Feature Configurator</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-100 bg-slate-950 flex flex-col min-h-screen">
    <div class="flex-1 flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900/90 border-r border-slate-800/80 p-5 flex flex-col justify-between shrink-0">
            <div>
                <!-- Brand -->
                <div class="flex items-center gap-3 pb-6 border-b border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-white leading-tight">Admin Configurator</h1>
                        <p class="text-[11px] text-slate-400">Website Cost Calculator</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 space-y-1.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-2">Master Data</p>

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>📦</span>
                        <span>Paket</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>🏷️</span>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('admin.features.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors {{ request()->routeIs('admin.features.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>⚡</span>
                        <span>Fitur & Sub Fitur</span>
                    </a>
                </nav>
            </div>

            <!-- Footer / Quick Links -->
            <div class="pt-6 border-t border-slate-800 space-y-2">
                <a href="{{ route('calculator') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                    <span class="flex items-center gap-2">
                        <span>🚀</span>
                        <span>Lihat Kalkulator</span>
                    </span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-medium text-rose-400 hover:bg-rose-950/40 transition-colors">
                        <span>🚪</span>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Admin Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-950">
            <!-- Admin Topbar -->
            <header class="h-16 border-b border-slate-800/80 bg-slate-900/50 backdrop-blur-lg px-6 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm font-bold text-white">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-semibold text-white">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-indigo-400">Admin</p>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs font-semibold flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span>✓</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs font-semibold">
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
    <script>
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
