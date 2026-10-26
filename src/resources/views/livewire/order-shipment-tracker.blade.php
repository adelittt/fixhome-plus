<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Navigasi -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('customer.product-orders') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Pesanan Material</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pelacakan Kurir Instan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau pergerakan pengiriman material bangunan Anda secara langsung.</p>
            </div>

            @if ($order->shipment)
                <div class="bg-white border border-slate-200/80 px-4 py-2.5 rounded-2xl shadow-sm text-left sm:text-right">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">No. Resi Pengiriman</span>
                    <span class="text-sm font-extrabold font-mono text-blue-700">{{ $order->shipment->tracking_number }}</span>
                </div>
            @endif
        </div>

        <!-- Flash Pesan Simulasi -->
        @if (session()->has('simulation_success'))
            <div class="mb-6 p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('simulation_success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Kiri: Info Driver & Timeline Stepper (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Kartu Driver Kurir Instan -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-900">{{ $trackingData['driver_name'] ?? 'Kurir Instan FixHome Express' }}</h3>
                                <p class="text-xs text-slate-500 font-mono">{{ $trackingData['driver_plate'] ?? 'B 4123 SXZ' }} • {{ $trackingData['driver_phone'] ?? '-' }}</p>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->shipment?->status->value === 'terkirim' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800 animate-pulse' }}">
                            {{ $order->shipment?->status->label() ?? 'Menunggu Alokasi' }}
                        </span>
                    </div>

                    <!-- Alamat Asal & Tujuan -->
                    <div class="space-y-3 text-xs">
                        <div class="flex items-start gap-2.5">
                            <div class="w-3.5 h-3.5 rounded-full bg-emerald-500 mt-1 shrink-0"></div>
                            <div>
                                <span class="text-slate-400 font-semibold block">Lokasi Toko Pengirim</span>
                                <span class="font-bold text-slate-800">{{ $order->store->name }}</span>
                                <p class="text-slate-500 text-[11px] mt-0.5">{{ $order->shipment?->origin_address }}</p>
                            </div>
                        </div>

                        <div class="border-l-2 border-dashed border-slate-200 ml-1.5 pl-4 py-1"></div>

                        <div class="flex items-start gap-2.5">
                            <div class="w-3.5 h-3.5 rounded-full bg-rose-500 mt-1 shrink-0"></div>
                            <div>
                                <span class="text-slate-400 font-semibold block">Lokasi Penerima Pelanggan</span>
                                <span class="font-bold text-slate-800">{{ $order->customer->name }}</span>
                                <p class="text-slate-500 text-[11px] mt-0.5">{{ $order->shipment?->destination_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Checkpoints Stepper -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 mb-6 flex items-center justify-between">
                        <span>Riwayat Perjalanan Paket</span>
                        <span class="text-xs font-normal text-slate-400">{{ $trackingData['estimated_time'] ?? '1-2 Jam' }}</span>
                    </h3>

                    @if ($trackingData && ! empty($trackingData['checkpoints']))
                        <div class="relative border-l-2 border-slate-200 ml-4 space-y-6">
                            @foreach ($trackingData['checkpoints'] as $checkpoint)
                                <div class="relative pl-6">
                                    <!-- Bullet Marker -->
                                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 {{ $checkpoint['completed'] ? 'bg-blue-600 border-white ring-2 ring-blue-500' : 'bg-slate-200 border-white' }}"></div>

                                    <div>
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-xs font-bold {{ $checkpoint['completed'] ? 'text-slate-900' : 'text-slate-400' }}">
                                                {{ $checkpoint['title'] }}
                                            </h4>
                                            @if ($checkpoint['timestamp'])
                                                <span class="text-[10px] text-slate-400 font-mono">{{ $checkpoint['timestamp'] }}</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] {{ $checkpoint['completed'] ? 'text-slate-600' : 'text-slate-400' }} mt-0.5">
                                            {{ $checkpoint['description'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-500">Data checkpoint belum tersedia.</p>
                    @endif
                </div>

            </div>

            <!-- Kolom Kanan: Detail Paket & Simulator Status Kurir (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Butir Material dalam Paket -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center justify-between">
                        <span>Isi Paket Material</span>
                        <span class="text-xs text-slate-500">{{ $order->items->count() }} jenis</span>
                    </h3>

                    <div class="space-y-3">
                        @foreach ($order->items as $item)
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-900 block">{{ $item->product_name }}</span>
                                    <span class="text-slate-500">{{ $item->quantity }} unit</span>
                                </div>
                                <span class="font-bold text-slate-800">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-slate-100 mt-4 flex justify-between items-baseline text-xs">
                        <span class="text-slate-500">Ongkos Kirim Instan:</span>
                        <span class="font-bold text-slate-900">Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Panel Simulasi Kurir (Sandbox Akademik PK-11) -->
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-lg space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-300">Simulator Kurir Instan (Sandbox)</h4>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Gunakan tombol di bawah ini untuk mensimulasikan pergerakan status kurir tanpa harus menunggu kurir nyata (sesuai ketentuan <strong>AB-013 & PK-11</strong>).
                    </p>

                    <div class="space-y-2 pt-2">
                        <button 
                            type="button" 
                            wire:click="simulateStep('dijemput')" 
                            class="w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold text-left transition flex items-center justify-between"
                        >
                            <span>1. Simulasikan Kurir Menjemput Paket</span>
                            <span class="text-[10px] bg-indigo-500/30 px-2 py-0.5 rounded text-indigo-200">dijemput</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="simulateStep('dalam_perjalanan')" 
                            class="w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold text-left transition flex items-center justify-between"
                        >
                            <span>2. Simulasikan Kurir Dalam Perjalanan</span>
                            <span class="text-[10px] bg-blue-500/30 px-2 py-0.5 rounded text-blue-200">dalam_perjalanan</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="simulateStep('terkirim')" 
                            class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold text-left transition flex items-center justify-between shadow-md shadow-emerald-900/30"
                        >
                            <span>3. Simulasikan Paket Tiba di Lokasi</span>
                            <span class="text-[10px] bg-emerald-700/60 px-2 py-0.5 rounded text-emerald-100">terkirim</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
