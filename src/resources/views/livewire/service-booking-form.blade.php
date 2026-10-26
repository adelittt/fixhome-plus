<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('service.catalog') }}" class="inline-flex items-center text-xs font-semibold text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white transition-colors" wire:navigate>
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Katalog Teknisi
            </a>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#FEF8E7] text-[#9A6F14] border border-[#F5DE9B]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E0A526]"></span>
                Mode Simulasi
            </span>
        </div>

        <!-- 3-Step Stepper Progress Bar -->
        <div class="mb-8 bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl shadow-card p-4 sm:p-6">
            <x-ui.stepper
                :steps="[
                    ['title' => 'Langkah 1', 'desc' => 'Pilih Tanggal & Jam'],
                    ['title' => 'Langkah 2', 'desc' => 'Tentukan Alamat & Peta'],
                    ['title' => 'Langkah 3', 'desc' => 'Rincian Biaya & Konfirmasi'],
                ]"
                :currentStep="2"
            />
        </div>

        <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-card overflow-hidden mb-8">
            <!-- Header Ringkasan Teknisi -->
            <div class="bg-gradient-to-r from-[#14432A] via-[#1F6B3E] to-[#14432A] p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-md text-white font-extrabold text-2xl flex items-center justify-center shrink-0 border border-white/20 shadow-sm">
                        {{ substr($technician->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-wider text-[#BFE3CC] font-bold mb-1 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#3FAE6D]"></span>
                            Form Pemesanan Jasa Perbaikan
                        </div>
                        <h1 class="text-2xl font-black tracking-tight text-white">{{ $technician->name }}</h1>
                        <p class="text-[#DFF2E6] text-xs sm:text-sm mt-0.5">{{ $technician->technicianProfile?->category?->name ?? 'Layanan Perbaikan Rumah' }}</p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 sm:text-right">
                    <div class="text-xs text-[#BFE3CC]">Tarif Dasar Jasa Mulai Dari</div>
                    <div class="text-2xl font-black text-white">Rp{{ number_format($base_service_fee, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Form Isi Pemesanan -->
            <form wire:submit="submitBooking" class="p-6 sm:p-8 space-y-6">
                <!-- Deskripsi Pekerjaan -->
                <div>
                    <label class="block text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-2">
                        Deskripsi Kerusakan / Kebutuhan Jasa <span class="text-[#B3402F]">*</span>
                    </label>
                    <textarea
                        wire:model="job_description"
                        rows="4"
                        placeholder="Jelaskan kendala kerusakan secara detail (misal: pipa pembuangan wastafel dapur bocor di bawah bak cuci)..."
                        class="w-full rounded-2xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/40 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-sm placeholder-[#5E6B60]/60 p-4 transition duration-150"
                    ></textarea>
                    @error('job_description') <span class="text-xs text-[#B3402F] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jadwal Kunjungan -->
                    <div>
                        <label class="block text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-2">
                            Rencana Jadwal Kunjungan <span class="text-[#B3402F]">*</span>
                        </label>
                        <input
                            type="datetime-local"
                            wire:model="scheduled_at"
                            class="w-full rounded-2xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/40 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-sm p-3.5 transition duration-150"
                        />
                        @error('scheduled_at') <span class="text-xs text-[#B3402F] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-2">
                            Alamat Lengkap Lokasi Rumah <span class="text-[#B3402F]">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="address"
                            placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."
                            class="w-full rounded-2xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/40 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 text-sm p-3.5 transition duration-150"
                        />
                        @error('address') <span class="text-xs text-[#B3402F] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Penentuan Pin Lokasi pada Peta (OpenStreetMap / Leaflet) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
                            Titik Lokasi Pekerjaan pada Peta
                        </label>
                        <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Geser penanda pin ke lokasi Anda</span>
                    </div>

                    <div
                        wire:ignore
                        id="leaflet-map"
                        class="w-full h-72 rounded-2xl border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-inner z-0 overflow-hidden"
                    ></div>

                    <div class="mt-2.5 flex flex-wrap items-center justify-between text-xs text-[#5E6B60] dark:text-[#BFE3CC] gap-2">
                        <span>Koordinat Pelanggan: <strong class="text-[#14432A] dark:text-[#3FAE6D] font-mono">{{ number_format($latitude, 5) }}, {{ number_format($longitude, 5) }}</strong></span>
                        <span>Lokasi Teknisi: <strong class="text-[#14432A] dark:text-[#3FAE6D] font-mono">{{ number_format($technician->technicianProfile?->latitude ?? 0, 5) }}, {{ number_format($technician->technicianProfile?->longitude ?? 0, 5) }}</strong></span>
                    </div>
                </div>

                <!-- Kartu Rincian Biaya Transparan (AB-002) -->
                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-[#BFE3CC] dark:border-[#1F6B3E] pb-3">
                        <h3 class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span>Rincian Biaya Transparan</span>
                        </h3>
                        <span class="text-[11px] font-bold text-[#1F6B3E] dark:text-[#3FAE6D] bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 px-2 py-0.5 rounded-md">
                            Mode Simulasi Pembayaran
                        </span>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center text-[#5E6B60] dark:text-[#BFE3CC]">
                            <span>Tarif Dasar Jasa Teknisi</span>
                            <span class="font-semibold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($base_service_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-[#5E6B60] dark:text-[#BFE3CC]">
                            <div>
                                <span>Estimasi Jarak Rute Jalan Sebenarnya</span>
                                <span class="text-xs text-[#1F6B3E] dark:text-[#3FAE6D] ml-1 font-bold">({{ number_format($distance_km, 2) }} km)</span>
                            </div>
                            <span class="text-xs">
                                @if ($distance_km >= 10.0)
                                    <span class="text-[#9A6F14] bg-[#FEF8E7] px-2 py-0.5 rounded-md border border-[#F5DE9B] font-semibold">Jarak &ge; 10 km (+Rp10.000)</span>
                                @else
                                    <span class="text-[#1F6B3E] bg-[#DFF2E6] px-2 py-0.5 rounded-md border border-[#BFE3CC] font-semibold">Jarak &lt; 10 km (Bebas Biaya Tambahan Jarak)</span>
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[#5E6B60] dark:text-[#BFE3CC]">
                            <span>Biaya Tambahan Jarak (Aturan Bisnis AB-002)</span>
                            <span class="font-semibold text-[#1B2B20] dark:text-[#F2FAF5]">Rp{{ number_format($distance_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-3 border-t border-[#BFE3CC] dark:border-[#1F6B3E] flex justify-between items-center">
                            <div>
                                <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5] text-base">Total Biaya Pemesanan Jasa</span>
                                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Belum termasuk material opsional (jika ada rekomendasi nantinya)</p>
                            </div>
                            <span class="text-2xl font-black text-[#14432A] dark:text-[#3FAE6D]">Rp{{ number_format($total_service_fee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <x-ui.button
                        type="submit"
                        variant="primary"
                        size="lg"
                    >
                        <span>Kirim Pesanan Jasa</span>
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </x-ui.button>
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
            html: '<div style="background-color:#14432A;color:white;padding:4px 8px;border-radius:8px;font-size:11px;font-weight:bold;box-shadow:0 2px 4px rgba(0,0,0,0.3);border:1px solid #BFE3CC;">🔧 Teknisi</div>',
            iconSize: [65, 25]
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
