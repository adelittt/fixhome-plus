<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Status Pesanan -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. Pesanan</span>
                    <span class="text-base font-extrabold text-slate-900 font-mono">{{ $order->order_number }}</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ $order->category?->name ?? 'Layanan Perbaikan Rumah' }}
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>

            <!-- Status Badge -->
            <div class="flex flex-col sm:items-end">
                <div class="text-xs text-slate-400 mb-1">Status Pesanan Jasa</div>
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
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold border {{ $badgeClass }}">
                    {{ $order->status->label() }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Kolom Kiri: Rincian Pesanan (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Info Pihak Terlibat -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Informasi Layanan & Teknisi
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
                        <div class="bg-slate-50 rounded-2xl p-4">
                            <span class="text-xs text-slate-400 block mb-1">Teknisi Ditugaskan</span>
                            <span class="font-bold text-slate-900 block text-base">{{ $order->technician->name }}</span>
                            <span class="text-xs text-slate-500">{{ $order->technician->phone ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4">
                            <span class="text-xs text-slate-400 block mb-1">Pelanggan Pemesan</span>
                            <span class="font-bold text-slate-900 block text-base">{{ $order->customer->name }}</span>
                            <span class="text-xs text-slate-500">{{ $order->customer->phone ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Jadwal Kunjungan</span>
                            <div class="flex items-center gap-2 text-slate-800 font-medium">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ $order->scheduled_at->translatedFormat('l, d F Y - H:i') }} WIB</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Alamat Pekerjaan</span>
                            <div class="flex items-start gap-2 text-slate-800">
                                <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span>{{ $order->address }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Deskripsi Kerusakan</span>
                            <div class="bg-slate-50 rounded-2xl p-4 text-slate-700 leading-relaxed border border-slate-100">
                                {{ $order->job_description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Rekomendasi Material (Fase 3: PRD 4.2, AB-006, AB-007) -->
                @if (auth()->id() === $order->technician_id && in_array($order->status->value, ['diterima', 'berlangsung']))
                    <livewire:material-recommendation-manager :order="$order" />
                @endif

                <livewire:material-recommendation-viewer :order="$order" />

                <!-- Kartu Rincian Finansial Transparan -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Rincian Biaya Transparan</span>
                        <span class="text-xs font-normal text-slate-400">Jarak Rute: {{ number_format($order->distance_km, 2) }} km</span>
                    </h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center text-slate-700">
                            <span>Tarif Dasar Jasa</span>
                            <span class="font-semibold text-slate-900">Rp{{ number_format($order->base_service_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-slate-700">
                            <div>
                                <span>Biaya Tambahan Jarak</span>
                                <span class="text-xs text-slate-400 ml-1">(&ge; 10 km = Rp10.000)</span>
                            </div>
                            <span class="font-semibold text-slate-900">Rp{{ number_format($order->distance_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-base">
                            <span class="font-bold text-slate-900">Total Biaya Jasa</span>
                            <span class="text-xl font-extrabold text-blue-700">Rp{{ number_format($order->total_service_fee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Live Chat Interaktif (5 Cols) -->
            <div class="lg:col-span-5">
                <livewire:service-order-chat :order="$order" />
            </div>
        </div>
    </div>
</div>
