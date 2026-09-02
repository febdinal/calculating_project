@extends('layouts.admin')

@section('title', 'Matriks Fitur: ' . $package->name)
@section('page-title', 'Matriks Fitur: ' . $package->name)
@section('page-subtitle', 'Tentukan status ketersediaan setiap fitur (Included, Add-on Berbayar, atau Tidak Tersedia) untuk paket ini')

@section('header-actions')
    <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">
        &larr; Kembali ke Daftar Paket
    </a>
@endsection

@section('content')
<form action="{{ route('admin.packages.features.update', $package) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Package Summary Banner -->
    <div class="bg-gradient-to-r from-indigo-900/40 via-purple-900/30 to-slate-900 border border-indigo-500/30 rounded-2xl p-6 shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-white">{{ $package->name }}</h2>
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono">
                    {{ $package->formatted_price }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">{{ $package->description }}</p>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="setAllGlobal('included')" class="px-3 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-semibold rounded-lg transition-colors">
                ✓ Set Semua Included
            </button>
            <button type="button" onclick="setAllGlobal('not_available')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-400 text-xs font-semibold rounded-lg transition-colors">
                — Reset Semua Not Available
            </button>
        </div>
    </div>

    <!-- Category Groups with Feature Matrix Rows -->
    <div class="space-y-6">
        @foreach ($categories as $category)
            <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl shadow-xl overflow-hidden">
                <!-- Category Header -->
                <div class="p-4 sm:p-5 bg-slate-800/60 border-b border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">{{ $category->icon ?? '📁' }}</span>
                        <div>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ $category->name }}</h3>
                            <p class="text-xs text-slate-400">{{ $category->description }}</p>
                        </div>
                    </div>

                    <!-- Category Quick Actions -->
                    <div class="flex items-center gap-1.5 text-xs">
                        <button type="button" onclick="setCategoryStatus({{ $category->id }}, 'included')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-emerald-400 font-medium">
                            Set Included
                        </button>
                        <button type="button" onclick="setCategoryStatus({{ $category->id }}, 'optional')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-amber-400 font-medium">
                            Set Optional
                        </button>
                        <button type="button" onclick="setCategoryStatus({{ $category->id }}, 'not_available')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-400 font-medium">
                            Set N/A
                        </button>
                    </div>
                </div>

                <!-- Features Table -->
                <div class="divide-y divide-slate-800/80">
                    @forelse ($category->features as $feature)
                        @php
                            $pivot = $packageFeaturesMap->get($feature->id);
                            $currentStatus = $pivot ? $pivot->status : ($feature->is_infrastructure ? 'included' : 'not_available');
                            $currentNotes = $pivot ? $pivot->notes : '';
                        @endphp
                        <div class="p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:bg-slate-800/30 transition-colors">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">{{ $feature->icon ?? '⚙️' }}</span>
                                    <span class="text-sm font-semibold text-white">{{ $feature->name }}</span>
                                    @if ($feature->is_infrastructure)
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-800 text-slate-400 border border-slate-700">
                                            Infrastruktur Dasar
                                        </span>
                                    @endif
                                </div>
                                @if ($feature->description)
                                    <p class="text-xs text-slate-400 mt-1 pl-6">{{ $feature->description }}</p>
                                @endif
                            </div>

                            <!-- Status Selection Radios -->
                            <div class="flex items-center gap-2 shrink-0">
                                <!-- Included -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="features[{{ $feature->id }}]" value="included" {{ $currentStatus === 'included' ? 'checked' : '' }}
                                        class="peer sr-only cat-{{ $category->id }}-status" data-status="included">
                                    <div class="px-3 py-1.5 rounded-xl border text-xs font-semibold flex items-center gap-1.5 transition-all
                                        border-slate-800 bg-slate-800/50 text-slate-400 
                                        peer-checked:border-emerald-500/50 peer-checked:bg-emerald-500/20 peer-checked:text-emerald-300 peer-checked:shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-slate-600 peer-checked:bg-emerald-400"></span>
                                        <span>✓ Included</span>
                                    </div>
                                </label>

                                <!-- Optional (Add-on) -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="features[{{ $feature->id }}]" value="optional" {{ $currentStatus === 'optional' ? 'checked' : '' }}
                                        class="peer sr-only cat-{{ $category->id }}-status" data-status="optional">
                                    <div class="px-3 py-1.5 rounded-xl border text-xs font-semibold flex items-center gap-1.5 transition-all
                                        border-slate-800 bg-slate-800/50 text-slate-400 
                                        peer-checked:border-amber-500/50 peer-checked:bg-amber-500/20 peer-checked:text-amber-300 peer-checked:shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-slate-600 peer-checked:bg-amber-400"></span>
                                        <span>＋ Add-on</span>
                                    </div>
                                </label>

                                <!-- Not Available -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="features[{{ $feature->id }}]" value="not_available" {{ $currentStatus === 'not_available' ? 'checked' : '' }}
                                        class="peer sr-only cat-{{ $category->id }}-status" data-status="not_available">
                                    <div class="px-3 py-1.5 rounded-xl border text-xs font-semibold flex items-center gap-1.5 transition-all
                                        border-slate-800 bg-slate-800/50 text-slate-400 
                                        peer-checked:border-slate-700 peer-checked:bg-slate-800 peer-checked:text-slate-300">
                                        <span>— N/A</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-500">
                            Belum ada fitur dalam kategori ini.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <!-- Sticky Bottom Bar -->
    <div class="sticky bottom-6 z-30 bg-slate-900/90 border border-slate-800/90 backdrop-blur-xl rounded-2xl p-4 shadow-2xl flex items-center justify-between">
        <div class="text-xs text-slate-400 hidden sm:block">
            Simpan perubahan untuk memperbarui logika kalkulator publik & snapshot baru.
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Matriks Fitur</span>
            </button>
        </div>
    </div>
</form>

<script>
    function setCategoryStatus(categoryId, status) {
        const radios = document.querySelectorAll(`.cat-${categoryId}-status[data-status="${status}"]`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }

    function setAllGlobal(status) {
        const radios = document.querySelectorAll(`input[type="radio"][data-status="${status}"]`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endsection
