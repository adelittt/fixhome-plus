<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]" x-data="{ activeTab: 'ringkasan' }">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Status Pesanan -->
        <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card p-6 sm:p-8 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-xs font-bold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider">No. Pesanan</span>
                    <span class="text-base font-extrabold text-[#14432A] dark:text-[#3FAE6D] font-mono bg-[#F2FAF5] dark:bg-[#14432A] px-2.5 py-0.5 rounded-lg border border-[#E3EBE5] dark:border-[#1F6B3E]">{{ $order->order_number }}</span>
                </div>
                <h1 class="text-2xl font-black text-[#14432A] dark:text-[#F2FAF5] tracking-tight">
                    {{ $order->category?->name ?? 'Layanan Perbaikan Rumah' }}
                </h1>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-1">
                    Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>

            <!-- Status Badge & Mode Simulasi -->
            <div class="flex flex-col sm:items-end gap-1.5">
                <div class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Status Pesanan Jasa</div>
                @php
                    $statusVariants = [
                        'menunggu_teknisi' => 'warning',
                        'diterima' => 'info',
                        'berlangsung' => 'info',
                        'selesai' => 'success',
                        'ditolak' => 'danger',
                        'dibatalkan' => 'neutral',
                    ];
                    $badgeVar = $statusVariants[$order->status->value] ?? 'neutral';
                @endphp
                <x-ui.badge :variant="$badgeVar" size="md" dot>
                    {{ $order->status->label() }}
                </x-ui.badge>
                <span class="text-[10px] text-[#9A6F14] bg-[#FEF8E7] px-2 py-0.5 rounded-full border border-[#F5DE9B]">
                    Mode Simulasi
                </span>
            </div>
        </div>

        <!-- Tab Controls (Ringkasan, Obrolan, Rekomendasi Material) -->
        <div class="mb-6 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex space-x-2 overflow-x-auto scrollbar-none" role="tablist">
            <button
                type="button"
                @click="activeTab = 'ringkasan'"
                :class="activeTab === 'ringkasan' ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D] font-bold' : 'border-transparent text-[#5E6B60] dark:text-[#BFE3CC] font-medium'"
                class="py-3 px-5 border-b-2 text-sm transition focus:outline-none flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Ringkasan Pesanan</span>
            </button>

            <button
                type="button"
                @click="activeTab = 'chat'"
                :class="activeTab === 'chat' ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D] font-bold' : 'border-transparent text-[#5E6B60] dark:text-[#BFE3CC] font-medium'"
                class="py-3 px-5 border-b-2 text-sm transition focus:outline-none flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Obrolan Kerja</span>
            </button>

            <button
                type="button"
                @click="activeTab = 'material'"
                :class="activeTab === 'material' ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D] font-bold' : 'border-transparent text-[#5E6B60] dark:text-[#BFE3CC] font-medium'"
                class="py-3 px-5 border-b-2 text-sm transition focus:outline-none flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Rekomendasi Material</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#DFF2E6] text-[#14432A]">
                    Opsional
                </span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Utama Sesuai Tab (lg: 8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Panel 1: Ringkasan Pesanan -->
                <div x-show="activeTab === 'ringkasan'" class="space-y-6">
                    <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card p-6 sm:p-8">
                        <h2 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] mb-4 pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Informasi Layanan &amp; Teknisi</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                            <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-2xl p-4 border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] block mb-1">Teknisi Ditugaskan</span>
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] block text-base">{{ $order->technician->name }}</span>
                                <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">{{ $order->technician->phone ?? '-' }}</span>
                            </div>
                            <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-2xl p-4 border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] block mb-1">Pelanggan Pemesan</span>
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] block text-base">{{ $order->customer->name }}</span>
                                <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">{{ $order->customer->phone ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-xs font-semibold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider block mb-1">Jadwal Kunjungan</span>
                                <div class="flex items-center gap-2 text-[#1B2B20] dark:text-[#F2FAF5] font-medium">
                                    <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ $order->scheduled_at->translatedFormat('l, d F Y - H:i') }} WIB</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-xs font-semibold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider block mb-1">Alamat Lokasi Pekerjaan</span>
                                <div class="flex items-start gap-2 text-[#1B2B20] dark:text-[#F2FAF5]">
                                    <svg class="w-4 h-4 text-[#2E8B57] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span>{{ $order->address }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-xs font-semibold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider block mb-1">Deskripsi Kerusakan</span>
                                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-2xl p-4 text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                    {{ $order->job_description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 2: Obrolan Kerja -->
                <div x-show="activeTab === 'chat'" style="display: none;">
                    <livewire:service-order-chat :order="$order" />
                </div>

                <!-- Panel 3: Rekomendasi Material -->
                <div x-show="activeTab === 'material'" style="display: none;" class="space-y-6">
                    @if (auth()->id() === $order->technician_id && in_array($order->status->value, ['diterima', 'berlangsung']))
                        <livewire:material-recommendation-manager :order="$order" />
                    @endif

                    <livewire:material-recommendation-viewer :order="$order" />
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Finansial & Aksi Cepat (lg: 4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Kartu Rincian Finansial Transparan -->
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card p-6">
                    <h2 class="text-sm font-bold text-[#14432A] dark:text-[#F2FAF5] mb-4 pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between">
                        <span>Rincian Biaya Transparan</span>
                        <span class="text-xs font-normal text-[#5E6B60] dark:text-[#BFE3CC]">Jarak: {{ number_format($order->distance_km, 2) }} km</span>
                    </h2>

                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between items-center text-[#5E6B60] dark:text-[#BFE3CC]">
                            <span>Tarif Dasar Jasa</span>
                            <span class="font-semibold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($order->base_service_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-[#5E6B60] dark:text-[#BFE3CC]">
                            <div>
                                <span>Biaya Tambahan Jarak</span>
                                <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC] ml-1">(&ge; 10 km = Rp10.000)</span>
                            </div>
                            <span class="font-semibold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($order->distance_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex justify-between items-center text-sm">
                            <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Total Biaya Jasa</span>
                            <span class="text-lg font-black text-[#14432A] dark:text-[#3FAE6D]">Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Cepat Beralih ke Obrolan / Material -->
                    <div class="mt-6 pt-4 border-t border-[#E3EBE5] dark:border-[#1F6B3E] space-y-2">
                        <button
                            type="button"
                            @click="activeTab = 'chat'"
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-[#F2FAF5] hover:bg-[#DFF2E6] text-[#14432A] dark:bg-[#14432A]/50 dark:text-[#F2FAF5] dark:hover:bg-[#14432A] border border-[#BFE3CC] dark:border-[#1F6B3E] transition flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span>Buka Obrolan Teknisi</span>
                        </button>

                        <button
                            type="button"
                            @click="activeTab = 'material'"
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-[#14432A] hover:bg-[#1F6B3E] text-white transition flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Lihat Tab Material</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
