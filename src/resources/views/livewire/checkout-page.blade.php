<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('cart.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : []) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white transition mb-2" wire:navigate>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Keranjang Belanja</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Checkout Pemesanan Material</h1>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">Lengkapi data pengiriman dan pilih metode pembayaran (Mode Simulasi).</p>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FEF8E7] text-[#9A6F14] border border-[#F5DE9B] self-start sm:self-auto">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E0A526]"></span>
                Pembayaran Mode Simulasi
            </span>
        </div>

        @if (session()->has('checkout_error'))
            <div class="mb-6 p-4 rounded-2xl bg-[#FDF2F0] dark:bg-[#B3402F]/20 border border-[#F5B8B0] text-[#B3402F] text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#B3402F] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('checkout_error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri: Form Alamat & Pembayaran (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Form Data Penerima & Alamat -->
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card space-y-4">
                    <h2 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span>Alamat &amp; Kontak Penerima</span>
                    </h2>

                    @if ($serviceOrder)
                        <div class="p-3.5 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-xs text-[#14432A] dark:text-[#F2FAF5] flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#2E8B57] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="font-bold block">Terhubung dengan Pesanan Jasa #{{ $serviceOrder->order_number }}</span>
                                Alamat diisi otomatis sesuai lokasi pengerjaan teknisi.
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Nama Lengkap Penerima <span class="text-[#B3402F]">*</span></label>
                            <input
                                type="text"
                                wire:model="recipientName"
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                            @error('recipientName') <span class="text-[10px] text-[#B3402F] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Nomor Telepon / WhatsApp <span class="text-[#B3402F]">*</span></label>
                            <input
                                type="text"
                                wire:model="recipientPhone"
                                class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 min-h-[44px]"
                            />
                            @error('recipientPhone') <span class="text-[10px] text-[#B3402F] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Alamat Lengkap Pengiriman <span class="text-[#B3402F]">*</span></label>
                        <textarea
                            wire:model="deliveryAddress"
                            rows="3"
                            placeholder="Alamat lengkap, nomor rumah, patokan, instruksi kurir..."
                            class="w-full rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20"
                        ></textarea>
                        @error('deliveryAddress') <span class="text-[10px] text-[#B3402F] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Opsi Metode Pembayaran (Simulasi) -->
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card space-y-4">
                    <h2 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between">
                        <span>Metode Pembayaran</span>
                        <span class="text-[11px] font-semibold text-[#9A6F14] bg-[#FEF8E7] px-2 py-0.5 rounded-full border border-[#F5DE9B]">Mode Simulasi</span>
                    </h2>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-[#F2FAF5] dark:bg-[#14432A]/60 cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" value="simulasi_transfer" class="text-[#14432A] focus:ring-[#2E8B57]" />
                            <div class="text-xs">
                                <span class="font-bold text-[#14432A] dark:text-[#F2FAF5] block">Simulasi Transfer Bank / Virtual Account</span>
                                <span class="text-[#5E6B60] dark:text-[#BFE3CC]">Konfirmasi otomatis instan (Lingkungan Pengujian)</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-[#E3EBE5] dark:border-[#1F6B3E] hover:bg-[#F2FAF5] dark:hover:bg-[#14432A]/40 cursor-pointer transition">
                            <input type="radio" wire:model="paymentMethod" value="simulasi_qris" class="text-[#14432A] focus:ring-[#2E8B57]" />
                            <div class="text-xs">
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] block">Simulasi QRIS Terpadu</span>
                                <span class="text-[#5E6B60] dark:text-[#BFE3CC]">Scan QR simulasi untuk pembayaran instan</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Pemecahan Order per Toko (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 shadow-card space-y-4">
                    <h3 class="text-sm font-bold text-[#14432A] dark:text-[#F2FAF5] pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                        Rincian Pesanan per Toko Mitra
                    </h3>

                    <div class="space-y-4">
                        @foreach ($grouped as $storeId => $group)
                            <div class="p-4 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] space-y-2">
                                <div class="flex items-center justify-between text-xs font-bold text-[#14432A] dark:text-[#3FAE6D]">
                                    <span>{{ $group['store']->name }}</span>
                                    <span>Rp{{ number_format($group['product_subtotal'], 0, ',', '.') }}</span>
                                </div>

                                <div class="space-y-1 text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">
                                    @foreach ($group['items'] as $item)
                                        <div class="flex justify-between">
                                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                            <span>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-2 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex justify-between text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                                    <span>Ongkir Kurir Instan (Estimasi)</span>
                                    <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($group['shipping_fee'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="pt-4 border-t border-[#E3EBE5] dark:border-[#1F6B3E] space-y-2 text-xs">
                        <div class="flex justify-between text-[#5E6B60] dark:text-[#BFE3CC]">
                            <span>Subtotal Produk</span>
                            <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($totalProduct, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#5E6B60] dark:text-[#BFE3CC]">
                            <span>Total Ongkos Kirim</span>
                            <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($totalShipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-2 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex justify-between text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
                            <span>Total Pembayaran</span>
                            <span class="text-xl font-black text-[#14432A] dark:text-[#3FAE6D]">
                                Rp{{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <x-ui.button
                            wire:click="placeOrder"
                            variant="primary"
                            size="lg"
                            class="w-full"
                        >
                            <span>Bayar &amp; Buat Pesanan (Simulasi)</span>
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
