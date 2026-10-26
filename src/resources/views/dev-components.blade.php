<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#DFF2E6] text-[#14432A] mb-2 border border-[#BFE3CC]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E8B57]"></span>
                    Fase 1: Sistem Desain & Komponen
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1B2B20] dark:text-[#F2FAF5]">
                    Pustaka Komponen UI FixHome Plus
                </h1>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-1">
                    Showcase token warna, tipografi, dan seluruh komponen Blade/Livewire reusable sesuai acuan BRD/PRD.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-ui.button
                    onclick="window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', title: 'Komponen Siap', message: 'Seluruh komponen lolos uji interaktivitas & aksesibilitas WCAG AA.' } }))"
                    variant="primary"
                    size="sm"
                >
                    Uji Notifikasi Toast
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
        <!-- 1. Palet Warna (Design Tokens) -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">1. Palet Warna (Design Tokens)</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Satu sumber kebenaran: CSS Variables, Tailwind Config, dan tema Filament.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-3">
                <div class="bg-[#0F2A1A] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24">
                    <span class="font-bold">green-950</span>
                    <span class="text-[10px] opacity-75">#0F2A1A</span>
                </div>
                <div class="bg-[#14432A] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24">
                    <span class="font-bold">green-900</span>
                    <span class="text-[10px] opacity-75">#14432A</span>
                </div>
                <div class="bg-[#1F6B3E] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24">
                    <span class="font-bold">green-700</span>
                    <span class="text-[10px] opacity-75">#1F6B3E</span>
                </div>
                <div class="bg-[#2E8B57] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24">
                    <span class="font-bold">green-600</span>
                    <span class="text-[10px] opacity-75">#2E8B57</span>
                </div>
                <div class="bg-[#3FAE6D] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24">
                    <span class="font-bold">green-500</span>
                    <span class="text-[10px] opacity-75">#3FAE6D</span>
                </div>
                <div class="bg-[#BFE3CC] text-[#14432A] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24 border border-[#1F6B3E]/20">
                    <span class="font-bold">green-200</span>
                    <span class="text-[10px] opacity-75">#BFE3CC</span>
                </div>
                <div class="bg-[#DFF2E6] text-[#14432A] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24 border border-[#BFE3CC]">
                    <span class="font-bold">green-100</span>
                    <span class="text-[10px] opacity-75">#DFF2E6</span>
                </div>
                <div class="bg-[#F2FAF5] text-[#14432A] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-24 border border-[#E3EBE5]">
                    <span class="font-bold">green-50</span>
                    <span class="text-[10px] opacity-75">#F2FAF5</span>
                </div>
            </div>

            <!-- Status & Netral Tokens -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3 pt-2">
                <div class="bg-[#FFFFFF] dark:bg-white text-[#1B2B20] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20 border border-[#E3EBE5]">
                    <span class="font-bold">Putih</span>
                    <span class="text-[10px] opacity-75">#FFFFFF</span>
                </div>
                <div class="bg-[#5E6B60] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20">
                    <span class="font-bold">Abu Sekunder</span>
                    <span class="text-[10px] opacity-75">#5E6B60</span>
                </div>
                <div class="bg-[#1B2B20] text-white p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20">
                    <span class="font-bold">Teks Utama</span>
                    <span class="text-[10px] opacity-75">#1B2B20</span>
                </div>
                <div class="bg-[#E3EBE5] text-[#1B2B20] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20">
                    <span class="font-bold">Border Netral</span>
                    <span class="text-[10px] opacity-75">#E3EBE5</span>
                </div>
                <div class="bg-[#FEF8E7] text-[#9A6F14] border border-[#F5DE9B] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20">
                    <span class="font-bold">Status: Menunggu</span>
                    <span class="text-[10px] opacity-75">#E0A526</span>
                </div>
                <div class="bg-[#FDF2F0] text-[#B3402F] border border-[#F5B8B0] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20">
                    <span class="font-bold">Status: Batal/Gagal</span>
                    <span class="text-[10px] opacity-75">#B3402F</span>
                </div>
                <div class="bg-[#EDF3F8] text-[#4A6B8A] border border-[#BCD0E2] p-3 rounded-2xl shadow-sm text-xs flex flex-col justify-between h-20">
                    <span class="font-bold">Status: Info</span>
                    <span class="text-[10px] opacity-75">#4A6B8A</span>
                </div>
            </div>
        </section>

        <!-- 2. Tipografi & Skala Font -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">2. Tipografi (Plus Jakarta Sans + Inter)</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Skala ukuran font: 12 / 14 / 16 / 20 / 24 / 32 / 48 px.</p>
            </div>

            <x-ui.card>
                <div class="space-y-4 divide-y divide-[#E3EBE5] dark:divide-[#1F6B3E]">
                    <div class="pt-2">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">48px - Hero Title (Plus Jakarta Sans Bold)</span>
                        <h1 class="text-3xl sm:text-5xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Perbaikan Rumah Beres Tanpa Repot</h1>
                    </div>
                    <div class="pt-4">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">32px - Section Title</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Kategori Layanan Jasa Terpopuler</h2>
                    </div>
                    <div class="pt-4">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">24px - Card / Modal Title</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-[#1B2B20] dark:text-[#F2FAF5]">Rekomendasi Material Bangunan</h3>
                    </div>
                    <div class="pt-4">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">20px - Subtitle / Group</span>
                        <h4 class="text-lg sm:text-xl font-semibold text-[#1B2B20] dark:text-[#F2FAF5]">Pipa PVC &amp; Perlengkapan Sanitasi</h4>
                    </div>
                    <div class="pt-4">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">16px - Body Text (Inter Regular)</span>
                        <p class="text-base text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed">
                            Teknisi memberikan rekomendasi spesifikasi material yang akurat setelah memeriksa kondisi langsung di lokasi rumah Anda.
                        </p>
                    </div>
                    <div class="pt-4">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">14px - Control &amp; Form Label</span>
                        <p class="text-sm font-medium text-[#5E6B60] dark:text-[#BFE3CC]">
                            Estimasi kurir instan tiba dalam 2-4 jam sejak pesanan disetujui Mitra Toko.
                        </p>
                    </div>
                    <div class="pt-4">
                        <span class="text-[11px] font-bold text-[#5E6B60] dark:text-[#BFE3CC]">12px - Caption / Badge / Helper</span>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                            * Pembelian material melalui marketplace bersifat opsional.
                        </p>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <!-- 3. Tombol & Variasi Aksi -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">3. Tombol (Buttons) &amp; Mikro-Interaksi</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Radius 12px, efek tekan (scale 0.97), transisi halus 180ms, dan target sentuh min 44px.</p>
            </div>

            <x-ui.card>
                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider mb-3">Varian Tombol</h4>
                        <div class="flex flex-wrap items-center gap-3">
                            <x-ui.button variant="primary">Tombol Primer</x-ui.button>
                            <x-ui.button variant="secondary">Tombol Sekunder</x-ui.button>
                            <x-ui.button variant="outline">Tombol Outline</x-ui.button>
                            <x-ui.button variant="ghost">Tombol Ghost</x-ui.button>
                            <x-ui.button variant="danger">Tombol Destruktif</x-ui.button>
                            <x-ui.button variant="primary" disabled>Dinonaktifkan</x-ui.button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider mb-3">Ukuran Tombol</h4>
                        <div class="flex flex-wrap items-center gap-3">
                            <x-ui.button variant="primary" size="sm">Ukuran Kecil (sm)</x-ui.button>
                            <x-ui.button variant="primary" size="md">Ukuran Standar (md)</x-ui.button>
                            <x-ui.button variant="primary" size="lg">Ukuran Besar (lg)</x-ui.button>
                        </div>
                    </div>

                    <!-- Syarat Khusus Aturan Bisnis: Tombol Lihat vs Lewati Marketplace SETARA -->
                    <div class="p-4 bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-2xl border border-[#BFE3CC] dark:border-[#1F6B3E]">
                        <div class="flex items-center gap-2 mb-2 text-[#14432A] dark:text-[#3FAE6D] font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Aturan Bisnis AB-006: Visual Equality "Lihat" vs "Lewati" Marketplace</span>
                        </div>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mb-4">
                            Tombol "Lihat Marketplace" dan "Lewati Marketplace" wajib memiliki ukuran, bobot visual, dan kontras setara.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <x-ui.button variant="primary" size="md" class="flex-1">
                                Lihat Marketplace
                            </x-ui.button>
                            <x-ui.button variant="secondary" size="md" class="flex-1">
                                Lewati Marketplace
                            </x-ui.button>
                        </div>
                        <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] mt-2 italic text-center">
                            * Pembelian material bersifat opsional.
                        </p>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <!-- 4. Komponen Formulir & Input -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">4. Formulir &amp; Kontrol Masukan (Form Controls)</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Fokus terlihat (focus-visible), aksesibilitas label, ikon pendamping, dan pesan error.</p>
            </div>

            <x-ui.card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.input
                        label="Nama Lengkap"
                        placeholder="Contoh: Budi Santoso"
                        hint="Sesuai kartu identitas atau profil Anda."
                        required
                    />

                    <x-ui.input
                        label="Alamat Email (Dengan Error State)"
                        type="email"
                        value="email-salah@"
                        error="Format alamat email tidak valid."
                        required
                    />

                    <x-ui.select label="Kategori Pekerjaan Jasa" required>
                        <option value="">Pilih Kategori Perbaikan</option>
                        <option value="listrik">Instalasi &amp; Perbaikan Listrik</option>
                        <option value="pipa">Pipa &amp; Saluran Air</option>
                        <option value="ac">Servis &amp; Cuci AC</option>
                        <option value="atap">Perbaikan Atap &amp; Kebocoran</option>
                        <option value="cat">Pengecatan Tembok</option>
                    </x-ui.select>

                    <x-ui.input
                        label="Pencarian Material"
                        placeholder="Cari pipa PVC, saklar, cat dinding..."
                    >
                        <x-slot name="leadingIcon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </x-slot>
                    </x-ui.input>

                    <div class="md:col-span-2">
                        <x-ui.textarea
                            label="Keluhan atau Deskripsi Masalah"
                            placeholder="Jelaskan kendala di rumah secara rinci..."
                            hint="Maksimal 500 karakter."
                            rows="3"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <x-ui.file-upload
                            label="Unggah Foto Kerusakan / Foto Lokasi (Opsional)"
                            hint="Format JPG, PNG, atau WEBP maksimal 5MB."
                        />
                    </div>
                </div>
            </x-ui.card>
        </section>

        <!-- 5. Chip & Badge Status -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">5. Badge &amp; Chip Status</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Menampilkan status jasa, pembayaran, pesanan produk, dan pengiriman secara konsisten.</p>
            </div>

            <x-ui.card>
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.badge variant="success" dot>Selesai / Berhasil</x-ui.badge>
                        <x-ui.badge variant="warning" dot>Menunggu Konfirmasi</x-ui.badge>
                        <x-ui.badge variant="danger" dot>Dibatalkan / Gagal</x-ui.badge>
                        <x-ui.badge variant="info" dot>Dalam Pengiriman (Kurir Instan)</x-ui.badge>
                        <x-ui.badge variant="neutral" dot>Draf Rekomendasi</x-ui.badge>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <x-ui.badge variant="success" size="sm">Jasa Berjalan</x-ui.badge>
                        <x-ui.badge variant="warning" size="sm">Pembayaran Tertunda</x-ui.badge>
                        <x-ui.badge variant="info" size="sm">Mode Simulasi</x-ui.badge>
                        <x-ui.badge variant="neutral" size="sm">Toko Mitra</x-ui.badge>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <!-- 6. Stepper & Tabs -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">6. Stepper &amp; Tab Navigasi</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Alur bertahap formulir booking jasa 3-langkah &amp; navigasi tab ARIA detail pesanan.</p>
            </div>

            <x-ui.card title="Stepper Alur Pemesanan Jasa">
                <x-ui.stepper
                    :steps="[
                        ['title' => 'Langkah 1', 'desc' => 'Pilih Tanggal & Jam'],
                        ['title' => 'Langkah 2', 'desc' => 'Tentukan Alamat & Peta'],
                        ['title' => 'Langkah 3', 'desc' => 'Konfirmasi & Rincian Biaya'],
                    ]"
                    :currentStep="2"
                />
            </x-ui.card>

            <x-ui.card title="Tab Detail Pesanan Jasa">
                <x-ui.tabs
                    :tabs="[
                        ['id' => 'ringkasan', 'label' => 'Ringkasan Pesanan'],
                        ['id' => 'chat', 'label' => 'Obrolan Teknisi', 'badge' => '2'],
                        ['id' => 'material', 'label' => 'Rekomendasi Material', 'badge' => '3'],
                    ]"
                    defaultTab="material"
                >
                    <div class="p-4 bg-[#F2FAF5] dark:bg-[#14432A]/40 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E]">
                        <p class="text-sm font-bold text-[#14432A] dark:text-[#3FAE6D] mb-1">Tab Material Aktif</p>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                            Menampilkan daftar butir material yang direkomendasikan teknisi. Pelanggan dapat memilih "Lihat Marketplace" atau "Lewati Marketplace".
                        </p>
                    </div>
                </x-ui.tabs>
            </x-ui.card>
        </section>

        <!-- 7. Modal & Drawer -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">7. Modal &amp; Drawer (Keranjang Mobile)</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Dukungan keyboard Escape, fokus otomatis, dan transisi slide-in.</p>
            </div>

            <x-ui.card>
                <div class="flex flex-wrap gap-4">
                    <x-ui.button
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'demo-modal' }))"
                        variant="primary"
                    >
                        Buka Contoh Modal Konfirmasi
                    </x-ui.button>

                    <x-ui.button
                        onclick="window.dispatchEvent(new CustomEvent('open-drawer', { detail: 'demo-drawer' }))"
                        variant="secondary"
                    >
                        Buka Contoh Drawer Keranjang
                    </x-ui.button>
                </div>
            </x-ui.card>

            <!-- Modal Showcase Instance -->
            <x-ui.modal name="demo-modal" title="Konfirmasi Batalkan Pesanan">
                <p class="text-sm text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                    Apakah Anda yakin ingin membatalkan pesanan jasa ini? Tindakan ini tidak dapat dibatalkan.
                </p>

                <x-slot name="footer">
                    <x-ui.button
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'demo-modal' }))"
                        variant="ghost"
                        size="sm"
                    >
                        Kembali
                    </x-ui.button>
                    <x-ui.button
                        onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'demo-modal' })); window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'danger', message: 'Pesanan telah dibatalkan.' } }))"
                        variant="danger"
                        size="sm"
                    >
                        Ya, Batalkan
                    </x-ui.button>
                </x-slot>
            </x-ui.modal>

            <!-- Drawer Showcase Instance -->
            <x-ui.drawer name="demo-drawer" title="Keranjang Belanja (2 Produk)">
                <div class="space-y-4">
                    <div class="p-3 bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E]">
                        <p class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] mb-1">Toko Sumber Rezeki</p>
                        <div class="flex justify-between text-xs text-[#1B2B20] dark:text-[#F2FAF5]">
                            <span>Pipa PVC 1/2 inch (x2)</span>
                            <span class="font-bold">Rp70.000</span>
                        </div>
                    </div>

                    <div class="p-3 bg-[#F2FAF5] dark:bg-[#14432A]/50 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E]">
                        <p class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] mb-1">Toko Mitra Bangun Mandiri</p>
                        <div class="flex justify-between text-xs text-[#1B2B20] dark:text-[#F2FAF5]">
                            <span>Cat Tembok Putih 5kg (x1)</span>
                            <span class="font-bold">Rp135.000</span>
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
                            <span>Total Produk:</span>
                            <span class="text-[#14432A] dark:text-[#3FAE6D]">Rp205.000</span>
                        </div>
                        <x-ui.button :href="route('checkout.index')" variant="primary" class="w-full" size="md">
                            Lanjut ke Checkout
                        </x-ui.button>
                    </div>
                </x-slot>
            </x-ui.drawer>
        </section>

        <!-- 8. Komponen Kartu Domain (Teknisi, Produk, Stat, Rating) -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">8. Komponen Domain Khusus (Domain Components)</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">StatCard, TechnicianCard, ProductCard, Rating bintang, dan Skeleton loading.</p>
            </div>

            <!-- Stat Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-ui.stat-card
                    title="Pendapatan Teknisi (95%)"
                    value="Rp1.425.000"
                    trend="+12%"
                    trendType="up"
                    subtitle="Bulan ini"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-slot>
                </x-ui.stat-card>

                <x-ui.stat-card
                    title="Pekerjaan Selesai"
                    value="18 Jasa"
                    trend="+3"
                    trendType="up"
                    subtitle="Pekan ini"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-slot>
                </x-ui.stat-card>

                <x-ui.stat-card
                    title="Ulasan Positif"
                    value="4.9 / 5.0"
                    subtitle="Dari 24 pelanggan (contoh)"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </x-slot>
                </x-ui.stat-card>
            </div>

            <!-- Technician & Product Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
                <!-- Technician Card Showcase -->
                <x-ui.technician-card
                    name="Ahmad Junaedi"
                    :skills="['Listrik', 'Pipa Air', 'AC']"
                    :rating="4.9"
                    :reviewCount="32"
                    :basePrice="75000"
                    :distance="12.4"
                    :isAvailable="true"
                    bookUrl="#"
                />

                <!-- Product Card Showcase -->
                <x-ui.product-card
                    name="Pipa PVC Rucika D 1/2 Inch Aw Awet Anti Bocor"
                    :price="35000"
                    storeName="TB. Mitra Abadi Jaya"
                    category="Pipa & Sambungan"
                    :stock="8"
                    addToCartAction="alert('Produk ditambahkan ke keranjang')"
                />

                <!-- Skeleton Showcase -->
                <div class="space-y-3">
                    <p class="text-xs font-bold text-[#5E6B60] dark:text-[#BFE3CC]">State Memuat (Skeleton)</p>
                    <x-ui.skeleton type="card" />
                </div>
            </div>
        </section>

        <!-- 9. Empty State & Data Table -->
        <section class="space-y-4">
            <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] pb-3">
                <h2 class="text-lg font-bold text-[#14432A] dark:text-[#3FAE6D]">9. State Kosong (Empty State) &amp; Tabel Data</h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Desain antarmuka ketika data belum tersedia atau hasil filter kosong.</p>
            </div>

            <x-ui.data-table
                :headers="['ID Pesanan', 'Layanan / Produk', 'Pelanggan', 'Tanggal', 'Total', 'Status']"
                :empty="false"
            >
                <tr class="hover:bg-[#F2FAF5]/50 dark:hover:bg-[#14432A]/50 transition">
                    <td class="px-5 py-3.5 font-bold text-[#14432A] dark:text-[#3FAE6D]">#SRV-2026-001</td>
                    <td class="px-5 py-3.5">Perbaikan Korsleting Listrik</td>
                    <td class="px-5 py-3.5">Budi Hartono</td>
                    <td class="px-5 py-3.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC]">26 Okt 2026</td>
                    <td class="px-5 py-3.5 font-bold">Rp120.000</td>
                    <td class="px-5 py-3.5">
                        <x-ui.badge variant="success" size="sm" dot>Selesai</x-ui.badge>
                    </td>
                </tr>
                <tr class="hover:bg-[#F2FAF5]/50 dark:hover:bg-[#14432A]/50 transition">
                    <td class="px-5 py-3.5 font-bold text-[#14432A] dark:text-[#3FAE6D]">#PRD-2026-089</td>
                    <td class="px-5 py-3.5">Cat Tembok Avian 5kg (x2)</td>
                    <td class="px-5 py-3.5">Dewi Lestari</td>
                    <td class="px-5 py-3.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC]">26 Okt 2026</td>
                    <td class="px-5 py-3.5 font-bold">Rp270.000</td>
                    <td class="px-5 py-3.5">
                        <x-ui.badge variant="info" size="sm" dot>Dikirim</x-ui.badge>
                    </td>
                </tr>
            </x-ui.data-table>

            <div class="pt-4">
                <x-ui.empty-state
                    title="Belum Ada Pesanan Berlangsung"
                    description="Anda belum memiliki pesanan aktif saat ini. Silakan cari teknisi atau telusuri marketplace material."
                >
                    <x-ui.button :href="route('service.catalog')" variant="primary" size="sm" wire:navigate>
                        Cari Teknisi Sekarang
                    </x-ui.button>
                </x-ui.empty-state>
            </div>
        </section>
    </div>
</x-app-layout>
