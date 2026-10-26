<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Back -->
        <div class="mb-6">
            <a href="{{ route('service.catalog') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Katalog Teknisi
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
            <!-- Header Ringkasan Teknisi -->
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-white font-bold text-2xl flex items-center justify-center shrink-0 border border-white/20">
                        {{ substr($technician->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-blue-200 font-semibold mb-1">Form Pemesanan Jasa</div>
                        <h1 class="text-2xl font-extrabold tracking-tight">{{ $technician->name }}</h1>
                        <p class="text-blue-100 text-sm mt-0.5">{{ $technician->technicianProfile?->category?->name ?? 'Layanan Perbaikan Rumah' }}</p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 sm:text-right">
                    <div class="text-xs text-blue-200">Tarif Dasar Jasa</div>
                    <div class="text-2xl font-black text-white">Rp{{ number_format($base_service_fee, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Form Isi Pemesanan -->
            <form wire:submit="submitBooking" class="p-6 sm:p-8 space-y-6">
                
                <!-- Deskripsi Pekerjaan -->
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-2">Deskripsi Kerusakan / Kebutuhan Jasa <span class="text-rose-500">*</span></label>
                    <textarea 
                        wire:model="job_description" 
                        rows="4" 
                        placeholder="Jelaskan kendala kerusakan secara detail (misal: pipa pembuangan wastafel dapur bocor di bawah bak cuci)..." 
                        class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm placeholder-slate-400 p-4 transition-all"
                    ></textarea>
                    @error('job_description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jadwal Kunjungan -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Rencana Jadwal Kunjungan <span class="text-rose-500">*</span></label>
                        <input 
                            type="datetime-local" 
                            wire:model="scheduled_at" 
                            class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm p-3.5 transition-all"
                        />
                        @error('scheduled_at') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Alamat Lengkap Lokasi <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="address" 
                            placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..." 
                            class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm p-3.5 transition-all"
                        />
                        @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Penentuan Pin Lokasi pada Peta (OpenStreetMap / Leaflet) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-slate-800">
                            Titik Lokasi Pekerjaan pada Peta
                        </label>
                        <span class="text-xs text-slate-500">Geser penanda pin ke lokasi Anda</span>
                    </div>

                    <div 
                        wire:ignore
                        id="leaflet-map" 
                        class="w-full h-72 rounded-2xl border border-slate-200 shadow-inner z-0"
                    ></div>
                    
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                        <span>Koordinat Pelanggan: <strong class="text-slate-700">{{ number_format($latitude, 5) }}, {{ number_format($longitude, 5) }}</strong></span>
                        <span>Lokasi Teknisi: <strong class="text-slate-700">{{ number_format($technician->technicianProfile?->latitude ?? 0, 5) }}, {{ number_format($technician->technicianProfile?->longitude ?? 0, 5) }}</strong></span>
                    </div>
                </div>

                <!-- Kartu Rincian Biaya Transparan (AB-002) -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-6">
                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Rincian Biaya Layanan Jasa
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center text-slate-700">
                            <span>Tarif Dasar Jasa Teknisi</span>
                            <span class="font-semibold text-slate-900">Rp{{ number_format($base_service_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-slate-700">
                            <div>
                                <span>Estimasi Jarak Rute Jalan</span>
                                <span class="text-xs text-blue-600 ml-1 font-medium">({{ number_format($distance_km, 2) }} km)</span>
                            </div>
                            <span class="text-xs text-slate-500">
                                @if ($distance_km >= 10.0)
                                    <span class="text-amber-600 font-semibold">Jarak &ge; 10 km (+Rp10.000)</span>
                                @else
                                    <span class="text-emerald-600 font-medium">Jarak &lt; 10 km (Bebas Biaya Jarak)</span>
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between items-center text-slate-700">
                            <span>Biaya Tambahan Jarak (AB-002)</span>
                            <span class="font-semibold text-slate-900">Rp{{ number_format($distance_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-3 border-t border-blue-200/60 flex justify-between items-center text-base">
                            <span class="font-bold text-slate-900">Total Biaya Jasa</span>
                            <span class="text-xl font-extrabold text-blue-700">Rp{{ number_format($total_service_fee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-base bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all flex items-center justify-center gap-2"
                    >
                        <span>Kirim Pesanan Jasa</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Inisialisasi Peta Leaflet -->
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = {{ $latitude }};
        const defaultLng = {{ $longitude }};
        const techLat = {{ $technician->technicianProfile?->latitude ?? $latitude }};
        const techLng = {{ $technician->technicianProfile?->longitude ?? $longitude }};

        const mapContainer = document.getElementById('leaflet-map');
        if (!mapContainer) return;

        const map = L.map('leaflet-map').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker Teknisi
        const techIcon = L.divIcon({
            className: 'tech-marker',
            html: '<div style="background-color:#4338ca;color:white;padding:4px 8px;border-radius:8px;font-size:11px;font-weight:bold;box-shadow:0 2px 4px rgba(0,0,0,0.3);">🔧 Teknisi</div>',
            iconSize: [60, 25]
        });
        L.marker([techLat, techLng], { icon: techIcon }).addTo(map).bindPopup('Lokasi Awal Teknisi');

        // Marker Pelanggan (dapat digeser)
        const customerMarker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map).bindPopup('Lokasi Pekerjaan Rumah Anda').openPopup();

        customerMarker.on('dragend', function (event) {
            const position = event.target.getLatLng();
            @this.call('updateCoordinates', position.lat, position.lng);
        });

        map.on('click', function (e) {
            customerMarker.setLatLng(e.latlng);
            @this.call('updateCoordinates', e.latlng.lat, e.latlng.lng);
        });
    });
</script>
@endpush
