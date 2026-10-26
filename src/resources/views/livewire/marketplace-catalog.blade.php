<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]" x-data="{ quickProduct: null }">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
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

        <!-- Banner Kontekstual Rekomendasi Material Pesanan Jasa (Jika Berasal dari Pesanan Jasa) -->
        @if ($serviceOrder && $serviceOrder->materialRecommendation)
            <div class="mb-8 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-[#14432A] via-[#1F6B3E] to-[#0F2A1A] text-white shadow-elevated border border-[#BFE3CC]/30">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-white/10">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-[#BFE3CC] uppercase tracking-wider mb-1">
                            <span class="w-2 h-2 rounded-full bg-[#3FAE6D] animate-pulse"></span>
                            <span>Konteks Pesanan Jasa #{{ $serviceOrder->order_number }}</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">Rekomendasi Material oleh Teknisi {{ $serviceOrder->technician->name }}</h2>
                        <p class="text-xs text-[#DFF2E6] mt-0.5">Klik salah satu nama material berikut untuk langsung mencari produk yang sesuai di toko mitra.</p>
                    </div>
                    <a href="{{ route('service.order.detail', $serviceOrder->id) }}" class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold backdrop-blur-sm transition flex items-center gap-1.5 self-start md:self-auto shrink-0 border border-white/20" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Kembali ke Detail Pesanan</span>
                    </a>
                </div>

                <!-- Chips Material Rekomendasi -->
                <div class="pt-4 flex flex-wrap gap-2.5">
                    @foreach ($serviceOrder->materialRecommendation->items as $item)
                        <button
                            type="button"
                            wire:click="$set('search', '{{ addslashes($item->material_name) }}')"
                            class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 {{ $search === $item->material_name ? 'bg-[#3FAE6D] text-white ring-2 ring-white/50 shadow-md' : 'bg-white/15 hover:bg-white/25 text-white' }}"
                        >
                            <svg class="w-3.5 h-3.5 text-[#BFE3CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span>{{ $item->material_name }}</span>
                            <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded-md text-white font-bold">
                                {{ $item->quantity }} {{ $item->unit }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Hero Banner Umum Marketplace -->
            <div class="mb-8 rounded-3xl bg-gradient-to-r from-[#14432A] via-[#1F6B3E] to-[#14432A] p-8 sm:p-10 text-white shadow-elevated relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="max-w-2xl relative z-10">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-[#DFF2E6] mb-3 backdrop-blur-sm">
                        FixHome Plus Marketplace Material
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">
                        Material Bangunan Berkualitas dari Toko Mitra Terverifikasi
                    </h1>
                    <p class="text-xs sm:text-sm text-[#DFF2E6] mt-2 leading-relaxed">
                        Penuhi kebutuhan bahan perbaikan rumah Anda langsung dari toko material terdekat. Transparan, terstandarisasi, dengan estimasi pengiriman kurir instan ke lokasi Anda.
                    </p>
                </div>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 shadow-card mb-8 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Search Input (md: 5 cols) -->
                <div class="md:col-span-5 relative">
                    <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Cari Nama Material / Produk</label>
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Contoh: Semen, Pipa PVC, Cat Tembok, MCB..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-[#1B2B20] dark:text-[#F2FAF5] transition min-h-[44px]"
                        />
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#5E6B60] dark:text-[#BFE3CC]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Kategori Filter (md: 3 cols) -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Kategori Material</label>
                    <select
                        wire:model.live="categoryId"
                        class="w-full py-2.5 px-3 rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-[#1B2B20] dark:text-[#F2FAF5] transition min-h-[44px]"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Toko Mitra Filter (md: 2 cols) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Mitra Toko</label>
                    <select
                        wire:model.live="storeId"
                        class="w-full py-2.5 px-3 rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-[#1B2B20] dark:text-[#F2FAF5] transition min-h-[44px]"
                    >
                        <option value="">Semua Toko</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Urutan Filter (md: 2 cols) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1">Urutkan</label>
                    <select
                        wire:model.live="sort"
                        class="w-full py-2.5 px-3 rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-[#1B2B20] dark:text-[#F2FAF5] transition min-h-[44px]"
                    >
                        <option value="terbaru">Terbaru</option>
                        <option value="termurah">Harga Terendah</option>
                        <option value="termahal">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <!-- Filter Status & Reset -->
            @if ($search || $categoryId || $storeId || $sort !== 'terbaru')
                <div class="pt-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between text-xs">
                    <span class="text-[#5E6B60] dark:text-[#BFE3CC]">Menampilkan produk dengan filter aktif.</span>
                    <button
                        type="button"
                        wire:click="resetFilter"
                        class="font-semibold text-[#B3402F] hover:underline flex items-center gap-1 transition"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span>Reset Semua Filter</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Daftar Produk Grid -->
        @if ($products->isEmpty())
            <x-ui.empty-state
                title="Produk Tidak Ditemukan"
                description="Tidak ada material bangunan yang cocok dengan kriteria pencarian Anda. Coba gunakan kata kunci umum atau ubah pilihan kategori."
            >
                <x-ui.button wire:click="resetFilter" variant="secondary" size="sm">
                    Lihat Semua Produk
                </x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card card-hover-effect overflow-hidden flex flex-col justify-between group">
                        <!-- Header & Info Produk -->
                        <div class="p-5">
                            <!-- Kategori & Toko -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DFF2E6] text-[#14432A] dark:bg-[#1F6B3E]/40 dark:text-[#3FAE6D]">
                                    {{ $product->category?->name ?? 'Material' }}
                                </span>

                                <span class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] flex items-center gap-1 font-medium truncate max-w-[130px]" title="{{ $product->store->name }}">
                                    <svg class="w-3.5 h-3.5 text-[#2E8B57] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="truncate">{{ $product->store->name }}</span>
                                </span>
                            </div>

                            <a href="{{ route('marketplace.product.detail', $product->id) }}" class="block" wire:navigate>
                                <h3 class="text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] group-hover:text-[#1F6B3E] dark:group-hover:text-[#3FAE6D] transition line-clamp-2 leading-snug mb-1">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] line-clamp-2 mb-4 leading-relaxed">
                                {{ $product->description ?: 'Material bangunan standar spesifikasi berkualitas untuk perbaikan rumah.' }}
                            </p>

                            <!-- Harga & Stok -->
                            <div class="pt-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex items-baseline justify-between">
                                <div>
                                    <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC] block">Harga Satuan</span>
                                    <span class="text-lg font-black text-[#14432A] dark:text-[#3FAE6D]">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold text-[#5E6B60] dark:text-[#BFE3CC]">
                                    Stok: <strong class="text-[#1B2B20] dark:text-[#F2FAF5]">{{ $product->stock }}</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Footer Aksi -->
                        <div class="p-5 pt-0">
                            <div class="grid grid-cols-2 gap-2">
                                <a
                                    href="{{ route('marketplace.product.detail', $product->id) }}"
                                    class="py-2.5 px-3 rounded-xl border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] dark:border-[#1F6B3E] hover:bg-[#F2FAF5] dark:hover:bg-[#14432A] text-center text-xs font-bold transition min-h-[44px] flex items-center justify-center"
                                    wire:navigate
                                >
                                    Detail
                                </a>

                                <button
                                    type="button"
                                    wire:click="addToCart({{ $product->id }})"
                                    class="py-2.5 px-3 rounded-xl bg-[#14432A] hover:bg-[#1F6B3E] text-white text-center text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5 btn-press-effect min-h-[44px]"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    <span>+ Keranjang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
