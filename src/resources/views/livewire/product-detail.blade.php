<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-[#5E6B60] dark:text-[#BFE3CC] mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-[#14432A] dark:hover:text-white" wire:navigate>Beranda</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-[#14432A] dark:hover:text-white" wire:navigate>Marketplace Material</a>
            <span>/</span>
            <span class="text-[#14432A] dark:text-[#3FAE6D] font-bold truncate max-w-xs">{{ $product->name }}</span>
        </nav>

        <!-- Flash Alert -->
        @if (session()->has('cart_success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold">{{ session('cart_success') }}</span>
                </div>
                <a href="{{ route('cart.index') }}" class="px-3.5 py-1.5 bg-[#14432A] hover:bg-[#1F6B3E] text-white rounded-xl text-xs font-bold transition shadow-xs" wire:navigate>
                    Buka Keranjang &rarr;
                </a>
            </div>
        @endif

        @if (session()->has('cart_error'))
            <div class="mb-6 p-4 rounded-2xl bg-[#FDF2F0] dark:bg-[#B3402F]/20 border border-[#F5B8B0] text-[#B3402F] text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#B3402F] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('cart_error') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card overflow-hidden p-6 sm:p-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">
                <!-- Kolom Kiri: Visual Produk & Badges (md: 5 cols) -->
                <div class="md:col-span-5 flex flex-col items-center">
                    <div class="w-full aspect-square rounded-3xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E] flex flex-col items-center justify-center p-8 relative shadow-inner">
                        <div class="w-24 h-24 rounded-3xl bg-white dark:bg-[#14432A] shadow-md flex items-center justify-center text-[#2E8B57] mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-widest text-center">Material Bangunan</span>
                        <span class="text-sm font-extrabold text-[#1B2B20] dark:text-[#F2FAF5] text-center mt-1">{{ $product->name }}</span>

                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold {{ $product->stock > 0 ? 'bg-[#DFF2E6] text-[#1F6B3E] border border-[#BFE3CC]' : 'bg-[#FDF2F0] text-[#B3402F] border border-[#F5B8B0]' }}">
                            {{ $product->stock > 0 ? 'Stok Tersedia (' . $product->stock . ')' : 'Stok Habis' }}
                        </span>
                    </div>

                    <!-- Info Mitra Toko -->
                    <div class="w-full mt-6 p-4 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#E3EBE5] dark:border-[#1F6B3E]">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC] block mb-1">Disediakan Oleh Mitra Toko</span>
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-extrabold text-sm text-[#1B2B20] dark:text-[#F2FAF5] flex items-center gap-1.5">
                                    <span>{{ $product->store->name }}</span>
                                    <svg class="w-4 h-4 text-[#2E8B57]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </h4>
                                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">{{ $product->store->address ?: 'Lokasi fisik terverifikasi' }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#DFF2E6] text-[#1F6B3E] shrink-0 border border-[#BFE3CC]">
                                Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Rincian Produk & Pembelian (md: 7 cols) -->
                <div class="md:col-span-7 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#DFF2E6] text-[#14432A] dark:bg-[#1F6B3E]/40 dark:text-[#3FAE6D]">
                                {{ $product->category?->name ?? 'Material Perbaikan' }}
                            </span>
                            <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">&bull; ID: PROD-{{ $product->id }}</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B2B20] dark:text-[#F2FAF5] tracking-tight leading-snug mb-3">
                            {{ $product->name }}
                        </h1>

                        <!-- Harga Format Rupiah -->
                        <div class="p-4 bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-2xl border border-[#BFE3CC] dark:border-[#1F6B3E] mb-6 flex items-baseline justify-between">
                            <div>
                                <span class="text-[11px] font-semibold text-[#5E6B60] dark:text-[#BFE3CC] block">Harga Satuan</span>
                                <span class="text-3xl font-black text-[#14432A] dark:text-[#3FAE6D]">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                                Satuan: <strong class="text-[#1B2B20] dark:text-[#F2FAF5]">{{ $product->unit ?? 'Pcs / Butir' }}</strong>
                            </span>
                        </div>

                        <!-- Deskripsi -->
                        <div class="space-y-2 mb-6">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC]">Deskripsi &amp; Spesifikasi Produk</h3>
                            <p class="text-sm text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed">
                                {{ $product->description ?: 'Material bangunan standar spesifikasi berkualitas tinggi yang cocok untuk kebutuhan renovasi dan perbaikan rumah Anda.' }}
                            </p>
                        </div>

                        <!-- Catatan Edukasi Pembelian Opsional -->
                        <div class="p-3.5 rounded-xl bg-white dark:bg-[#14432A]/40 border border-[#BFE3CC] dark:border-[#1F6B3E] text-xs text-[#5E6B60] dark:text-[#BFE3CC] flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#2E8B57] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pembelian material melalui marketplace bersifat <strong>opsional</strong>. Pengiriman disimulasikan menggunakan kurir instan ke alamat lokasi perbaikan rumah Anda.</span>
                        </div>
                    </div>

                    <!-- Pemilihan Jumlah & Tombol Tambah Keranjang -->
                    <div class="pt-6 border-t border-[#E3EBE5] dark:border-[#1F6B3E] space-y-4">
                        <div class="flex items-center gap-4">
                            <label class="text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Jumlah Pembelian:</label>
                            <div class="flex items-center border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-xl overflow-hidden bg-white dark:bg-[#14432A]">
                                <button
                                    type="button"
                                    wire:click="decrementQty"
                                    class="w-10 h-10 flex items-center justify-center text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A] transition focus:outline-none"
                                >
                                    &minus;
                                </button>
                                <span class="w-12 text-center text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $quantity }}</span>
                                <button
                                    type="button"
                                    wire:click="incrementQty"
                                    class="w-10 h-10 flex items-center justify-center text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A] transition focus:outline-none"
                                >
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <x-ui.button
                                wire:click="addToCart"
                                variant="secondary"
                                size="lg"
                                class="w-full"
                            >
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>+ Keranjang</span>
                            </x-ui.button>

                            <x-ui.button
                                wire:click="buyNow"
                                variant="primary"
                                size="lg"
                                class="w-full"
                            >
                                <span>Beli Langsung</span>
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
