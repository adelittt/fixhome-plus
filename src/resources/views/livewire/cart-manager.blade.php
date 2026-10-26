<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Keranjang Belanja Material</h1>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-1">Daftar material bangunan Anda dikelompokkan otomatis per Mitra Toko.</p>
            </div>
            <a href="{{ route('marketplace.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : []) }}" class="px-4 py-2.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 hover:bg-[#F2FAF5] text-[#14432A] dark:text-[#F2FAF5] text-xs font-bold transition flex items-center gap-1.5 self-start sm:self-auto shadow-sm" wire:navigate>
                <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Lanjut Belanja Material</span>
            </a>
        </div>

        <!-- Flash Pesan -->
        @if (session()->has('cart_success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-bold">{{ session('cart_success') }}</span>
            </div>
        @endif

        @if (session()->has('cart_error'))
            <div class="mb-6 p-4 rounded-2xl bg-[#FDF2F0] dark:bg-[#B3402F]/20 border border-[#F5B8B0] text-[#B3402F] text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#B3402F] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('cart_error') }}</span>
            </div>
        @endif

        @if ($storesGroup->isEmpty())
            <x-ui.empty-state
                title="Keranjang Belanja Anda Kosong"
                description="Anda belum memasukkan material perbaikan rumah ke dalam keranjang. Silakan jelajahi katalog toko mitra kami."
            >
                <x-ui.button :href="route('marketplace.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : [])" variant="primary" size="md" wire:navigate>
                    Mulai Belanja Material
                </x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Kolom Kiri: Pengelompokan Produk per Toko (lg: 8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    @foreach ($storesGroup as $group)
                        <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card overflow-hidden">
                            <!-- Header Toko -->
                            <div class="bg-[#F2FAF5] dark:bg-[#14432A]/60 px-6 py-4 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-[#DFF2E6] text-[#14432A] dark:bg-[#1F6B3E]/50 dark:text-[#3FAE6D] flex items-center justify-center font-bold text-xs border border-[#BFE3CC]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm text-[#14432A] dark:text-[#F2FAF5]">{{ $group['store']->name }}</h3>
                                        <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">{{ $group['store']->address }}</p>
                                    </div>
                                </div>

                                <span class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D]">
                                    Subtotal: Rp{{ number_format($group['product_subtotal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Daftar Butir Item Toko Ini -->
                            <div class="p-6 divide-y divide-[#E3EBE5] dark:divide-[#1F6B3E]">
                                @foreach ($group['items'] as $item)
                                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-center text-[#2E8B57] font-bold shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>

                                            <div>
                                                <a href="{{ route('marketplace.product.detail', $item->product_id) }}" class="text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] hover:text-[#1F6B3E] dark:hover:text-[#3FAE6D] transition" wire:navigate>
                                                    {{ $item->product->name }}
                                                </a>
                                                <p class="text-xs font-semibold text-[#14432A] dark:text-[#3FAE6D] mt-0.5">
                                                    Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls & Delete -->
                                        <div class="flex items-center justify-between sm:justify-end gap-4">
                                            <div class="flex items-center border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-xl overflow-hidden bg-white dark:bg-[#14432A]">
                                                <button
                                                    type="button"
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    class="w-8 h-8 flex items-center justify-center text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] transition"
                                                >
                                                    &minus;
                                                </button>
                                                <span class="w-10 text-center text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $item->quantity }}</span>
                                                <button
                                                    type="button"
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    class="w-8 h-8 flex items-center justify-center text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] transition"
                                                >
                                                    +
                                                </button>
                                            </div>

                                            <button
                                                type="button"
                                                wire:click="removeItem({{ $item->id }})"
                                                class="p-2 text-[#5E6B60] hover:text-[#B3402F] transition rounded-lg hover:bg-[#FDF2F0]"
                                                title="Hapus dari keranjang"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Kolom Kanan: Ringkasan Checkout (lg: 4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 shadow-card space-y-4">
                        <h2 class="text-sm font-bold text-[#14432A] dark:text-[#F2FAF5] pb-3 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                            Ringkasan Total Belanja
                        </h2>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between text-[#5E6B60] dark:text-[#BFE3CC]">
                                <span>Total Nilai Produk</span>
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($totalProductAmount, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between text-[#5E6B60] dark:text-[#BFE3CC]">
                                <span>Estimasi Ongkir (Kurir Instan)</span>
                                <span class="text-right">
                                    <strong class="text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($totalShippingFee, 0, ',', '.') }}</strong>
                                    <span class="block text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">Dihitung terpisah per toko mitra</span>
                                </span>
                            </div>

                            <div class="pt-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex justify-between items-center text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
                                <span>Estimasi Total</span>
                                <span class="text-xl font-black text-[#14432A] dark:text-[#3FAE6D]">
                                    Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <x-ui.button
                                :href="route('checkout.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : [])"
                                variant="primary"
                                size="lg"
                                class="w-full"
                                wire:navigate
                            >
                                <span>Lanjut ke Checkout</span>
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
