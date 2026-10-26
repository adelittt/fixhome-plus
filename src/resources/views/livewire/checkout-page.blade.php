<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('cart.index', $serviceOrderId ? ['pesanan_jasa' => $serviceOrderId] : []) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Keranjang Belanja</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Checkout Pemesanan Material</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi alamat pengiriman dan pilih metode pembayaran untuk memproses pesanan Anda.</p>
        </div>

        @if (session()->has('checkout_error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('checkout_error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Kiri: Form Alamat & Pembayaran (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Form Data Penerima & Alamat -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span>Alamat & Kontak Penerima</span>
                    </h2>

                    @if ($serviceOrder)
                        <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-200 text-xs text-blue-900 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="font-bold block">Terhubung dengan Pesanan Jasa #{{ $serviceOrder->order_number }}</span>
                                Alamat diisi otomatis sesuai lokasi pengerjaan teknisi.
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penerima <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="recipientName" 
                                class="w-full rounded-2xl border-slate-200 text-xs p-3 focus:border-blue-500 focus:ring-blue-500" 
                            />
                            @error('recipientName') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="recipientPhone" 
                                class="w-full rounded-2xl border-slate-200 text-xs p-3 focus:border-blue-500 focus:ring-blue-500" 
                            />
                            @error('recipientPhone') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Pengiriman <span class="text-rose-500">*</span></label>
                        <textarea 
                            wire:model="deliveryAddress" 
                            rows="3" 
                            placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, patokan lokasi..."
                            class="w-full rounded-2xl border-slate-200 text-xs p-3 focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>
                        @error('deliveryAddress') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan untuk Toko / Kurir (Opsional)</label>
                        <input 
                            type="text" 
                            wire:model="notes" 
                            placeholder="Contoh: Titipkan di pos satpam atau hubungi sebelum sampai"
                            class="w-full rounded-2xl border-slate-200 text-xs p-3 focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Metode Pembayaran Simulasi -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span>Metode Pembayaran</span>
                    </h2>

                    <div class="space-y-3">
                        <label class="p-4 rounded-2xl border border-blue-500 bg-blue-50/40 flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" wire:model="paymentMethod" value="simulasi_qris" checked class="text-blue-600 focus:ring-blue-500" />
                                <div>
                                    <span class="font-bold text-xs text-slate-900 block">QRIS Instan (Simulasi Otomatis)</span>
                                    <span class="text-[11px] text-slate-500">Konfirmasi pembayaran instan dalam satu langkah.</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md">Instan</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Rincian Pesanan per Toko & Konfirmasi (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Rincian Pesanan Multi-Toko</span>
                        <span class="text-xs font-semibold text-blue-600">{{ $grouped->count() }} Toko</span>
                    </h2>

                    <!-- Daftar Toko & Item -->
                    <div class="space-y-4 max-h-[360px] overflow-y-auto pr-1">
                        @foreach ($grouped as $group)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-2.5">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-900">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        <span>{{ $group['store']->name }}</span>
                                    </span>
                                    <span class="text-blue-700 font-extrabold">Rp{{ number_format($group['total'], 0, ',', '.') }}</span>
                                </div>

                                <div class="space-y-1.5 pl-5">
                                    @foreach ($group['items'] as $item)
                                        <div class="flex justify-between text-[11px] text-slate-600">
                                            <span class="truncate max-w-[180px]">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                            <span class="font-medium text-slate-800 shrink-0">Rp{{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex justify-between text-[11px] text-slate-400 pt-1 border-t border-slate-200/60">
                                        <span>Ongkos Kirim Kurir:</span>
                                        <span>Rp{{ number_format($group['shipping_fee'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Rincian Biaya Transparan Keseluruhan -->
                    <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Produk:</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($totalProduct, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Ongkir ({{ $grouped->count() }} Toko):</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($totalShipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="font-extrabold text-slate-900 text-sm">Total Pembayaran:</span>
                            <span class="text-2xl font-black text-blue-700">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Catatan Pemecahan Pesanan (AB-010) -->
                    <div class="p-3 rounded-xl bg-slate-100 text-[11px] text-slate-600">
                        Sesuai aturan bisnis <strong>AB-010</strong>, sistem akan otomatis menerbitkan pesanan terpisah untuk tiap toko mitra saat Anda menekan tombol di bawah.
                    </div>

                    <button 
                        type="button" 
                        wire:click="placeOrder(true)" 
                        wire:loading.attr="disabled"
                        class="w-full py-4 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 disabled:opacity-50"
                    >
                        <span wire:loading.remove>Konfirmasi & Bayar Sekarang (Rp{{ number_format($grandTotal, 0, ',', '.') }})</span>
                        <span wire:loading>Memproses Pesanan Multi-Toko...</span>
                    </button>

                </div>

            </div>

        </div>

    </div>
</div>
