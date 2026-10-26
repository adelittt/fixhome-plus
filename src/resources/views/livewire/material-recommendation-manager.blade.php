<div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
        <div>
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Susun Rekomendasi Material</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Rekomendasikan material yang tepat agar pelanggan dapat mempersiapkan bahan pekerjaan.</p>
        </div>
        <button 
            type="button" 
            wire:click="addItem" 
            class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-colors flex items-center gap-1.5"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Butir Material</span>
        </button>
    </div>

    <!-- Alert Keamanan Aturan Bisnis AB-007 -->
    <div class="mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-start gap-3">
        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <strong class="text-slate-800">Prinsip Rekomendasi (AB-007):</strong>
            Anda hanya bertindak memberikan rekomendasi teknis kebutuhan material. Anda tidak dituntut membeli material dengan dana pribadi dan sistem tidak membuat transaksi otomatis atas nama Anda.
        </div>
    </div>

    <!-- Flash Pesan Sukses / Error -->
    @if (session()->has('recommendation_success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('recommendation_success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit="saveRecommendation" class="space-y-4">
        <!-- List Butir Rekomendasi -->
        @foreach ($items as $index => $item)
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 relative space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Butir #{{ $index + 1 }}</span>
                    @if (count($items) > 1)
                        <button 
                            type="button" 
                            wire:click="removeItem({{ $index }})" 
                            class="text-xs text-rose-500 hover:text-rose-700 font-semibold transition-colors flex items-center gap-1"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus Butir</span>
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Material <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="items.{{ $index }}.material_name" 
                            placeholder="Contoh: Pipa PVC AW 1/2 inch" 
                            class="w-full rounded-xl border-slate-200 text-xs p-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error("items.{$index}.material_name") <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Jumlah <span class="text-rose-500">*</span></label>
                        <input 
                            type="number" 
                            min="1" 
                            wire:model="items.{{ $index }}.quantity" 
                            class="w-full rounded-xl border-slate-200 text-xs p-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error("items.{$index}.quantity") <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Satuan <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="items.{{ $index }}.unit" 
                            placeholder="batang / roll / pcs / sak" 
                            class="w-full rounded-xl border-slate-200 text-xs p-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error("items.{$index}.unit") <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Spesifikasi Detail (Opsional)</label>
                        <input 
                            type="text" 
                            wire:model="items.{{ $index }}.specification" 
                            placeholder="Contoh: Panjang 4m, tebal standar SNI merek Rucika/Wavin" 
                            class="w-full rounded-xl border-slate-200 text-xs p-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Catatan Tambahan Butir (Opsional)</label>
                        <input 
                            type="text" 
                            wire:model="items.{{ $index }}.notes" 
                            placeholder="Contoh: Pastikan ada drat kuningan" 
                            class="w-full rounded-xl border-slate-200 text-xs p-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Catatan Umum -->
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Rekomendasi Umum dari Teknisi (Opsional)</label>
            <textarea 
                wire:model="notes" 
                rows="2" 
                placeholder="Pesan atau saran umum untuk pelanggan terkait pemilihan material..." 
                class="w-full rounded-2xl border-slate-200 text-xs p-3 focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
        </div>

        <div class="pt-3 flex justify-end">
            <button 
                type="submit" 
                class="px-6 py-2.5 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan & Kirim Rekomendasi</span>
            </button>
        </div>
    </form>
</div>
