@extends('layouts.admin')

@section('title', 'Tambah Fitur Baru')
@section('page-title', 'Tambah Fitur Baru')
@section('page-subtitle', 'Daftarkan fitur e-commerce baru dan tentukan dependensi jika ada')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-slate-900/90 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('admin.features.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Kategori <span class="text-rose-400">*</span></label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Pilih Kategori...</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Ikon / Emoji</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', '⚙️') }}" placeholder="Contoh: 🛒, 💳, 📦"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-center text-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Nama Fitur <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Pembayaran Online QRIS" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Slug (URL Identitas)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Otomatis jika dikosongkan"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Deskripsi & Cakupan Scope</label>
                <textarea id="description" name="description" rows="3" placeholder="Penjelasan cara kerja dan batasan fungsionalitas fitur..."
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description') }}</textarea>
            </div>

            <!-- Dependencies Selection -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Dependensi / Fitur Prasyarat</label>
                <p class="text-xs text-slate-400 mb-3">Pilih fitur yang wajib ada sebelum fitur ini bisa dipilih di kalkulator (misal: Payment Gateway butuh Checkout Online).</p>
                <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/60 max-h-48 overflow-y-auto space-y-2">
                    @foreach ($otherFeatures as $other)
                        <label class="flex items-center gap-2.5 hover:bg-slate-700/30 p-1.5 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" name="dependencies[]" value="{{ $other->id }}" {{ in_array($other->id, old('dependencies', [])) ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-slate-800 border-slate-600 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs text-slate-200">{{ $other->icon }} {{ $other->name }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">({{ $other->category?->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                <div>
                    <label for="sort_order" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Urutan Tampil</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 1) }}" min="0"
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Status <span class="text-rose-400">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_infrastructure" value="1" {{ old('is_infrastructure') ? 'checked' : '' }}
                            class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-semibold text-slate-300">Termasuk Infrastruktur Dasar (Included)</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('admin.features.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium rounded-xl text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                    Simpan & Atur Harga &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
