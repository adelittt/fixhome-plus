<div class="py-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- 1. Hero Banner Sambutan Pelanggan -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-800 text-white p-8 sm:p-10 shadow-xl shadow-emerald-950/10">
            <!-- Background Accent Shapes -->
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-emerald-700/30 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/4 -bottom-20 w-80 h-80 rounded-full bg-amber-400/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-800/80 border border-emerald-700/60 text-emerald-200 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Dasbor Pelanggan FixHome Plus
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                        Halo, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-emerald-100/80 text-sm sm:text-base max-w-2xl leading-relaxed">
                        Kelola seluruh kebutuhan perbaikan rumah, konsultasi kebutuhan material bersama teknisi, dan pantau pengiriman material bangunan Anda di sini.
                    </p>
                </div>

                <!-- CTA Tombol Cepat di Hero -->
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 shrink-0">
                    <a
                        href="{{ route('service.catalog') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white text-emerald-950 hover:bg-emerald-50 font-bold text-xs sm:text-sm shadow-md transition transform hover:-translate-y-0.5 active:translate-y-0"
                        wire:navigate
                    >
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari Teknisi</span>
                    </a>

                    <a
                        href="{{ route('marketplace.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-800/80 hover:bg-emerald-700 text-white border border-emerald-600/50 font-bold text-xs sm:text-sm shadow-md transition transform hover:-translate-y-0.5 active:translate-y-0"
                        wire:navigate
                    >
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Beli Material</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Ringkasan 4 Kartu KPI Aktivitas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Pesanan Jasa Aktif -->
            <a href="{{ route('customer.orders') }}" class="group bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-emerald-500/50 transition-all flex items-center justify-between" wire:navigate>
                <div>
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Jasa Sedang Aktif</span>
                    <div class="text-3xl font-black text-slate-900 mt-1 group-hover:text-emerald-700 transition">
                        {{ $activeServiceCount }}
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Menunggu &bull; Berlangsung</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 group-hover:bg-emerald-600 text-emerald-700 group-hover:text-white transition flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </a>

            <!-- Card 2: Pesanan Material Diproses -->
            <a href="{{ route('customer.product-orders') }}" class="group bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-amber-500/50 transition-all flex items-center justify-between" wire:navigate>
                <div>
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Pesanan Material</span>
                    <div class="text-3xl font-black text-slate-900 mt-1 group-hover:text-amber-700 transition">
                        {{ $activeProductCount }}
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Diproses &bull; Dalam Kirim</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-600 text-amber-700 group-hover:text-white transition flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </a>

            <!-- Card 3: Keranjang Belanja -->
            <a href="{{ route('cart.index') }}" class="group bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-blue-500/50 transition-all flex items-center justify-between" wire:navigate>
                <div>
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Isi Keranjang</span>
                    <div class="text-3xl font-black text-slate-900 mt-1 group-hover:text-blue-700 transition">
                        {{ $cartCount }} <span class="text-sm font-semibold text-slate-400">item</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Siap untuk checkout</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 group-hover:bg-blue-600 text-blue-700 group-hover:text-white transition flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </a>

            <!-- Card 4: Jasa Selesai -->
            <a href="{{ route('customer.orders') }}" class="group bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md hover:border-purple-500/50 transition-all flex items-center justify-between" wire:navigate>
                <div>
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Perbaikan Selesai</span>
                    <div class="text-3xl font-black text-slate-900 mt-1 group-hover:text-purple-700 transition">
                        {{ $completedServiceCount }}
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Riwayat pekerjaan sukses</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 group-hover:bg-purple-600 text-purple-700 group-hover:text-white transition flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </a>
        </div>

        <!-- 3. Pintasan Aksi Cepat (Quick Action Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-gradient-to-br from-emerald-50 to-white rounded-3xl p-6 border border-emerald-200/80 flex flex-col justify-between shadow-2xs">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center mb-4 shadow-sm shadow-emerald-600/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Perbaikan Darurat Rumah</h3>
                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                        Pipa bocor mendadak, korsleting listrik, atau AC mati? Temukan teknisi terdekat berjarak di bawah 5 km untuk kedatangan segera.
                    </p>
                </div>
                <div class="pt-5">
                    <a href="{{ route('service.catalog') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-800 hover:text-emerald-950 transition" wire:navigate>
                        <span>Pesan Teknisi Cepat</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-white rounded-3xl p-6 border border-amber-200/80 flex flex-col justify-between shadow-2xs">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-amber-600 text-white flex items-center justify-center mb-4 shadow-sm shadow-amber-600/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Belanja Material Bangunan</h3>
                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                        Pipa PVC, saklar MCB, semen, pasir, dan cat tembok dari toko bangunan mitra terdekat di sekitar tempat tinggal Anda.
                    </p>
                </div>
                <div class="pt-5">
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-800 hover:text-amber-950 transition" wire:navigate>
                        <span>Buka Katalog Toko</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-white rounded-3xl p-6 border border-blue-200/80 flex flex-col justify-between shadow-2xs">
                <div>
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-4 shadow-sm shadow-blue-600/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Lacak Status Pengiriman</h3>
                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                        Pantau perjalanan kurir instan yang membawa pesanan material pesanan Anda dengan pelacakan visual interaktif secara langsung.
                    </p>
                </div>
                <div class="pt-5">
                    <a href="{{ route('customer.product-orders') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-800 hover:text-blue-950 transition" wire:navigate>
                        <span>Pantau Paket Saya</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Dua Tabel Pesanan Terkini: Jasa & Produk -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- TABEL KIRI: Pesanan Jasa Terbaru -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span>🛠️ Pesanan Layanan Jasa Terkini</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Permintaan perbaikan rumah bersama teknisi</p>
                        </div>
                        <a href="{{ route('customer.orders') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 transition" wire:navigate>
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if ($recentServiceOrders->isEmpty())
                        <div class="py-10 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">Belum Ada Pesanan Jasa Aktif</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                                Rumah Anda mengalami kendala? Pilih teknisi terpercaya di dekat lokasi Anda sekarang.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('service.catalog') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-800 text-white text-xs font-bold hover:bg-emerald-900 shadow-sm transition" wire:navigate>
                                    Pesan Teknisi Sekarang
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3.5">
                            @foreach ($recentServiceOrders as $order)
                                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-black text-slate-900">{{ $order->order_number }}</span>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold
                                                @if($order->status->value === 'menunggu_teknisi') bg-amber-100 text-amber-800
                                                @elseif($order->status->value === 'diterima') bg-blue-100 text-blue-800
                                                @elseif($order->status->value === 'berlangsung') bg-indigo-100 text-indigo-800
                                                @elseif($order->status->value === 'selesai') bg-emerald-100 text-emerald-800
                                                @else bg-rose-100 text-rose-800 @endif">
                                                {{ $order->status->label() }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-slate-700 font-semibold">
                                            Teknisi: {{ $order->technician->name }} &bull; <span class="text-slate-500">{{ $order->category?->name }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                            <span>📅 {{ $order->scheduled_at->translatedFormat('d M Y, H:i') }}</span>
                                            <span>&bull;</span>
                                            <span class="font-bold text-slate-800">Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <a
                                            href="{{ route('service.order.detail', $order->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 transition shadow-2xs"
                                            wire:navigate
                                        >
                                            <span>Detail &amp; Chat</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Butuh revisi jadwal?</span>
                    <span class="font-medium">Komunikasikan lewat live chat teknisi</span>
                </div>
            </div>

            <!-- TABEL KANAN: Pesanan Material Terkini -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span>🧱 Pesanan Toko Material Bangunan</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pembelian bahan perbaikan rumah</p>
                        </div>
                        <a href="{{ route('customer.product-orders') }}" class="text-xs font-bold text-amber-700 hover:text-amber-900 transition" wire:navigate>
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if ($recentProductOrders->isEmpty())
                        <div class="py-10 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">Belum Ada Pembelian Material</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                                Belanja material bangunan langsung dari toko mitra terpercaya dengan kurir instan cepat.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-700 shadow-sm transition" wire:navigate>
                                    Jelajahi Marketplace
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3.5">
                            @foreach ($recentProductOrders as $pOrder)
                                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-black text-slate-900">{{ $pOrder->order_number }}</span>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold
                                                @if($pOrder->status->value === 'menunggu_pembayaran') bg-amber-100 text-amber-800
                                                @elseif($pOrder->status->value === 'dibayar') bg-blue-100 text-blue-800
                                                @elseif($pOrder->status->value === 'diproses' || $pOrder->status->value === 'siap_dikirim') bg-indigo-100 text-indigo-800
                                                @elseif($pOrder->status->value === 'dikirim') bg-purple-100 text-purple-800
                                                @elseif($pOrder->status->value === 'selesai') bg-emerald-100 text-emerald-800
                                                @else bg-rose-100 text-rose-800 @endif">
                                                {{ $pOrder->status->label() }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-slate-700 font-semibold">
                                            Toko: {{ $pOrder->store?->name }} &bull; <span class="text-slate-500">{{ $pOrder->items->count() }} jenis barang</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                            <span>Total Belanja:</span>
                                            <span class="font-bold text-slate-800">Rp{{ number_format($pOrder->grand_total, 0, ',', '.') }}</span>
                                            <span class="text-slate-400">(Termasuk ongkir)</span>
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <a
                                            href="{{ route('customer.product-order.track', $pOrder->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-amber-50 hover:text-amber-800 hover:border-amber-300 transition shadow-2xs"
                                            wire:navigate
                                        >
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            <span>Lacak Kurir</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Estimasi instan: 1–2 jam</span>
                    <span class="font-medium text-emerald-700 font-semibold">Dikirim dari toko terdekat</span>
                </div>
            </div>
        </div>

        <!-- 5. Kategori Layanan Populer -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Kategori Perbaikan Rumah Populer</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih bidang spesialisasi yang Anda butuhkan</p>
                </div>
                <a href="{{ route('service.catalog') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 transition" wire:navigate>
                    Lihat Seluruh Kategori &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($categories as $cat)
                    <a
                        href="{{ route('service.catalog') }}"
                        class="group p-5 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-300 transition flex flex-col items-center text-center space-y-2 shadow-2xs"
                        wire:navigate
                    >
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200/80 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white transition flex items-center justify-center text-xl shadow-2xs">
                            @if(str_contains(strtolower($cat->slug), 'listrik'))
                                ⚡
                            @elseif(str_contains(strtolower($cat->slug), 'pipa'))
                                🚿
                            @elseif(str_contains(strtolower($cat->slug), 'ac'))
                                ❄️
                            @else
                                🔨
                            @endif
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 group-hover:text-emerald-950 transition">
                            {{ $cat->name }}
                        </h4>
                        <span class="text-[10px] text-slate-400 group-hover:text-emerald-700 font-medium transition">
                            Cari Teknisi Terdekat &rarr;
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 6. Banner Jaminan Pelayanan FixHome Plus -->
        <div class="rounded-3xl bg-emerald-900/5 border border-emerald-900/10 p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-700 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <strong class="text-slate-900 block text-sm">Jaminan Kualitas FixHome Plus</strong>
                    <span>Teknisi bersertifikat, kalkulasi rute geospasial OSRM transparan, dan isolasi komisi terpisah (AB-005).</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                    Garansi Transparan
                </span>
            </div>
        </div>

    </div>
</div>
