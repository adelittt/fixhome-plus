<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Alert -->
        @if (session()->has('cart_success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('cart_success') }}</span>
                </div>
                <a href="{{ route('cart.index') }}" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold transition">
                    Buka Keranjang &rarr;
                </a>
            </div>
        @endif

        @if (session()->has('cart_error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('cart_error') }}</span>
            </div>
        @endif

        <!-- Banner Kontekstual Rekomendasi Material Pesanan Jasa (Jika Berasal dari Pesanan Jasa) -->
        @if ($serviceOrder && $serviceOrder->materialRecommendation)
            <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white shadow-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-white/10">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-blue-300 uppercase tracking-wider mb-1">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                            <span>Konteks Pesanan Jasa #{{ $serviceOrder->order_number }}</span>
                        </div>
                        <h2 class="text-xl font-black">Rekomendasi Material oleh Teknisi {{ $serviceOrder->technician->name }}</h2>
                        <p class="text-xs text-slate-300 mt-0.5">Klik salah satu nama material berikut untuk langsung mencari produk yang sesuai di toko mitra.</p>
                    </div>
                    <a href="{{ route('service.order.detail', $serviceOrder->id) }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm transition flex items-center gap-1.5 self-start md:self-auto shrink-0">
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
                            class="px-3.5 py-2 rounded-xl text-xs font-medium transition-all flex items-center gap-2 {{ $search === $item->material_name ? 'bg-blue-500 text-white ring-2 ring-white/50 shadow-md' : 'bg-white/15 hover:bg-white/25 text-white' }}"
                        >
                            <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span>{{ $item->material_name }}</span>
                            <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded-md text-blue-100 font-bold">
                                {{ $item->quantity }} {{ $item->unit }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Hero Banner Umum Marketplace -->
            <div class="mb-8 rounded-3xl bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-800 p-8 sm:p-10 text-white shadow-lg relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="max-w-2xl relative z-10">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white mb-3 backdrop-blur-sm">
                        FixHome Plus Marketplace Material
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                        Material Bangunan Berkualitas dari Mitra Toko Terverifikasi
                    </h1>
                    <p class="text-sm text-blue-100 mt-2 leading-relaxed">
                        Penuhi kebutuhan bahan perbaikan rumah Anda langsung dari toko material terdekat. Transparan, terstandarisasi, dengan pengiriman cepat ke lokasi Anda.
                    </p>
                </div>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm mb-8 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                <!-- Search Input (md: 5 cols) -->
                <div class="md:col-span-5 relative">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Cari Nama Material / Produk</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Contoh: Semen, Pipa AW, Cat Tembok, MCB..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 transition"
                        />
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Kategori Filter (md: 3 cols) -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Material</label>
                    <select 
                        wire:model.live="categoryId" 
                        class="w-full py-2.5 px-3 rounded-2xl border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 transition"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Toko Mitra Filter (md: 2 cols) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mitra Toko</label>
                    <select 
                        wire:model.live="storeId" 
                        class="w-full py-2.5 px-3 rounded-2xl border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 transition"
                    >
                        <option value="">Semua Toko</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Urutan Filter (md: 2 cols) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Urutkan</label>
                    <select 
                        wire:model.live="sort" 
                        class="w-full py-2.5 px-3 rounded-2xl border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 transition"
                    >
                        <option value="terbaru">Terbaru</option>
                        <option value="termurah">Harga Terendah</option>
                        <option value="termahal">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <!-- Filter Status & Reset -->
            @if ($search || $categoryId || $storeId || $sort !== 'terbaru')
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Menampilkan hasil dengan filter aktif.</span>
                    <button 
                        type="button" 
                        wire:click="resetFilter" 
                        class="font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span>Reset Semua Filter</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Daftar Produk Grid -->
        @if ($products->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-3xl text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Produk Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    Tidak ada material bangunan yang cocok dengan kriteria pencarian Anda. Coba gunakan kata kunci umum atau ubah pilihan kategori.
                </p>
                <button 
                    type="button" 
                    wire:click="resetFilter" 
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition"
                >
                    Lihat Semua Produk
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col justify-between group">
                        
                        <!-- Header & Info Produk -->
                        <div class="p-5">
                            <!-- Kategori & Toko -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                    {{ $product->category?->name ?? 'Material' }}
                                </span>
                                
                                <span class="text-[11px] text-slate-500 flex items-center gap-1 font-medium truncate max-w-[130px]" title="{{ $product->store->name }}">
                                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="truncate">{{ $product->store->name }}</span>
                                </span>
                            </div>

                            <a href="{{ route('marketplace.product.detail', $product->id) }}" class="block">
                                <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug mb-1">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                {{ $product->description ?: 'Material bangunan standar spesifikasi berkualitas untuk perbaikan rumah.' }}
                            </p>

                            <!-- Harga & Stok -->
                            <div class="pt-3 border-t border-slate-100 flex items-baseline justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Harga Satuan</span>
                                    <span class="text-lg font-black text-blue-700">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-500">
                                    Stok: <strong class="text-slate-800">{{ $product->stock }}</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Footer Aksi -->
                        <div class="p-5 pt-0">
                            <div class="grid grid-cols-2 gap-2">
                                <a 
                                    href="{{ route('marketplace.product.detail', $product->id) }}" 
                                    class="py-2.5 px-3 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-center text-xs font-bold transition"
                                >
                                    Detail
                                </a>

                                <button 
                                    type="button" 
                                    wire:click="addToCart({{ $product->id }})" 
                                    class="py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-center text-xs font-bold shadow-md shadow-blue-600/20 transition flex items-center justify-center gap-1.5"
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
