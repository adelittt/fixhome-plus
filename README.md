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
- **Pengujian**: PHPUnit / Pest PHP (109 tests, 463 assertions - 100% pass)

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

# Menjalankan feature test integrasi kurir Biteship & Sandbox
docker exec fixhome_plus_app php artisan test tests/Feature/BiteshipShippingTest.php

# Menjalankan end-to-end UAT test (UAT-01 s.d. UAT-10)
docker exec fixhome_plus_app php artisan test tests/Feature/EndToEndUatTest.php
```

Panduan operasional dan daftar akun demo selengkapnya dapat dilihat di:
👉 **[docs/DEMO_ACCOUNTS.md](docs/DEMO_ACCOUNTS.md)**

---

## 7. Status Fase Pengerjaan (Semua 8 Fase Selesai)

- **Fase 1 (Selesai)**: Skema data 21 tabel, Model Eloquent, relasi, multi-peran, dan seeder Jabodetabek.
- **Fase 2 (Selesai)**: Pemesanan jasa, integrasi Leaflet map picker & geocoding nominatim, alur kerja teknisi, dan live chat.
- **Fase 3 (Selesai)**: Rekomendasi butir material teknisi dan tombol setara "Lihat Marketplace" vs "Lewati Marketplace" (AB-006).
- **Fase 4 (Selesai)**: Marketplace multi-toko, keranjang belanja multi-store, checkout pemecahan order per toko fisik (AB-010).
- **Fase 5 (Selesai)**: Panel Mitra Toko Filament, isolasi data toko (AB-009), verifikasi dokumen mitra (AB-011).
- **Fase 6 (Selesai)**: Otomatisasi pencatatan komisi via Model Observer, pemisahan mutlak tabel komisi 5% dan 2% (AB-005, K-5).
- **Fase 7 (Selesai)**: Layanan kurir instan terpadu (`ShippingManager`, `MockInstantCourierService`, `BiteshipInstantCourierService` Sandbox), pelacak interaktif, dan penanganan kegagalan kurir (UAT-07).
- **Fase 8 (Selesai)**: Laporan Keuangan Admin Terpisah di Filament (`/admin/commission-report`), ekspor CSV ganda, pengujian end-to-end lengkap UAT-01 s.d. UAT-10 (`EndToEndUatTest.php` - 109 tests passed), dan dokumentasi akun demo.
