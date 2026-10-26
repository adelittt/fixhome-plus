<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Keranjang Belanja Material</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola kebutuhan material bangunan Anda dari mitra toko terverifikasi FixHome Plus.</p>
            </div>
            <a href="{{ route('marketplace.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : []) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 self-start sm:self-auto shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Lanjut Belanja Material</span>
            </a>
        </div>

        <!-- Flash Pesan -->
        @if (session()->has('cart_success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('cart_success') }}</span>
            </div>
        @endif

        @if (session()->has('cart_error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('cart_error') }}</span>
            </div>
        @endif

        @if ($storesGroup->isEmpty())
            <!-- Empty State Keranjang Kosong -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 bg-blue-50 rounded-3xl text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Keranjang Belanja Anda Kosong</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                    Anda belum memasukkan material perbaikan rumah ke dalam keranjang. Silakan jelajahi katalog toko mitra kami.
                </p>
                <a href="{{ route('marketplace.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : []) }}" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Mulai Belanja Material</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Kolom Kiri: Pengelompokan Produk per Toko (lg: 8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    @foreach ($storesGroup as $group)
                        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                            
                            <!-- Header Toko -->
                            <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-1.5">
                                            <span>{{ $group['store']->name }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Mitra Terverifikasi</span>
                                        </h3>
                                        <span class="text-[11px] text-slate-500">{{ $group['store']->address }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Item Toko Ini -->
                            <div class="p-6 divide-y divide-slate-100">
                                @foreach ($group['items'] as $item)
                                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        
                                        <!-- Detail Item -->
                                        <div class="flex-1">
                                            <span class="text-[10px] font-bold uppercase text-blue-600 block mb-0.5">
                                                {{ $item->product->category?->name ?? 'Material' }}
                                            </span>
                                            <h4 class="font-bold text-sm text-slate-900 leading-snug">
                                                {{ $item->product->name }}
                                            </h4>
                                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                                <span>Harga: <strong class="text-slate-800">Rp{{ number_format($item->product->price, 0, ',', '.') }}</strong></span>
                                                <span>•</span>
                                                <span>Stok: {{ $item->product->stock }}</span>
                                            </div>
                                        </div>

                                        <!-- Stepper Jumlah & Subtotal -->
                                        <div class="flex items-center justify-between sm:justify-end gap-6 shrink-0">
                                            
                                            <!-- Stepper -->
                                            <div class="flex items-center border border-slate-200 rounded-xl p-1 bg-slate-50">
                                                <button 
                                                    type="button" 
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                                    class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition"
                                                >
                                                    -
                                                </button>
                                                <span class="w-10 text-center text-xs font-bold text-slate-900">{{ $item->quantity }}</span>
                                                <button 
                                                    type="button" 
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                                    class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition disabled:opacity-40"
                                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                                                >
                                                    +
                                                </button>
                                            </div>

                                            <!-- Subtotal Item -->
                                            <div class="text-right w-28">
                                                <span class="text-[10px] text-slate-400 block">Subtotal</span>
                                                <span class="font-black text-sm text-slate-900">
                                                    Rp{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <!-- Tombol Hapus -->
                                            <button 
                                                type="button" 
                                                wire:click="removeItem({{ $item->id }})" 
                                                class="text-slate-400 hover:text-rose-600 transition p-1"
                                                title="Hapus dari keranjang"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer Subtotal Toko Ini -->
                            <div class="bg-slate-50/50 px-6 py-3.5 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs gap-3">
                                <div class="text-slate-600 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    <span>Ongkir Toko (Kurir Instan): <strong class="text-slate-800">Rp{{ number_format($group['shipping_fee'], 0, ',', '.') }}</strong></span>
                                </div>
                                <div>
                                    <span class="text-slate-500">Subtotal Toko:</span>
                                    <span class="font-extrabold text-blue-700 ml-1">Rp{{ number_format($group['store_total'], 0, ',', '.') }}</span>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- Kolom Kanan: Ringkasan Belanja Multi-Toko (lg: 4 cols) -->
                <div class="lg:col-span-4 sticky top-24 space-y-4">
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                        <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                            <span>Ringkasan Belanja</span>
                            <span class="text-xs font-normal text-slate-500">{{ $itemCount }} item</span>
                        </h2>

                        <div class="py-4 space-y-3 text-xs">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Total Nilai Produk</span>
                                <span class="font-bold text-slate-900">Rp{{ number_format($totalProductAmount, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center text-slate-600">
                                <div>
                                    <span>Total Biaya Pengiriman</span>
                                    <span class="text-[10px] text-slate-400 block">({{ $storesGroup->count() }} Mitra Toko)</span>
                                </div>
                                <span class="font-bold text-slate-900">Rp{{ number_format($totalShippingFee, 0, ',', '.') }}</span>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Total Tagihan</span>
                                    <span class="text-[10px] text-slate-400">Termasuk ongkir seluruh toko</span>
                                </div>
                                <span class="text-xl font-black text-blue-700">
                                    Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Catatan Transparansi Pemisahan Pesanan AB-010 -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 leading-relaxed mb-6">
                            <strong class="text-slate-800 block mb-0.5">Ketentuan Pemesanan (AB-010):</strong>
                            Produk dari {{ $storesGroup->count() }} toko berbeda akan otomatis dipecah menjadi {{ $storesGroup->count() }} nomor pesanan terpisah untuk memudahkan koordinasi pengiriman oleh masing-masing toko.
                        </div>

                        <button 
                            type="button" 
                            wire:click="proceedToCheckout" 
                            class="w-full py-3.5 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-600/25 transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>Lanjut ke Checkout &rarr;</span>
                        </button>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
