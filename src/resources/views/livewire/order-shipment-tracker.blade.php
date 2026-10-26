<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header & Navigasi -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('customer.product-orders') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white transition mb-2" wire:navigate>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Riwayat Pesanan Material</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Pelacakan Kurir Instan</h1>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">Estimasi pengiriman kurir instan hari yang sama (Mode Simulasi).</p>
            </div>

            @if ($order->shipment)
                <div class="bg-white dark:bg-[#14432A]/40 border border-[#E3EBE5] dark:border-[#1F6B3E] px-4 py-2.5 rounded-2xl shadow-card text-left sm:text-right">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC] block">No. Resi Pelacakan</span>
                    <span class="text-sm font-extrabold font-mono text-[#14432A] dark:text-[#3FAE6D]">{{ $order->shipment->tracking_number }}</span>
                    <span class="text-[10px] block text-[#5E6B60] dark:text-[#BFE3CC] font-semibold mt-0.5">{{ $order->shipment->provider_name }}</span>
                </div>
            @endif
        </div>

        <!-- Flash Pesan Simulasi -->
        @if (session()->has('simulation_success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('simulation_success') }}</span>
            </div>
        @endif

        @if (session()->has('simulation_error'))
            <div class="mb-6 p-4 rounded-2xl bg-[#FCE8E6] dark:bg-[#B3402F]/20 border border-[#F5B5AD] text-[#B3402F] dark:text-[#F5B5AD] text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 text-[#B3402F] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('simulation_error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri: Driver & Stepper (lg: 7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Kartu Driver Kurir Instan -->
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 shadow-card">
                    <div class="flex items-center justify-between pb-4 border-b border-[#E3EBE5] dark:border-[#1F6B3E] mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/50 text-[#14432A] dark:text-[#3FAE6D] flex items-center justify-center font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-[#1B2B20] dark:text-[#F2FAF5]">{{ $trackingData['driver_name'] ?? 'Kurir Instan Mitra FixHome' }}</h3>
                                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] font-mono">{{ $trackingData['driver_plate'] ?? 'B 4123 SXZ' }} • {{ $trackingData['driver_phone'] ?? '-' }}</p>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->shipment?->status->value === 'terkirim' ? 'bg-[#DFF2E6] text-[#1F6B3E]' : 'bg-[#FEF8E7] text-[#9A6F14] border border-[#F5DE9B]' }}">
                            {{ $order->shipment?->status->label() ?? 'Menunggu Alokasi' }}
                        </span>
                    </div>

                    <!-- Alamat Asal & Tujuan -->
                    <div class="space-y-3 text-xs">
                        <div class="flex items-start gap-2.5">
                            <div class="w-3.5 h-3.5 rounded-full bg-[#2E8B57] mt-1 shrink-0"></div>
                            <div>
                                <span class="text-[#5E6B60] dark:text-[#BFE3CC] font-semibold block">Lokasi Toko Pengirim</span>
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $order->store->name }}</span>
                                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">{{ $order->shipment?->origin_address ?? $order->store->address }}</p>
                            </div>
                        </div>

                        <div class="border-l-2 border-dashed border-[#BFE3CC] dark:border-[#1F6B3E] ml-1.5 h-4"></div>

                        <div class="flex items-start gap-2.5">
                            <div class="w-3.5 h-3.5 rounded-full bg-[#14432A] dark:bg-[#3FAE6D] mt-1 shrink-0"></div>
                            <div>
                                <span class="text-[#5E6B60] dark:text-[#BFE3CC] font-semibold block">Alamat Tujuan Pengiriman</span>
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $order->customer->name }}</span>
                                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">{{ $order->shipment?->destination_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stepper Pelacakan Pengiriman -->
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 shadow-card">
                    <h3 class="text-sm font-bold text-[#14432A] dark:text-[#F2FAF5] mb-6 pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                        Progres Pengiriman Hari yang Sama (Estimasi)
                    </h3>

                    @php
                        $currentStepIndex = match ($order->shipment?->status->value) {
                            'diminta', 'belum_dibuat' => 1,
                            'dijemput' => 2,
                            'dalam_perjalanan' => 3,
                            'terkirim' => 4,
                            default => 1,
                        };
                    @endphp

                    <x-ui.stepper
                        :steps="[
                            ['title' => 'Diminta', 'desc' => 'Alokasi kurir'],
                            ['title' => 'Dijemput', 'desc' => 'Tiba di toko mitra'],
                            ['title' => 'Perjalanan', 'desc' => 'Menuju lokasi'],
                            ['title' => 'Terkirim', 'desc' => 'Diterima pelanggan'],
                        ]"
                        :currentStep="$currentStepIndex"
                    />

                    <!-- Timeline Checkpoints -->
                    @if ($trackingData && ! empty($trackingData['checkpoints']))
                        <div class="mt-8 pt-6 border-t border-[#E3EBE5] dark:border-[#1F6B3E]">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC] mb-4">Riwayat Checkpoint Pengiriman</h4>
                            <div class="relative border-l-2 border-[#BFE3CC] dark:border-[#1F6B3E] ml-3 space-y-4">
                                @foreach ($trackingData['checkpoints'] as $checkpoint)
                                    <div class="relative pl-5">
                                        <div class="absolute -left-[7px] top-1 w-3 h-3 rounded-full border-2 border-white dark:border-[#0F2A1A] {{ $checkpoint['completed'] ? 'bg-[#2E8B57]' : 'bg-[#E3EBE5] dark:bg-[#1F6B3E]' }}"></div>
                                        <div class="flex items-center justify-between gap-2">
                                            <h5 class="text-xs font-bold {{ $checkpoint['completed'] ? 'text-[#1B2B20] dark:text-[#F2FAF5]' : 'text-[#5E6B60] dark:text-[#BFE3CC]/60' }}">
                                                {{ $checkpoint['title'] }}
                                            </h5>
                                            @if ($checkpoint['timestamp'])
                                                <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC] font-mono">{{ $checkpoint['timestamp'] }}</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] {{ $checkpoint['completed'] ? 'text-[#5E6B60] dark:text-[#BFE3CC]' : 'text-[#88968B]' }} mt-0.5">
                                            {{ $checkpoint['description'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Pesanan & Simulator (lg: 5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Daftar Material Pesanan -->
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 shadow-card">
                    <h3 class="text-sm font-bold text-[#14432A] dark:text-[#F2FAF5] pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E] mb-4 flex items-center justify-between">
                        <span>Daftar Material Pesanan</span>
                        <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">{{ $order->items->count() }} jenis</span>
                    </h3>

                    <div class="space-y-3 mb-4 text-xs">
                        @foreach ($order->items as $item)
                            <div class="flex items-center justify-between py-1 border-b border-dashed border-[#E3EBE5] dark:border-[#1F6B3E] last:border-0">
                                <div>
                                    <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] block">{{ $item->product_name }}</span>
                                    <span class="text-[#5E6B60] dark:text-[#BFE3CC]">{{ $item->quantity }} unit &bull; @ Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                <span class="font-bold text-[#14432A] dark:text-[#3FAE6D]">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] space-y-2 text-xs">
                        <div class="flex justify-between text-[#5E6B60] dark:text-[#BFE3CC]">
                            <span>Biaya Pengiriman Kurir Instan</span>
                            <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] pt-2 border-t border-[#E3EBE5] dark:border-[#1F6B3E]">
                            <span>Total Pembayaran</span>
                            <span class="text-lg font-black text-[#14432A] dark:text-[#3FAE6D]">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Simulator Kurir Instan (Sandbox) -->
                <div class="bg-gradient-to-br from-[#14432A] to-[#0F2A1A] rounded-3xl p-6 text-white shadow-lg space-y-4 border border-[#1F6B3E]">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#3FAE6D] animate-pulse"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#BFE3CC]">Simulator Kurir Instan (Sandbox)</h4>
                    </div>
                    <p class="text-xs text-[#E3EBE5] leading-relaxed">
                        Gunakan tombol di bawah ini untuk mensimulasikan pergerakan status kurir tanpa harus menunggu kurir nyata (sesuai ketentuan <strong>AB-013 &amp; PK-11</strong>).
                    </p>

                    <div class="space-y-2 pt-2">
                        <button 
                            type="button" 
                            wire:click="simulateStep('dijemput')" 
                            class="w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold text-left transition flex items-center justify-between"
                        >
                            <span>1. Simulasikan Kurir Menjemput Paket</span>
                            <span class="text-[10px] bg-[#1F6B3E]/50 px-2 py-0.5 rounded text-[#BFE3CC]">dijemput</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="simulateStep('dalam_perjalanan')" 
                            class="w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold text-left transition flex items-center justify-between"
                        >
                            <span>2. Simulasikan Kurir Dalam Perjalanan</span>
                            <span class="text-[10px] bg-[#1F6B3E]/50 px-2 py-0.5 rounded text-[#BFE3CC]">dalam_perjalanan</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="simulateStep('terkirim')" 
                            class="w-full py-2.5 px-4 rounded-xl bg-[#2E8B57] hover:bg-[#1F6B3E] text-white text-xs font-bold text-left transition flex items-center justify-between shadow-md shadow-[#0F2A1A]/30"
                        >
                            <span>3. Simulasikan Paket Tiba di Lokasi</span>
                            <span class="text-[10px] bg-[#14432A]/60 px-2 py-0.5 rounded text-[#BFE3CC]">terkirim</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
