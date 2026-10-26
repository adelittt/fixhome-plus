# Analisis Kesenjangan (Gap Analysis) - FixHome Plus

Dokumen ini mendokumentasikan hasil audit repositori awal terhadap seluruh spesifikasi dan kebutuhan sistem **FixHome Plus** untuk proyek akhir Kapita Selekta Sistem Informasi.

---

## 1. Ringkasan Eksekutif

Repositori awal merupakan template dasar berbasis `raugadh/fila-starter:2.0` yang menggunakan Laravel 12, Filament v3.3, dan MariaDB 10.11. Meskipun kerangka kerja inti (Laravel 12 + Filament v3) sudah terpasang, arsitektur dasar belum memenuhi spesifikasi FixHome Plus yang menuntut PostgreSQL 16, arsitektur multi-peran (Customer, Teknisi, Mitra Toko, Admin), pemisahan transaksi jasa dan marketplace material multi-toko, penghitungan jarak OSRM, integrasi kurir pengiriman, serta pemisahan siklus status transaksi.

Pada **Fase 0**, telah dilakukan penyesuaian infrastruktur dan fondasi awal:
- Migrasi database dari MariaDB ke **PostgreSQL 16**.
- Penambahan layanan Docker Compose: `app` (PHP 8.3 FPM), `nginx`, `postgres`, `node`, `mailpit` (opsional), serta service OSRM opsional (dikomentari).
- Pemasangan **Laravel Breeze Livewire** untuk autentikasi antarmuka frontend.
- Pemasangan pustaka peta **Leaflet.js** via npm/Vite.
- Pembersihan script entrypoint agar tidak menimpa file `.env` dan tidak melakukan `migrate:fresh` otomatis yang berisiko menghapus data.

---

## 2. Matriks Komparasi Fitur & Teknologi

| Komponen / Fitur | Kondisi Awal Repositori | Kebutuhan FixHome Plus | Status Fase 0 | Rencana Implementasi |
| :--- | :--- | :--- | :--- | :--- |
| **Framework & Versi** | Laravel 12.0, PHP 8.3 | Laravel 12, PHP 8.3 | Terpenuhi | Dipertahankan |
| **Admin Panel** | Filament v3.3 + Shield + Theme | Filament v3 untuk panel Admin | Terpenuhi & Kompatibel | Dikonfigurasi ulang pada Fase 2 & 8 |
| **Frontend Stack** | Blade, Tailwind CSS 3.4, Vite 6 | Livewire 3 + Blade, Tailwind CSS, Vite | Terpenuhi (Breeze Livewire terpasang) | Fase 2 s/d 8 |
| **Database** | MariaDB 10.11 | **PostgreSQL 16** | Diperbarui ke PostgreSQL 16 | Fase 1: skema & migrasi lengkap |
| **Docker Compose** | php, nginx, db (mariadb) | app (php-fpm), nginx, postgres, node, mailpit (opsional), osrm (opsional) | Terpenuhi | Selesai di Fase 0 |
| **Peta & Geocoding** | Belum ada | OpenStreetMap + Leaflet.js, Nominatim, OSRM | Leaflet terpasang di Vite | Fase 3: Map picker & OSRM calculator |
| **Autentikasi & Role** | Spatie Role (super_admin, user) | 4 Role: Customer, Teknisi, Mitra Toko, Admin | Breeze Livewire terpasang | Fase 2: Role enum, profile, & middleware |
| **Modul Pemesanan Jasa** | Belum ada | Kategori jasa, teknisi, booking, jarak OSRM, rincian biaya | Belum ada | Fase 4 |
| **Perhitungan Biaya & Jasa** | Belum ada | Jarak < 10km Rp0, >= 10km Rp10.000; komisi platform 5%, teknisi 95% | Belum ada | Fase 3 (`PricingService`, `CommissionService`) |
| **Live Chat & Rekomendasi**| Belum ada | Chat Livewire wire:poll, rekomendasi material (opsional beli) | Belum ada | Fase 5 |
| **Pilihan Checkout Jasa** | Belum ada | Tombol "Lihat Marketplace" vs "Selanjutnya" | Belum ada | Fase 5 & 6 |
| **Marketplace Multi-Toko** | Belum ada | Multi-toko, pemecahan order & shipment per toko, komisi 2%, isolasi toko | Belum ada | Fase 6 |
| **Pengiriman & Kurir** | Belum ada | `ShippingServiceInterface` (MockShipping, Biteship sandbox), pelacakan, simulator | Selesai di Fase 7 (Mock & Biteship Adapter) | Fase 7: Terpenuhi & Teruji |
| **Laporan & Audit** | Logging aktivitas Spatie dasar | Laporan keuangan admin terpisah (jasa vs marketplace), ledger, penarikan dana | Belum ada | Fase 8 |

---

## 3. Detail Kesenjangan & Tindakan Solutif

### A. Kompatibilitas Filament v3 dengan Laravel 12
- **Temuan**: File `composer.json` telah menggunakan `"filament/filament": "^3.3"` dan `"laravel/framework": "^12.0"`.
- **Evaluasi**: Filament versi 3.3 telah mendukung Laravel 12 secara penuh dan stabil. Tidak ditemukan konflik dependensi (`composer update` dan upgrade berjalan sukses).
- **Rekomendasi**: Pertahankan Filament v3.3 untuk Panel Admin tanpa perlu menurunkan versi framework.

