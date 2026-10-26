<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-10 mb-8 text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 mb-2">
                    🛠️ Panel Teknisi
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Dasbor Pekerjaan Teknisi</h1>
                <p class="text-slate-300 text-sm mt-1">Kelola pesanan jasa masuk, konfirmasi jadwal, dan komunikasikan kebutuhan perbaikan rumah.</p>
            </div>
            <div class="flex items-center gap-4 sm:text-right">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                    <div class="text-xs text-slate-300">Total Selesai</div>
                    <div class="text-2xl font-black text-emerald-400">{{ $counts['selesai'] }}</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                    <div class="text-xs text-slate-300">Pendapatan Bersih (95%)</div>
                    <div class="text-2xl font-black text-amber-300">Rp{{ number_format($totalEarning, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Flash Alert -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session()->has('info'))
            <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Tab Navigasi Status -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6">
            <button 
                wire:click="setTab('masuk')" 
                class="px-5 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2 {{ $activeTab === 'masuk' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
            >
                <span>Pesanan Masuk</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'masuk' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['masuk'] }}</span>
            </button>

            <button 
                wire:click="setTab('aktif')" 
                class="px-5 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2 {{ $activeTab === 'aktif' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
            >
                <span>Sedang Berjalan</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'aktif' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['aktif'] }}</span>
            </button>

            <button 
                wire:click="setTab('selesai')" 
                class="px-5 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2 {{ $activeTab === 'selesai' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
            >
                <span>Selesai</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'selesai' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['selesai'] }}</span>
            </button>

            <button 
                wire:click="setTab('riwayat')" 
                class="px-5 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2 {{ $activeTab === 'riwayat' ? 'bg-slate-800 text-white shadow-lg shadow-slate-900/25' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
            >
                <span>Riwayat Batal / Tolak</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'riwayat' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['riwayat'] }}</span>
            </button>
        </div>

        <!-- Daftar Pesanan -->
        @if ($orders->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ada Pesanan pada Kategori Ini</h3>
                <p class="text-sm text-slate-500">Daftar pesanan pada tab {{ $activeTab }} saat ini masih kosong.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                            
                            <!-- Kolom Info Pesanan -->
                            <div class="flex-1 space-y-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-sm font-extrabold text-blue-600 font-mono">{{ $order->order_number }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $order->category?->name ?? 'Layanan Jasa' }}
                                    </span>
                                    <span class="text-xs text-slate-400">• {{ $order->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="text-sm">
                                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold block mb-0.5">Pemesan & Lokasi</span>
                                    <div class="font-bold text-slate-900 text-base">{{ $order->customer->name }} ({{ $order->customer->phone ?? '-' }})</div>
                                    <div class="text-slate-600 text-xs mt-0.5 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <span>{{ $order->address }}</span>
                                        <span class="font-semibold text-blue-600">({{ number_format($order->distance_km, 1) }} km)</span>
                                    </div>
                                </div>

                                <div class="text-sm">
                                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold block mb-0.5">Jadwal Permintaan</span>
                                    <div class="font-semibold text-slate-800">{{ $order->scheduled_at->translatedFormat('l, d F Y - H:i') }} WIB</div>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-4 text-xs text-slate-700 border border-slate-100">
                                    <strong class="text-slate-900 block mb-1">Keluhan:</strong>
                                    {{ $order->job_description }}
                                </div>
                            </div>

                            <!-- Kolom Tarif & Tombol Aksi -->
                            <div class="flex flex-col sm:items-end justify-between self-stretch shrink-0 border-t md:border-t-0 md:border-l md:pl-6 border-slate-100 pt-4 md:pt-0">
                                <div class="text-left md:text-right mb-4">
                                    <span class="text-xs text-slate-400 block">Total Imbalan Jasa</span>
                                    <span class="text-2xl font-black text-slate-900">Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}</span>
                                    @if ($order->distance_fee > 0)
                                        <span class="text-[11px] text-amber-600 block mt-0.5 font-medium">+ Biaya Jarak Rp{{ number_format($order->distance_fee, 0, ',', '.') }}</span>
                                    @endif

                                    @if ($order->serviceCommission)
                                        <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 text-left text-xs space-y-1">
                                            <div class="flex items-center justify-between text-gray-600">
                                                <span>Dasar Jasa:</span>
                                                <span class="font-medium">Rp{{ number_format($order->serviceCommission->gross_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-rose-600">
                                                <span>Komisi Platform (5%):</span>
                                                <span class="font-medium">-Rp{{ number_format($order->serviceCommission->platform_commission, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between font-bold text-emerald-800 pt-1 border-t border-emerald-200">
                                                <span>Bersih Diterima:</span>
                                                <span>Rp{{ number_format($order->serviceCommission->technician_earning, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="pt-0.5">
                                                <span class="inline-flex rounded-full bg-emerald-200 px-2 py-0.5 text-[10px] font-bold text-emerald-900">
                                                    Komisi Tercatat (AB-001)
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tombol Aksi per Status -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <a 
                                        href="{{ route('service.order.detail', $order->id) }}" 
                                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition-colors flex items-center gap-1.5"
                                    >
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span>Buka Chat & Detail</span>
                                    </a>

                                    @if ($order->status->value === 'menunggu_teknisi')
                                        <button 
                                            wire:click="acceptOrder({{ $order->id }})" 
                                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-600/20 transition-all"
                                        >
                                            Terima Pesanan
                                        </button>
                                        <button 
                                            wire:click="rejectOrder({{ $order->id }})" 
                                            class="px-4 py-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold text-xs transition-colors"
                                        >
                                            Tolak
                                        </button>
                                    @elseif ($order->status->value === 'diterima')
                                        <button 
                                            wire:click="startOrder({{ $order->id }})" 
                                            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-md shadow-blue-600/20 transition-all"
                                        >
                                            Mulai Pengerjaan
                                        </button>
                                    @elseif ($order->status->value === 'berlangsung')
                                        <button 
                                            wire:click="completeOrder({{ $order->id }})" 
                                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-600/20 transition-all"
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
