# FixHome Plus 🛠️🏡

> **Platform Digital FixHome Plus: Integrasi Pemesanan Jasa Perbaikan Rumah dan Marketplace Material Bangunan Berbasis Kebutuhan Customer**  
> Proyek Akhir Kapita Selekta Sistem Informasi

---

## 1. Tentang Proyek

FixHome Plus mengintegrasikan dua domain bisnis inti:
1. **Layanan Pemesanan Jasa**: Pemesanan jasa perbaikan rumah oleh customer dengan teknisi terverifikasi, perhitungan jarak rute presisi, live chat, dan rekomendasi material.
2. **Marketplace Material Multi-Toko**: Katalog material bangunan dari berbagai mitra toko fisik terverifikasi. Pembelian material bersifat **sepenuhnya opsional** bagi customer (tombol "Lihat Marketplace" vs "Lewati Marketplace" setara), dan pesanan multi-toko otomatis dipecah per toko fisik.

---

## 2. Tech Stack Inti

- **Framework**: Laravel 12.0 (PHP 8.3 FPM)
- **Panel Admin & Panel Toko**: Filament v3 (v3.3 - terbukti kompatibel penuh dengan Laravel 12)
- **Frontend Interaktif**: Livewire 3 + Blade, Tailwind CSS, Leaflet.js
- **Database**: PostgreSQL 16 (database operasional: `fixhome_plus`, database testing: `fixhome_plus_test`)
- **Containerization**: Docker Compose (`app`, `nginx`, `postgres:16-alpine`)
- **Pengujian**: PHPUnit / Pest PHP (43 tests, 146 assertions - 100% pass)

---

## 3. Rencana Penyedia Routing (Dev Environment)

Sesuai ketentuan, jarak antara teknisi dan pelanggan wajib merupakan **jarak rute** (bukan garis lurus):
- **Penyedia Utama (Dev)**: OSRM (Open Source Routing Machine) API publik (`https://router.project-osrm.org`) atau instance lokal OSRM Docker.
- **Penyedia Alternatif (Fallback)**: OpenRouteService API.
- **Lingkungan Pengujian (Testing)**: Seluruh test unit & feature menggunakan kontrak `RouteDistanceServiceInterface` dengan implementasi `FakeRouteDistanceService` sehingga tidak memanggil network/API eksternal saat proses automated testing.

---

## 4. Panduan Menjalankan Sistem dari Nol

### Prasyarat
- Docker Engine & Docker Compose v2+
- Port yang dibutuhkan: `80` (HTTP), `443` (HTTPS), `5432` (PostgreSQL)

### Langkah Instalasi

1. **Clone Repositori**:
   ```bash
   git clone <repo-url> fixhome-plus
   cd fixhome-plus
   ```

2. **Siapkan Konfigurasi Lingkungan (`.env`)**:
   ```bash
   cp .env.example .env
   cp src/.env.example src/.env
   ```

3. **Jalankan Container Docker**:
   ```bash
   docker compose up -d --build
   ```
   *Saat pertama kali dijalankan, PostgreSQL otomatis membuat basis data `fixhome_plus` dan `fixhome_plus_test`.*

4. **Jalankan Migrasi dan Seeder**:
   ```bash
   docker exec fixhome_plus_app php artisan migrate --seed
   ```

