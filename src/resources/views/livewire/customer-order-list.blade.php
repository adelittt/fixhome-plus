<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Pesanan Jasa Saya</h1>
                <p class="text-[#5E6B60] dark:text-[#BFE3CC] text-xs sm:text-sm mt-1">Pantau status konfirmasi teknisi, jadwal kunjungan, dan diskusikan pekerjaan Anda.</p>
            </div>

            <!-- Tab Switcher (Jasa vs Material) & CTA -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="inline-flex p-1 rounded-2xl bg-white dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm text-xs font-bold">
                    <a href="{{ route('customer.orders') }}" class="px-4 py-2 rounded-xl bg-[#14432A] text-white shadow-xs" wire:navigate>
                        Pesanan Jasa
                    </a>
                    <a href="{{ route('customer.product-orders') }}" class="px-4 py-2 rounded-xl text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white transition" wire:navigate>
                        Pesanan Material
                    </a>
                </div>

                <x-ui.button
                    :href="route('service.catalog')"
                    variant="primary"
                    size="sm"
                    wire:navigate
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Pesan Jasa Baru</span>
                </x-ui.button>
            </div>
        </div>

        @if ($orders->isEmpty())
            <x-ui.empty-state
                title="Belum Ada Pesanan Jasa"
                description="Anda belum memiliki riwayat pemesanan jasa perbaikan rumah di FixHome Plus."
            >
                <x-ui.button :href="route('service.catalog')" variant="primary" size="md" wire:navigate>
                    Cari Teknisi Sekarang
                </x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
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
                    <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card card-hover-effect">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-2.5 flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] font-mono bg-[#F2FAF5] dark:bg-[#14432A] px-2.5 py-0.5 rounded-lg border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                        {{ $order->order_number }}
                                    </span>
                                    <x-ui.badge :variant="$badgeVar" size="sm" dot>
                                        {{ $order->status->label() }}
                                    </x-ui.badge>
                                </div>
                                <h3 class="text-lg font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
                                    {{ $order->category?->name ?? 'Layanan Jasa' }}
                                </h3>
                                <div class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] flex flex-wrap items-center gap-x-4 gap-y-1">
                                    <span>Teknisi: <strong class="text-[#1B2B20] dark:text-[#F2FAF5]">{{ $order->technician->name }}</strong></span>
                                    <span>Jadwal: <strong class="text-[#1B2B20] dark:text-[#F2FAF5]">{{ $order->scheduled_at->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                                </div>
                                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] line-clamp-1 leading-relaxed">{{ $order->job_description }}</p>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-6 shrink-0 border-t md:border-t-0 pt-4 md:pt-0 border-[#E3EBE5] dark:border-[#1F6B3E]">
                                <div class="text-left md:text-right">
                                    <span class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] block">Total Biaya</span>
                                    <span class="text-lg font-black text-[#14432A] dark:text-[#3FAE6D]">
                                        Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}
                                    </span>
                                </div>

                                <x-ui.button
                                    :href="route('service.order.detail', $order->id)"
                                    variant="secondary"
                                    size="sm"
                                    wire:navigate
                                >
                                    <span>Lihat Detail</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
