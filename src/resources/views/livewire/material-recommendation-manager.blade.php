<div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-6 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
        <div>
            <h3 class="text-lg font-bold text-[#14432A] dark:text-[#F2FAF5] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Susun Rekomendasi Material</span>
            </h3>
            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">Rekomendasikan material yang tepat agar pelanggan dapat mempersiapkan bahan pekerjaan.</p>
        </div>

        <button 
            type="button" 
            wire:click="addItem" 
            class="px-4 py-2.5 rounded-xl bg-[#DFF2E6] hover:bg-[#BFE3CC] dark:bg-[#1F6B3E]/50 dark:hover:bg-[#1F6B3E] text-[#14432A] dark:text-[#F2FAF5] text-xs font-bold transition flex items-center justify-center gap-1.5 border border-[#BFE3CC] dark:border-[#1F6B3E] min-h-[44px] self-start sm:self-auto shadow-sm"
        >
            <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Butir Material</span>
        </button>
    </div>

    <!-- Alert Keamanan Aturan Bisnis AB-007 -->
    <div class="mb-6 p-4 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] text-xs text-[#14432A] dark:text-[#BFE3CC] flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-[#2E8B57] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="leading-relaxed">
            <strong class="font-bold text-[#14432A] dark:text-[#F2FAF5] block mb-0.5">Prinsip Rekomendasi (AB-007):</strong>
            Anda hanya bertindak memberikan rekomendasi teknis kebutuhan material. Anda tidak dituntut membeli material dengan dana pribadi dan sistem tidak membuat transaksi otomatis atas nama Anda.
        </div>
    </div>

    <!-- Flash Pesan Sukses / Error -->
    @if (session()->has('recommendation_success'))
        <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-xs font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('recommendation_success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 rounded-2xl bg-[#FDF2F0] dark:bg-[#B3402F]/20 border border-[#F5B8B0] text-[#B3402F] text-xs font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-[#B3402F] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit="saveRecommendation" class="space-y-5">
        <!-- List Butir Rekomendasi -->
        <div class="space-y-4">
            @foreach ($items as $index => $item)
                <div class="p-4 sm:p-5 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] relative space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                        <span class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#2E8B57]"></span>
                            <span>Butir Material #{{ $index + 1 }}</span>
                        </span>

                        @if (count($items) > 1)
                            <button 
                                type="button" 
                                wire:click="removeItem({{ $index }})" 
                                class="text-xs text-[#B3402F] hover:text-[#B3402F]/80 font-bold transition flex items-center gap-1 min-h-[36px] px-2 rounded-lg hover:bg-[#FDF2F0]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                        <div class="sm:col-span-6">
                            <label class="block text-[11px] font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Nama Material <span class="text-[#B3402F]">*</span></label>
                            <input 
                                type="text" 
                                wire:model="items.{{ $index }}.material_name" 
                                placeholder="Contoh: Pipa PVC AW 1/2 inch" 
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                            @error("items.{$index}.material_name") <span class="text-[10px] text-[#B3402F] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Jumlah <span class="text-[#B3402F]">*</span></label>
                            <input 
                                type="number" 
                                min="1" 
                                wire:model="items.{{ $index }}.quantity" 
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                            @error("items.{$index}.quantity") <span class="text-[10px] text-[#B3402F] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Satuan <span class="text-[#B3402F]">*</span></label>
                            <input 
                                type="text" 
                                wire:model="items.{{ $index }}.unit" 
                                placeholder="batang / roll / pcs / sak" 
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                            @error("items.{$index}.unit") <span class="text-[10px] text-[#B3402F] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-[11px] font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Spesifikasi Detail (Opsional)</label>
                            <input 
                                type="text" 
                                wire:model="items.{{ $index }}.specification" 
                                placeholder="Contoh: Panjang 4m, tebal standar SNI merek Rucika/Wavin" 
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-[11px] font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Catatan Tambahan Butir (Opsional)</label>
                            <input 
                                type="text" 
                                wire:model="items.{{ $index }}.notes" 
                                placeholder="Contoh: Pastikan ada drat kuningan" 
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Catatan Umum Teknisi -->
        <div>
            <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1.5">Catatan Rekomendasi Umum dari Teknisi (Opsional)</label>
            <textarea 
                wire:model="notes" 
                rows="3" 
                placeholder="Pesan atau petunjuk teknis umum untuk pelanggan terkait pemilihan material bangunan..." 
                class="w-full rounded-2xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20"
            ></textarea>
        </div>

        <div class="pt-2 flex justify-end">
            <button 
                type="submit" 
                class="px-6 py-3 rounded-xl font-bold text-xs bg-[#14432A] hover:bg-[#1F6B3E] text-white shadow-md shadow-[#14432A]/20 transition flex items-center justify-center gap-2 min-h-[44px]"
            >
                <svg class="w-4 h-4 text-[#3FAE6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan &amp; Kirim Rekomendasi</span>
            </button>
        </div>
    </form>
</div>