5. **Akses Antarmuka**:
   - **Frontend Publik**: [http://localhost](http://localhost)
   - **Panel Admin Filament**: [http://localhost/admin](http://localhost/admin)
   - **Panel Mitra Toko Filament**: [http://localhost/toko](http://localhost/toko)

---

## 5. Akun Pengguna Bawaan (Seeder)

Semua akun default memiliki kata sandi: `password`

| Peran | Nama | Email | Akses Panel | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Admin FixHome Plus | `admin@fixhome.test` | `/admin` | Verifikasi teknisi/toko, kelola kategori, audit komisi |
| **Mitra Toko 1** | Hendra Gunawan (TB Jaya Abadi) | `toko.jayaabadi@fixhome.test` | `/toko` | Profil toko & 5 produk sanitari di Tangerang |
| **Mitra Toko 2** | Linda Wijaya (TB Sumber Rejeki) | `toko.sumberrejeki@fixhome.test` | `/toko` | Profil toko & 5 produk listrik di Jakarta Barat |
| **Teknisi 1** | Budi Santoso | `budi.teknisi@fixhome.test` | Antarmuka Jasa | Spesialis Pipa (Tangerang) |
| **Teknisi 2** | Agus Setiawan | `agus.teknisi@fixhome.test` | Antarmuka Jasa | Spesialis Listrik (Jakarta Barat) |
| **Teknisi 3** | Dedi Pratama | `dedi.teknisi@fixhome.test` | Antarmuka Jasa | Spesialis AC (Jakarta Selatan) |
| **Pelanggan** | Rina Pelanggan | `pelanggan@fixhome.test` | Antarmuka Publik | Akun pemesan jasa |

---

## 6. Cara Menjalankan Pengujian (Testing)

Pengujian berjalan di atas PostgreSQL database khusus testing (`fixhome_plus_test`):

```bash
# Menjalankan seluruh test suite (Unit & Feature)
docker exec fixhome_plus_app php artisan test

# Menjalankan unit test komisi (K-1 s.d. K-5)
docker exec fixhome_plus_app php artisan test tests/Unit/CommissionServiceTest.php

# Menjalankan unit test jarak & biaya jarak
docker exec fixhome_plus_app php artisan test tests/Unit/DistancePricingTest.php

# Menjalankan feature test isolasi data toko (UAT-05)
docker exec fixhome_plus_app php artisan test tests/Feature/StoreDataIsolationTest.php

# Menjalankan feature test biaya jarak pesanan (UAT-06)
docker exec fixhome_plus_app php artisan test tests/Feature/DistanceFeeOrderTest.php
```

---

## 7. Status Fase Pengerjaan

- **Fase 1 (Selesai)**:
  - Fondasi Docker: PHP 8.3 FPM (`app`), Nginx (`nginx`), PostgreSQL 16 (`postgres`).
  - Database PostgreSQL: Migrasi 21 tabel entitas PRD 10.1 dengan tipe integer rupiah & enum status terpisah.
  - Autentikasi multi-peran & authorization matrix (Policy).
  - Panel Admin Filament (`/admin`): Manajemen pengguna, verifikasi teknisi & toko, kategori jasa & produk.
  - Panel Mitra Toko Filament (`/toko`): Multi-tenancy & isolasi data toko (`getEloquentQuery`).
  - Seeder: 1 admin, kategori, 3 teknisi terverifikasi, 2 mitra toko dengan produk, pengaturan sistem PK-15.
  - Service Layer: `CommissionService` (K-1 s.d. K-5, pembulatan integer rupiah), `PricingService`, `RouteDistanceServiceInterface`.
  - Automated Tests: 43 test case, 146 assertions (100% passed pada database PostgreSQL).
- **Fase 2 (Selesai)**: Pemesanan jasa, integrasi Leaflet map picker & geocoding nominatim, alur kerja teknisi, dan live chat WebSocket/Livewire.
- **Fase 3 (Selesai)**: Rekomendasi butir material oleh teknisi, tombol setara "Lihat Marketplace" vs "Lewati Marketplace" (AB-006 & PRD 4.2).
- **Fase 4 (Selesai)**: Marketplace material multi-toko, keranjang belanja multi-store, checkout transaksional otomatis memecah order & ongkos kirim per toko fisik (AB-004, AB-008, AB-009).
- **Fase 5 (Selesai)**: Adapter kurir instan simulasi (`MockInstantCourierService`), pelacakan visual paket real-time (`OrderShipmentTracker`), dan simulator perjalanan paket.
- **Fase 6 (Selesai)**:
  - Otomatisasi pencatatan komisi melalui model observer (`ServiceOrderObserver` dan `ProductOrderObserver`).
  - Pemisahan ketat tabel komisi (`service_commissions` 5% vs `marketplace_commissions` 2%) sesuai aturan bisnis AB-005 dan K-1 s.d. K-5.
  - Ongkir dikecualikan dari dasar perhitungan komisi marketplace (AB-004).
  - Layar 19: Halaman Laporan Komisi Terpisah di Admin Filament (`/admin/commission-report`) dengan dual-table layout, filter tanggal/status, KPI cards, dan ekspor CSV.
  - Layar 20: Pengawasan Pesanan Jasa di Admin Filament (`/admin/service-orders`).
  - Panel Mitra Toko Filament (`/toko/marketplace-commissions`): Laporan penghasilan toko 98% terisolasi per toko fisik (AB-009).
  - Dasbor Pekerjaan Teknisi (`/teknisi/pesanan`): Kartu total pendapatan bersih 95% dan rincian breakdown komisi per pesanan.
  - Pengaturan Sistem Dinamis PK-15 (`service_commission_includes_distance_fee`).
  - Automated Tests: 9 feature test case komprehensif pada `CommissionLifecycleAndReportingTest` (100% passed).
- **Fase 7 (Berikutnya)**: Laporan keuangan komprehensif, evaluasi audit akhir, dan pengujian end-to-end seluruh skenario UAT-01 s.d. UAT-10.
# fixhome-plus
