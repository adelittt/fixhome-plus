<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-blue-600">Marketplace Material</a>
            <span>/</span>
            <span class="text-slate-800 font-bold truncate max-w-xs">{{ $product->name }}</span>
        </nav>

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

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6 sm:p-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">
                
                <!-- Kolom Kiri: Visual Produk & Badges (md: 5 cols) -->
                <div class="md:col-span-5 flex flex-col items-center">
                    <div class="w-full aspect-square rounded-3xl bg-gradient-to-tr from-slate-100 to-blue-50/60 border border-slate-200/80 flex flex-col items-center justify-center p-8 relative shadow-inner">
                        <div class="w-24 h-24 rounded-3xl bg-white shadow-md flex items-center justify-center text-blue-600 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Material Bangunan</span>
                        <span class="text-sm font-extrabold text-slate-700 text-center mt-1">{{ $product->name }}</span>

                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold {{ $product->stock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $product->stock > 0 ? 'Stok Tersedia (' . $product->stock . ')' : 'Stok Habis' }}
                        </span>
                    </div>

                    <!-- Info Mitra Toko -->
                    <div class="w-full mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Disediakan Oleh Mitra Toko</span>
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-extrabold text-sm text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $product->store->name }}</span>
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $product->store->address ?: 'Lokasi terverifikasi' }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 shrink-0">
                                Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Rincian Produk & Pembelian (md: 7 cols) -->
                <div class="md:col-span-7 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                {{ $product->category?->name ?? 'Material Perbaikan' }}
                            </span>
                            <span class="text-xs text-slate-400">• ID: PROD-{{ $product->id }}</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug mb-3">
                            {{ $product->name }}
                        </h1>

                        <!-- Harga Satuan -->
                        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 mb-6 flex items-baseline justify-between">
                            <div>
                                <span class="text-xs font-bold text-blue-900 block">Harga Satuan Resmi</span>
                                <div class="text-3xl font-black text-blue-700 mt-0.5">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">
                                Tersisa <strong class="text-slate-900">{{ $product->stock }}</strong> unit
                            </span>
                        </div>

                        <!-- Deskripsi & Spesifikasi Produk -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                                Deskripsi & Spesifikasi
                            </h3>
                            <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                                <p>{{ $product->description ?: 'Material bangunan standar spesifikasi proyek perbaikan rumah yang telah melalui proses kurasi kualitas oleh mitra toko.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kontrol Kuantitas & Tombol Aksi Belanja -->
                    <div class="pt-6 border-t border-slate-100 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Tentukan Jumlah Pesanan:</span>
                            
                            <!-- Input Stepper -->
                            <div class="flex items-center border border-slate-200 rounded-2xl p-1 bg-slate-50">
                                <button 
                                    type="button" 
                                    wire:click="decrement" 
                                    class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition disabled:opacity-40"
                                    {{ $quantity <= 1 ? 'disabled' : '' }}
                                >
                                    -
                                </button>
                                <span class="w-12 text-center text-xs font-extrabold text-slate-900">{{ $quantity }}</span>
                                <button 
                                    type="button" 
                                    wire:click="increment" 
                                    class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition disabled:opacity-40"
                                    {{ $quantity >= $product->stock ? 'disabled' : '' }}
                                >
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Subtotal Sementara -->
                        <div class="flex items-center justify-between text-xs font-medium text-slate-600 bg-slate-50 p-3 rounded-xl">
                            <span>Subtotal Produk:</span>
                            <span class="text-base font-extrabold text-slate-900">
                                Rp{{ number_format($product->price * $quantity, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                wire:click="addToCart(false)" 
                                class="w-full py-3.5 px-6 rounded-2xl border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold text-xs transition flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                <span>Tambah ke Keranjang</span>
                            </button>

                            <button 
                                type="button" 
                                wire:click="addToCart(true)" 
                                class="w-full py-3.5 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span>Beli Sekarang</span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
