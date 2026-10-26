<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header & Navigasi Tab Pesanan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Riwayat Pesanan Material</h1>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-1">Pantau status konfirmasi toko mitra, pengiriman kurir instan, dan resi material Anda.</p>
            </div>

            <!-- Tab Switcher (Jasa vs Material) -->
            <div class="inline-flex p-1 rounded-2xl bg-white dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm text-xs font-bold self-start sm:self-auto">
                <a href="{{ route('customer.orders') }}" class="px-4 py-2 rounded-xl text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white transition" wire:navigate>
                    Pesanan Jasa
                </a>
                <a href="{{ route('customer.product-orders') }}" class="px-4 py-2 rounded-xl bg-[#14432A] text-white shadow-xs" wire:navigate>
                    Pesanan Material
                </a>
            </div>
        </div>

        <!-- Flash Pesan -->
        @if (session()->has('order_success') || session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('order_success') ?? session('success') }}</span>
            </div>
        @endif

        @if ($orders->isEmpty())
            <x-ui.empty-state
                title="Belum Ada Pesanan Material"
                description="Anda belum pernah memesan material bangunan di Marketplace FixHome Plus."
            >
                <x-ui.button :href="route('marketplace.index')" variant="primary" size="md" wire:navigate>
                    Jelajahi Marketplace Material
                </x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="space-y-6">
                @foreach ($orders as $order)
                    @php
                        $statusVariants = [
                            'menunggu_pembayaran' => 'warning',
                            'dibayar' => 'info',
                            'diproses_toko' => 'info',
                            'siap_dikirim' => 'info',
                            'dikirim' => 'info',
                            'selesai' => 'success',
                            'dibatalkan' => 'danger',
                        ];
                        $badgeVar = $statusVariants[$order->status->value] ?? 'neutral';
                    @endphp
                    <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card card-hover-effect">
                        <!-- Header Kartu Pesanan -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-[#E3EBE5] dark:border-[#1F6B3E] gap-3">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] bg-[#F2FAF5] dark:bg-[#14432A] px-2.5 py-1 rounded-lg border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                    {{ $order->order_number }}
                                </span>
                                <div class="flex items-center gap-1.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                                    <svg class="w-3.5 h-3.5 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $order->store->name }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <x-ui.badge :variant="$badgeVar" size="sm" dot>
                                    {{ $order->status->label() }}
                                </x-ui.badge>
                                <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">
                                    {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Daftar Butir Produk -->
                        <div class="space-y-3 mb-6">
                            @foreach ($order->items as $item)
                                <div class="flex items-center justify-between text-xs py-1.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-center text-[#2E8B57] font-bold shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $item->product_name }}</p>
                                            <p class="text-[#5E6B60] dark:text-[#BFE3CC]">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-[#14432A] dark:text-[#3FAE6D]">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer: Total Biaya & Aksi Pelacakan -->
                        <div class="pt-4 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-0.5 text-xs">
                                <div class="text-[#5E6B60] dark:text-[#BFE3CC]">
                                    Ongkir Kurir Instan (Estimasi): <strong>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</strong>
                                </div>
                                <div class="text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
                                    Total Pembayaran: <span class="text-lg font-black text-[#14432A] dark:text-[#3FAE6D]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                @if (in_array($order->status->value, ['dikirim', 'siap_dikirim', 'selesai']))
                                    <x-ui.button
                                        :href="route('customer.product-order.track', $order->id)"
                                        variant="primary"
                                        size="sm"
                                        wire:navigate
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span>Lacak Kurir Instan</span>
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