### B. Lingkungan Database & Docker
- **Temuan Awal**:
  1. `docker-compose.yml` memakai `mariadb:10.11` pada port `13306`.
  2. `php/docker-entrypoint.sh` secara agresif menulis ulang `.env` setiap kali container dijalankan, memaksa konfigurasi MariaDB dan port 3306.
  3. Layanan `node` untuk live bundling Vite belum ada di docker-compose.
  4. Port database tidak sesuai spesifikasi PostgreSQL 16.
- **Tindakan yang Dilakukan di Fase 0**:
  1. Mengganti database service ke `postgres:16-alpine` dengan environment `POSTGRES_DB=fixhome_plus`, `POSTGRES_USER=fixhome`, `POSTGRES_PASSWORD=fixhome_secret`.
  2. Menambahkan service `node` (Node 20 Alpine) untuk menjalankan Vite development server (`npm run dev -- --host 0.0.0.0`).
  3. Menambahkan service `mailpit` untuk penanganan simulasi pengiriman email lokal (SMTP port 1025, Web UI port 8025).
  4. Menambahkan blok service `osrm` opsional dalam bentuk komentar sesuai instruksi.
  5. Menamai service PHP sebagai `app` dengan alias `php` dan memperbarui `nginx/default.conf`.
  6. Memperbaiki `php/docker-entrypoint.sh` agar tidak merusak file `.env` yang sudah ada, serta mendeteksi ketersediaan port PostgreSQL (5432) sebelum menjalankan migrasi.

### C. Autentikasi Frontend (Laravel Breeze Livewire)
- **Temuan Awal**: Starter hanya menyediakan autentikasi admin di `/admin/login` via Filament. Frontend publik belum memiliki sistem login, registrasi, dan manajemen profil untuk Customer, Teknisi, dan Mitra Toko.
- **Tindakan yang Dilakukan di Fase 0**:
  1. Menginstal package `laravel/breeze:^2.4` dan `livewire/volt`.
  2. Menjalankan scaffolding Breeze Livewire dengan pengujian Pest.
  3. Mengatur rute web dan view selamat datang (`welcome.blade.php`) berdesain modern dengan identitas FixHome Plus.

### D. Peta & Geospasial (Leaflet.js & Nominatim & OSRM)
- **Temuan Awal**: Belum ada library peta terpasang.
- **Tindakan yang Dilakukan di Fase 0**:
  1. Menginstal `leaflet` dan `@types/leaflet` melalui npm.
  2. Menghubungkan CSS dan JS Leaflet ke bundle Vite (`resources/css/app.css` dan `resources/js/app.js`).
  3. Pengujian build Vite (`npm run build`) berhasil memproduksi aset bundle tanpa error.

### E. Layanan Pengiriman & Kurir Instan (Fase 7 - AB-013, PK-01, UAT-07)
- **Implementasi**:
  1. Membuat kontrak adapter `InstantCourierServiceInterface` dan `ShippingManager` untuk dynamic driver switching (`mock` dan `biteship`).
  2. Menyediakan `MockInstantCourierService` untuk simulasi lokal mandiri.
  3. Mengimplementasikan `BiteshipInstantCourierService` untuk integrasi gateway API kurir instan (Grab, Gojek, Lalamove Sandbox) dengan penanganan timeout/error terisolasi (`ShippingServiceException`).
  4. Menyediakan antarmuka pelacakan `OrderShipmentTracker` dan simulator kontrol pergerakan kurir instan interaktif di frontend.
  5. Menjamin aturan **UAT-07 & AB-006**: jika adapter pengiriman gagal merespons, muncul pesan kesalahan yang jelas dan alur pesanan jasa perbaikan rumah tetap berjalan normal tanpa terputus.

---

## 4. Rencana Kerja Fase Selanjutnya
- **Fase 7**: **Selesai (Terimplementasi & Lulus 99 Tes)**
- **Fase 8**: Laporan keuangan admin terpisah, pengujian end-to-end, dokumentasi akun demo.

- **Fase 1**: Desain migrasi tabel entitas lengkap (users, profiles, addresses, services, orders, material recommendations, products, carts, shipments, ledger, settings), pembuatan Model Eloquent, relasi, factory, dan seeder komprehensif (Jabodetabek/Tangerang).
- **Fase 2**: Implementasi multi-role auth, policy isolasi mitra toko, verifikasi teknisi dan toko di admin.
- **Fase 3**: Service perhitungan jarak rute OSRM + fallback Haversine 1.3x + caching, serta PricingService komisi 5% jasa.
- **Fase 4**: Katalog jasa, pencarian teknisi terdekat, pemesanan, dan simulasi pembayaran jasa.
- **Fase 5**: Live chat, sistem rekomendasi material oleh teknisi, tombol "Lihat Marketplace" vs "Selanjutnya".
- **Fase 6**: Marketplace multi-toko (pemecahan order & shipment per toko, komisi 2%, checkout transaksional).
- **Fase 7**: Layanan pengiriman MockShipping & Biteship Sandbox, pelacakan, kurir simulator.
- **Fase 8**: Laporan keuangan admin terpisah, pengujian end-to-end, dokumentasi akun demo.
