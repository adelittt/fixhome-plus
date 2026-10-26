<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Banner Teknisi -->
        <div class="bg-gradient-to-r from-[#14432A] via-[#1F6B3E] to-[#2E8B57] rounded-3xl p-8 sm:p-10 mb-8 text-white shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-[#2E8B57]/40 relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold bg-white/15 backdrop-blur-md text-[#BFE3CC] mb-3 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-[#3FAE6D] animate-pulse"></span>
                    <span>Panel Teknisi FixHome Plus</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Dasbor Pekerjaan Teknisi</h1>
                <p class="text-[#E3EBE5] text-xs sm:text-sm mt-1.5 max-w-2xl leading-relaxed">
                    Kelola pesanan jasa masuk, konfirmasi jadwal kunjungan, komunikasikan kebutuhan perbaikan, dan pantau bagi hasil jasa bersih 95%.
                </p>
            </div>

            <!-- Kartu Metrik Cepat -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-4 relative z-10">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 min-w-[130px]">
                    <div class="text-[11px] font-semibold text-[#BFE3CC] uppercase tracking-wider">Total Selesai</div>
                    <div class="text-2xl font-black text-white mt-1">{{ $counts['selesai'] }}</div>
                    <div class="text-[10px] text-[#BFE3CC] mt-0.5">Pekerjaan Berhasil</div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 min-w-[170px]">
                    <div class="text-[11px] font-semibold text-[#BFE3CC] uppercase tracking-wider">Pendapatan Bersih (95%)</div>
                    <div class="text-2xl font-black text-[#F5DE9B] mt-1">Rp{{ number_format($totalEarning, 0, ',', '.') }}</div>
                    <div class="text-[10px] text-[#BFE3CC] mt-0.5">Bagi Hasil Jasa Transparan</div>
                </div>
            </div>

            <!-- Aksen Dekoratif Latar -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- Flash Alert Sukses / Info -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-xs sm:text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif
        @if (session()->has('info'))
            <div class="mb-6 p-4 rounded-2xl bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] text-[#14432A] dark:text-[#BFE3CC] text-xs sm:text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#2E8B57] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Tab Navigasi Status Pekerjaan -->
        <div class="flex items-center gap-2.5 overflow-x-auto pb-4 mb-6">
            <button 
                type="button"
                wire:click="setTab('masuk')" 
                class="px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap min-h-[44px] {{ $activeTab === 'masuk' ? 'bg-[#E0A526] text-white shadow-md shadow-[#E0A526]/30' : 'bg-white dark:bg-[#14432A]/40 text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] dark:hover:bg-[#14432A] border border-[#E3EBE5] dark:border-[#1F6B3E]' }}"
            >
                <span>Pesanan Masuk</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $activeTab === 'masuk' ? 'bg-white/25 text-white' : 'bg-[#F2FAF5] dark:bg-[#14432A] text-[#14432A] dark:text-[#3FAE6D]' }}">{{ $counts['masuk'] }}</span>
            </button>

            <button 
                type="button"
                wire:click="setTab('aktif')" 
                class="px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap min-h-[44px] {{ $activeTab === 'aktif' ? 'bg-[#1F6B3E] text-white shadow-md shadow-[#1F6B3E]/30' : 'bg-white dark:bg-[#14432A]/40 text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] dark:hover:bg-[#14432A] border border-[#E3EBE5] dark:border-[#1F6B3E]' }}"
            >
                <span>Sedang Berjalan</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $activeTab === 'aktif' ? 'bg-white/25 text-white' : 'bg-[#F2FAF5] dark:bg-[#14432A] text-[#14432A] dark:text-[#3FAE6D]' }}">{{ $counts['aktif'] }}</span>
            </button>

            <button 
                type="button"
                wire:click="setTab('selesai')" 
                class="px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap min-h-[44px] {{ $activeTab === 'selesai' ? 'bg-[#2E8B57] text-white shadow-md shadow-[#2E8B57]/30' : 'bg-white dark:bg-[#14432A]/40 text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] dark:hover:bg-[#14432A] border border-[#E3EBE5] dark:border-[#1F6B3E]' }}"
            >
                <span>Selesai</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $activeTab === 'selesai' ? 'bg-white/25 text-white' : 'bg-[#F2FAF5] dark:bg-[#14432A] text-[#14432A] dark:text-[#3FAE6D]' }}">{{ $counts['selesai'] }}</span>
            </button>

            <button 
                type="button"
                wire:click="setTab('riwayat')" 
                class="px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center gap-2 whitespace-nowrap min-h-[44px] {{ $activeTab === 'riwayat' ? 'bg-[#14432A] text-white shadow-md shadow-[#14432A]/30' : 'bg-white dark:bg-[#14432A]/40 text-[#5E6B60] dark:text-[#BFE3CC] hover:bg-[#F2FAF5] dark:hover:bg-[#14432A] border border-[#E3EBE5] dark:border-[#1F6B3E]' }}"
            >
                <span>Riwayat Batal / Tolak</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $activeTab === 'riwayat' ? 'bg-white/25 text-white' : 'bg-[#F2FAF5] dark:bg-[#14432A] text-[#14432A] dark:text-[#3FAE6D]' }}">{{ $counts['riwayat'] }}</span>
            </button>
        </div>

        <!-- Daftar Pesanan Jasa -->
        @if ($orders->isEmpty())
            <x-ui.empty-state
                title="Tidak Ada Pesanan pada Kategori Ini"
                description="Daftar pesanan pada tab {{ $activeTab }} saat ini masih kosong. Pesanan jasa baru akan otomatis muncul di sini."
            />
        @else
            <div class="space-y-5">
                @foreach ($orders as $order)
                    <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] p-6 sm:p-8 shadow-card hover:shadow-card-hover transition-all">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                            
                            <!-- Kolom Kiri: Info Pesanan & Pelanggan -->
                            <div class="flex-1 space-y-4">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="text-xs sm:text-sm font-extrabold text-[#14432A] dark:text-[#3FAE6D] font-mono px-3 py-1 rounded-xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC]">
                                        #{{ $order->order_number }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#F2FAF5] dark:bg-[#14432A]/60 text-[#14432A] dark:text-[#F2FAF5] border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                        {{ $order->category?->name ?? 'Layanan Jasa' }}
                                    </span>
                                    <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                                        &bull; {{ $order->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                    <!-- Pelanggan & Alamat -->
                                    <div class="space-y-1">
                                        <span class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider font-bold block">Pemesan &amp; Lokasi</span>
                                        <div class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] text-sm sm:text-base">{{ $order->customer->name }}</div>
                                        <div class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">{{ $order->customer->phone ?? 'Tidak ada no. telp' }}</div>
                                        <div class="text-xs text-[#1B2B20] dark:text-[#F2FAF5] pt-1 flex items-start gap-1.5">
                                            <svg class="w-4 h-4 text-[#2E8B57] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            <span>{{ $order->address }}</span>
                                        </div>
                                        <div class="pt-0.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DFF2E6] dark:bg-[#1F6B3E]/50 text-[#14432A] dark:text-[#3FAE6D]">
                                                Jarak Rute: {{ number_format($order->distance_km, 1) }} km
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Jadwal Kunjungan -->
                                    <div class="space-y-1">
                                        <span class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider font-bold block">Jadwal Permintaan</span>
                                        <div class="flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#14432A] dark:text-[#F2FAF5]">
                                            <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span>{{ $order->scheduled_at->translatedFormat('l, d F Y - H:i') }} WIB</span>
                                        </div>
                                        <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] pt-1">
                                            Pastikan tiba tepat waktu sesuai jadwal yang telah disepakati bersama pelanggan.
                                        </p>
                                    </div>
                                </div>

                                <!-- Keluhan Pelanggan -->
                                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-2xl p-4 text-xs text-[#1B2B20] dark:text-[#F2FAF5] border border-[#E3EBE5] dark:border-[#1F6B3E]">
                                    <strong class="text-[#14432A] dark:text-[#3FAE6D] block mb-1">Deskripsi Kerusakan / Pekerjaan:</strong>
                                    {{ $order->job_description }}
                                </div>
                            </div>

                            <!-- Kolom Kanan: Finansial & Aksi -->
                            <div class="flex flex-col lg:items-end justify-between self-stretch shrink-0 border-t lg:border-t-0 lg:border-l lg:pl-6 border-[#E3EBE5] dark:border-[#1F6B3E] pt-4 lg:pt-0 min-w-[280px]">
                                <div class="text-left lg:text-right mb-4 w-full">
                                    <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] block">Total Tarif Jasa</span>
                                    <span class="text-2xl font-black text-[#14432A] dark:text-[#F2FAF5]">
                                        Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}
                                    </span>

                                    @if ($order->distance_fee > 0)
                                        <span class="text-[11px] font-bold text-[#E0A526] block mt-0.5">
                                            + Biaya Jarak Rp{{ number_format($order->distance_fee, 0, ',', '.') }} (Rute &ge; 10 km)
                                        </span>
                                    @endif

                                    <!-- Rincian Komisi Transparan (AB-001 & AB-005) -->
                                    @if ($order->serviceCommission)
                                        <div class="mt-3 bg-[#DFF2E6] dark:bg-[#1F6B3E]/30 border border-[#BFE3CC] dark:border-[#1F6B3E] rounded-2xl p-3 text-left text-xs space-y-1.5 shadow-sm">
                                            <div class="flex items-center justify-between text-[#5E6B60] dark:text-[#BFE3CC]">
                                                <span>Dasar Jasa:</span>
                                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($order->serviceCommission->gross_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-[#B3402F]">
                                                <span>Komisi Platform (5%):</span>
                                                <span class="font-bold">-Rp{{ number_format($order->serviceCommission->platform_commission, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between font-black text-[#14432A] dark:text-[#3FAE6D] pt-1.5 border-t border-[#BFE3CC] dark:border-[#1F6B3E] text-sm">
                                                <span>Bersih Diterima (95%):</span>
                                                <span>Rp{{ number_format($order->serviceCommission->technician_earning, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="pt-1">
                                                <span class="inline-flex rounded-full bg-[#3FAE6D]/20 px-2 py-0.5 text-[10px] font-bold text-[#14432A] dark:text-[#BFE3CC]">
                                                    Komisi Tercatat (AB-001)
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tombol Aksi per Siklus Pekerjaan -->
                                <div class="flex flex-wrap items-center gap-2.5 w-full lg:justify-end">
                                    <a 
                                        href="{{ route('service.order.detail', $order->id) }}" 
                                        class="px-4 py-2.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-[#14432A] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] font-bold text-xs transition flex items-center justify-center gap-1.5 min-h-[44px] shadow-sm flex-1 sm:flex-initial"
                                    >
                                        <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span>Buka Chat &amp; Detail</span>
                                    </a>

                                    @if ($order->status->value === 'menunggu_teknisi')
                                        <button 
                                            type="button"
                                            wire:click="acceptOrder({{ $order->id }})" 
                                            class="px-5 py-2.5 rounded-xl bg-[#14432A] hover:bg-[#1F6B3E] text-white font-bold text-xs shadow-md shadow-[#14432A]/20 transition flex items-center justify-center min-h-[44px] flex-1 sm:flex-initial"
                                        >
                                            Terima Pesanan
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="rejectOrder({{ $order->id }})" 
                                            class="px-4 py-2.5 rounded-xl bg-[#FDF2F0] text-[#B3402F] hover:bg-[#F5B8B0]/30 font-bold text-xs transition flex items-center justify-center min-h-[44px] border border-[#F5B8B0]"
                                        >
                                            Tolak
                                        </button>
                                    @elseif ($order->status->value === 'diterima')
                                        <button 
                                            type="button"
                                            wire:click="startOrder({{ $order->id }})" 
                                            class="px-5 py-2.5 rounded-xl bg-[#1F6B3E] hover:bg-[#2E8B57] text-white font-bold text-xs shadow-md shadow-[#1F6B3E]/20 transition flex items-center justify-center min-h-[44px] flex-1 sm:flex-initial"
                                        >
                                            Mulai Pengerjaan
                                        </button>
                                    @elseif ($order->status->value === 'berlangsung')
                                        <button 
                                            type="button"
                                            wire:click="completeOrder({{ $order->id }})" 
                                            class="px-5 py-2.5 rounded-xl bg-[#2E8B57] hover:bg-[#14432A] text-white font-bold text-xs shadow-md shadow-[#2E8B57]/20 transition flex items-center justify-center min-h-[44px] flex-1 sm:flex-initial"
                                        >
                                            Selesaikan Pekerjaan
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
