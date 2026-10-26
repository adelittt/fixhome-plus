<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Navigasi Tab Pesanan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Riwayat Pesanan Saya</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau status pengerjaan jasa perbaikan dan pesanan material bangunan Anda.</p>
            </div>

            <!-- Tab Switcher (Jasa vs Material) -->
            <div class="inline-flex p-1 rounded-2xl bg-slate-200/80 border border-slate-300/60 self-start sm:self-auto text-xs font-bold">
                <a href="{{ route('customer.orders') }}" class="px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 transition">
                    Pesanan Jasa
                </a>
                <a href="{{ route('customer.product-orders') }}" class="px-4 py-2 rounded-xl bg-white text-blue-700 shadow-sm transition">
                    Pesanan Material
                </a>
            </div>
        </div>

        <!-- Flash Pesan -->
        @if (session()->has('order_success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('order_success') }}</span>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 bg-blue-50 rounded-3xl text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Pesanan Material</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                    Anda belum pernah melakukan pemesanan material bangunan di marketplace FixHome Plus.
                </p>
                <a href="{{ route('marketplace.index') }}" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Jelajahi Marketplace</span>
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                        
                        <!-- Header Pesanan Toko -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3 mb-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-sm font-black font-mono text-blue-700">{{ $order->order_number }}</span>
                                    <span class="text-xs text-slate-400">• {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                                    
                                    @if ($order->serviceOrder)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                            Terkait Pesanan Jasa #{{ $order->serviceOrder->order_number }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs font-extrabold text-slate-800 mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <span>Toko: {{ $order->store->name }}</span>
                                </div>
                            </div>

                            <!-- Badge Status -->
                            <div>
                                @php
                                    $statusClasses = [
                                        'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'dibayar' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'diproses' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'siap_dikirim' => 'bg-sky-100 text-sky-800 border-sky-200',
                                        'dikirim' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'ditolak_toko' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        'dibatalkan' => 'bg-slate-100 text-slate-800 border-slate-200',
                                    ];
                                    $cls = $statusClasses[$order->status->value] ?? 'bg-slate-100 text-slate-800';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $cls }}">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                        </div>

                        <!-- Butir Produk Dipesan -->
                        <div class="space-y-3 mb-6">
                            @foreach ($order->items as $item)
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="font-bold text-slate-900 text-sm block">{{ $item->product_name }}</span>
                                        <span class="text-slate-500">Jumlah: {{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                    <span class="font-extrabold text-slate-900 text-sm">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Info Pengiriman & Finansial -->
                        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                            <div class="space-y-1">
                                @if ($order->shipment)
                                    <div class="text-slate-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        <span>Kurir Instan: <strong>{{ $order->shipment->tracking_number }}</strong> ({{ $order->shipment->status->label() }})</span>
                                    </div>
                                @endif
                                <div class="text-slate-500">
                                    Subtotal Produk: <strong>Rp{{ number_format($order->total_product_amount, 0, ',', '.') }}</strong> | Ongkir: <strong>Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 self-end sm:self-auto">
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 block">Total Tagihan Toko</span>
                                    <span class="text-lg font-black text-blue-700">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if ($order->shipment)
                                        <a 
                                            href="{{ route('customer.product-order.track', $order->id) }}" 
                                            class="px-3.5 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition inline-flex items-center gap-1.5"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            <span>Lacak Kurir</span>
                                        </a>
                                    @endif

                                    @if ($order->status->value === 'dikirim')
                                        <button 
                                            type="button" 
                                            wire:click="confirmDelivered({{ $order->id }})" 
                                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition"
                                        >
                                            Konfirmasi Diterima
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
