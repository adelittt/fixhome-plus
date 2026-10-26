<div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card">
    <div class="pb-4 mb-6 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-bold text-[#14432A] dark:text-[#F2FAF5] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Rekomendasi Material oleh Teknisi</span>
            </h3>
            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">Daftar material dan bahan perbaikan yang disarankan oleh teknisi berdasarkan analisis kerusakan.</p>
        </div>

        @if ($recommendation && $recommendation->revision_count > 0)
            <x-ui.badge variant="warning" size="sm">
                Revisi ke-{{ $recommendation->revision_count }}
            </x-ui.badge>
        @endif
    </div>

    <!-- Flash Pesan -->
    @if (session()->has('choice_success'))
        <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('choice_success') }}</span>
        </div>
    @endif
    @if (session()->has('choice_error'))
        <div class="mb-6 p-4 rounded-2xl bg-[#FDF2F0] dark:bg-[#B3402F]/20 border border-[#F5B8B0] text-[#B3402F] text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('choice_error') }}</span>
        </div>
    @endif

    @if (! $recommendation || $recommendation->items->isEmpty())
        <!-- Belum Ada Rekomendasi -->
        <x-ui.empty-state
            title="Belum Ada Rekomendasi Material"
            description="Teknisi belum menyusun daftar rekomendasi material untuk pesanan ini. Anda dapat mendiskusikannya terlebih dahulu melalui tab Obrolan."
        />
    @else
        <!-- Daftar Butir Material -->
        <div class="space-y-3 mb-6">
            @foreach ($recommendation->items as $idx => $item)
                <div class="p-4 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-[#DFF2E6] dark:bg-[#1F6B3E]/50 text-[#14432A] dark:text-[#3FAE6D] text-xs font-bold flex items-center justify-center shrink-0 border border-[#BFE3CC]">
                                {{ $idx + 1 }}
                            </span>
                            <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] text-sm">{{ $item->material_name }}</span>
                        </div>
                        @if ($item->specification)
                            <div class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] pl-8">
                                <span class="font-medium text-[#1B2B20] dark:text-[#F2FAF5]">Spesifikasi:</span> {{ $item->specification }}
                            </div>
                        @endif
                        @if ($item->notes)
                            <div class="text-[11px] text-[#9A6F14] bg-[#FEF8E7] px-2.5 py-0.5 rounded-md inline-block ml-8 border border-[#F5DE9B]">
                                Catatan: {{ $item->notes }}
                            </div>
                        @endif
                    </div>

                    <div class="sm:text-right shrink-0 pl-8 sm:pl-0">
                        <div class="text-xs font-extrabold text-[#14432A] dark:text-[#3FAE6D] bg-white dark:bg-[#0F2A1A] px-3 py-1.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] inline-block">
                            {{ $item->quantity }} {{ $item->unit }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($recommendation->notes)
            <div class="bg-[#F2FAF5] dark:bg-[#14432A]/40 rounded-2xl p-4 border border-[#BFE3CC] dark:border-[#1F6B3E] text-xs text-[#14432A] dark:text-[#BFE3CC] mb-6">
                <strong class="block mb-1 text-[#14432A] dark:text-[#F2FAF5] font-bold">Pesan Khusus dari Teknisi:</strong>
                {{ $recommendation->notes }}
            </div>
        @endif

        <!-- Pilihan Marketplace Setara (PRD 4.2 & AB-006) -->
        <div class="pt-6 border-t border-[#E3EBE5] dark:border-[#1F6B3E]">
            <div class="mb-5">
                <span class="text-xs font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC] block mb-1">
                    Pilihan Pemenuhan Material
                </span>
                <!-- PENEGASAN WAJIB ATURAN 1 -->
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                    <strong class="text-[#14432A] dark:text-[#3FAE6D]">Pembelian material bersifat opsional.</strong> Anda dapat membeli material yang direkomendasikan melalui Marketplace Mitra Toko FixHome Plus atau menyediakannya secara mandiri. Memilih "Lewati Marketplace" tidak membatalkan atau memengaruhi pesanan jasa Anda.
                </p>
            </div>

            <!-- Status Pilihan Saat Ini -->
            @if ($order->marketplace_choice->value !== 'belum_dipilih')
                <div class="mb-5 p-4 rounded-2xl {{ $order->marketplace_choice->value === 'lihat' ? 'bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5]' : 'bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] text-[#1B2B20] dark:text-[#F2FAF5]' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <span class="font-bold">Status Pilihan Anda:</span>
                        @if ($order->marketplace_choice->value === 'lihat')
                            <span class="font-semibold text-[#1F6B3E] dark:text-[#3FAE6D]">Menelusuri Marketplace Material</span>
                        @else
                            <span class="font-semibold text-[#5E6B60] dark:text-[#BFE3CC]">Menyediakan Material Sendiri (Lewati Marketplace)</span>
                        @endif
                        <span class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] font-normal block mt-0.5">Pilihan dapat disesuaikan selama pesanan jasa belum selesai.</span>
                    </div>
                    @if ($order->marketplace_choice->value === 'lihat')
                        <a href="{{ route('marketplace.index', ['pesanan_jasa' => $order->id]) }}" class="px-4 py-2 rounded-xl bg-[#14432A] hover:bg-[#1F6B3E] text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5 shrink-0 self-start sm:self-auto" wire:navigate>
                            <span>Buka Marketplace</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endif
                </div>
            @endif

            <!-- DUA TOMBOL SETARA (PRD 4.2 BUTIR 1 & ATURAN 1) -->
            <!-- Ukuran, bobot visual, dan kontras dibuat 100% sama persis -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Tombol 1: Lihat Marketplace -->
                <button
                    type="button"
                    wire:click="chooseOption('lihat')"
                    class="w-full min-h-[48px] py-3.5 px-6 rounded-2xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 btn-press-effect {{
                        $order->marketplace_choice->value === 'lihat'
                            ? 'bg-[#14432A] text-white ring-4 ring-[#BFE3CC] shadow-md dark:bg-[#2E8B57]'
                            : 'bg-[#14432A] hover:bg-[#1F6B3E] text-white shadow-sm'
                    }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Lihat Marketplace</span>
                </button>

                <!-- Tombol 2: Lewati Marketplace (SETARA UKURAN, BOBOT VISUAL, DAN TAMPILAN) -->
                <button
                    type="button"
                    wire:click="chooseOption('lewati')"
                    class="w-full min-h-[48px] py-3.5 px-6 rounded-2xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 btn-press-effect {{
                        $order->marketplace_choice->value === 'lewati'
                            ? 'bg-[#14432A] text-white ring-4 ring-[#BFE3CC] shadow-md dark:bg-[#2E8B57]'
                            : 'bg-[#14432A] hover:bg-[#1F6B3E] text-white shadow-sm'
                    }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    <span>Lewati Marketplace</span>
                </button>
            </div>
            <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] text-center mt-3 italic">
                * Pembelian material bersifat opsional.
            </p>
        </div>
    @endif
</div>
