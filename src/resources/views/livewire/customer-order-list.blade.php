<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pesanan Jasa Saya</h1>
                <p class="text-slate-500 text-sm mt-1">Pantau status konfirmasi teknisi, jadwal kunjungan, dan diskusikan pekerjaan Anda.</p>
            </div>
            <a 
                href="{{ route('service.catalog') }}" 
                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-500/20 transition-all shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Pesan Jasa Baru</span>
            </a>
        </div>

        @if ($orders->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Pesanan Jasa</h3>
                <p class="text-sm text-slate-500 mb-6">Anda belum pernah melakukan pemesanan jasa perbaikan rumah.</p>
                <a href="{{ route('service.catalog') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                    Cari Teknisi Sekarang
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    @php
                        $statusColors = [
                            'menunggu_teknisi' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'diterima' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'berlangsung' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'ditolak' => 'bg-rose-100 text-rose-800 border-rose-200',
                            'dibatalkan' => 'bg-slate-100 text-slate-800 border-slate-200',
                        ];
                        $badgeClass = $statusColors[$order->status->value] ?? 'bg-slate-100 text-slate-800';
                    @endphp
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-extrabold text-blue-600 font-mono">{{ $order->order_number }}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $order->category?->name ?? 'Layanan Jasa' }}</h3>
                                <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1">
                                    <span>Teknisi: <strong class="text-slate-800">{{ $order->technician->name }}</strong></span>
                                    <span>Jadwal: <strong class="text-slate-800">{{ $order->scheduled_at->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-1">{{ $order->job_description }}</p>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-6 shrink-0 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                                <div class="text-left md:text-right">
                                    <div class="text-[11px] text-slate-400">Total Biaya</div>
                                    <div class="text-xl font-extrabold text-slate-900">Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}</div>
                                </div>
                                <a 
                                    href="{{ route('service.order.detail', $order->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold text-sm transition-colors"
                                >
                                    <span>Lihat Detail & Chat</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
