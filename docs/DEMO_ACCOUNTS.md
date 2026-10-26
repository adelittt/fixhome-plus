# Panduan Akun Demo & Verifikasi End-to-End (Fase 8)
# FixHome Plus 🛠️🏡

Dokumen ini merupakan panduan operasional resmi untuk pengujian dan demonstrasi sistem **FixHome Plus** berdasarkan spesifikasi **BRD v1.0**, **PRD v1.0**, serta skenario **UAT-01 s.d. UAT-10**.

---

## 1. Daftar Akun Pengguna Bawaan (Default Seeder)

Semua akun demo di bawah ini telah dikonfigurasi melalui database seeder dengan kata sandi seragam:
```text
Kata Sandi Global: password
```

### A. Administrator Platform
* **Akses URL**: `http://localhost/admin`
* **Email**: `admin@fixhome.test`
* **Nama**: Admin FixHome Plus
* **Tanggung Jawab Utama**:
  - Verifikasi berkas mitra teknisi dan mitra toko (**AB-011**).
  - Mengelola kategori layanan jasa dan kategori produk material.
  - Memantau audit seluruh transaksi pesanan jasa dan pesanan toko (**AB-014**).
  - Mengakses **Laporan Komisi Terpisah** (`/admin/commission-report`) dan mengunduh berkas CSV komisi jasa 5% serta komisi produk 2% (**AB-005 & K-5**).
  - Mengelola pengaturan parameter sistem di `system_settings` (**PK-15**).

---

### B. Mitra Teknisi (3 Bidang Keahlian Jabodetabek)
* **Akses URL**: `http://localhost/login` (kemudian ke `/teknisi/pesanan`)
* **Daftar Teknisi**:
  1. **Budi Santoso (Spesialis Pipa & Sanitari - Tangerang)**
     - **Email**: `budi.teknisi@fixhome.test`
     - **Tarif Dasar**: Rp75.000 / jasa
     - **Lokasi Basis**: Jl. Daan Mogot KM 21, Tanah Tinggi, Kota Tangerang
  2. **Agus Setiawan (Spesialis Instalasi Listrik - Jakarta Barat)**
     - **Email**: `agus.teknisi@fixhome.test`
     - **Tarif Dasar**: Rp85.000 / jasa
     - **Lokasi Basis**: Jl. Kebon Jeruk Raya No. 45, Kebon Jeruk, Jakarta Barat
  3. **Dedi Pratama (Spesialis Perawatan AC - Jakarta Selatan)**
     - **Email**: `dedi.teknisi@fixhome.test`
     - **Tarif Dasar**: Rp90.000 / jasa
     - **Lokasi Basis**: Jl. RS Fatmawati No. 88, Cilandak, Jakarta Selatan
* **Fitur Teknisi**:
  - Menerima / menolak pesanan jasa yang masuk.
  - Live chat interaktif dengan pelanggan pemesan.
  - Menyusun dan merevisi butir rekomendasi material bangunan (**AB-007**).
  - Memperbarui status pengerjaan (`berlangsung` &rarr; `selesai`).
  - Melihat riwayat kerja dengan transparansi bagian pendapatan 95% tanpa melihat data toko (**AB-001**).

---

### C. Mitra Toko Material Bangunan (2 Toko Fisik)
* **Akses URL**: `http://localhost/toko`
* **Daftar Mitra Toko**:
  1. **TB Jaya Abadi (Tangerang)**
     - **Email**: `toko.jayaabadi@fixhome.test`
     - **Pemilik**: Hendra Gunawan
     - **Alamat Toko**: Jl. Merdeka No. 100, Sukasari, Kota Tangerang
     - **Katalog Produk**: Pipa PVC AW Wavin 1/2", Lem Isarplas, Stop Kran Onda, Seal Tape, Knee L-Bow.
  2. **TB Sumber Rejeki (Jakarta Barat)**
     - **Email**: `toko.sumberrejeki@fixhome.test`
     - **Pemilik**: Linda Wijaya
     - **Alamat Toko**: Jl. Panjang Arteri No. 50, Kebon Jeruk, Jakarta Barat
     - **Katalog Produk**: Kabel Listrik NYM Eterna, MCB Schneider 10A, Stop Kontak Panasonic, Saklar Dobel, Isolasi Listrik.
* **Fitur Toko**:
  - Isolasi data mutlak (**AB-009**): hanya mengelola produk, harga, stok, dan pesanan tokonya sendiri.
  - Memproses pesanan masuk (`dibayar` &rarr; `diproses`).
  - Memanggil penjemputan kurir instan via adapter (`ShippingManager` - Mock / Biteship Sandbox).

---

### D. Pelanggan (Customer)
* **Akses URL**: `http://localhost/login` (atau daftar akun baru di `/register`)
* **Email**: `pelanggan@fixhome.test`
* **Nama**: Rina Pelanggan
* **Alamat Default**: Jl. Daan Mogot KM 11, Jakarta Barat
* **Fitur Pelanggan**:
  - Mencari layanan dan memesan teknisi terdekat dengan penghitungan rute peta presisi.
  - Menikmati kebebasan memilih material: tombol **"Lihat Marketplace"** vs **"Lewati Marketplace"** dengan perlakuan visual setara (**AB-006**).
  - Belanja dari multi-toko dalam satu keranjang belanja dengan pemecahan pesanan otomatis (**AB-010**).
  - Melacak pengiriman kurir instan secara waktu nyata dengan kontrol simulator (**AB-013, PK-11**).
  - Memberikan ulasan dan rating bintang setelah jasa tuntas (**AB-015**).

---

## 2. Matriks Pengujian Skenario Penerimaan (UAT-01 s.d. UAT-10)

Seluruh skenario berikut telah diverifikasi secara otomatis melalui rangkaian pengujian di [`tests/Feature/EndToEndUatTest.php`](file:///root/perkuliahan/fixhome-plus/src/tests/Feature/EndToEndUatTest.php):

| ID UAT | Skenario Bisnis yang Diuji | Hasil yang Terverifikasi | Status |
| :---: | :--- | :--- | :---: |
| **UAT-01** | Pesan jasa, teknisi menerima, pekerjaan selesai tanpa rekomendasi material. | Pesanan jasa berstatus `selesai`. Komisi jasa 5% terbentuk otomatis, tidak ada catatan komisi marketplace yang dibuat. | **LULUS** |
| **UAT-02** | Rekomendasi dibuat oleh teknisi, pelanggan memilih tombol "Lewati Marketplace". | Alur jasa berlanjut normal hingga tuntas. Komisi jasa 5% tercatat; tidak ada komisi marketplace (**K-4**). | **LULUS** |
| **UAT-03** | Pelanggan memilih "Lihat Marketplace" dan membeli produk dari 1 toko mitra. | Pesanan produk dibuat, pengiriman kurir instan diproses, dan komisi marketplace 2% tercatat dari nilai produk murni tanpa ongkir (**K-1**). | **LULUS** |
| **UAT-04** | Pelanggan membeli produk dari 2 toko mitra berbeda dalam satu keranjang. | Terbentuk 2 pesanan produk terpisah (`ProductOrder`) dengan ongkir masing-masing. Komisi 2% tercatat per baris toko independen (**AB-010 & K-2**). | **LULUS** |
| **UAT-05** | Mitra Toko A berupaya mengakses/mengedit produk milik Mitra Toko B. | Akses ditolak oleh sistem dengan respons `403 Forbidden` / `404 Not Found`, membuktikan data isolation berjalan sempurna (**AB-009**). | **LULUS** |
| **UAT-06** | Jarak rute teknisi ke lokasi pelanggan $\ge 10\text{ km}$. | Biaya tambahan jarak sebesar **Rp10.000** otomatis ditambahkan ke rincian tagihan jasa (**AB-002**). | **LULUS** |
| **UAT-07** | Adapter penyedia pengiriman (Biteship/Kurir) mengalami gangguan/error. | Muncul pesan kesalahan yang informatif dan ramah dalam Bahasa Indonesia, dan alur pesanan jasa perbaikan rumah **tetap berjalan normal tanpa terputus**. | **LULUS** |
| **UAT-08** | Salah satu mitra toko menolak pesanan produk material. | Hanya pesanan toko bersangkutan yang berstatus `ditolak_toko`; pesanan toko lain tetap berjalan normal. | **LULUS** |
| **UAT-09** | Pelanggan memesan jasa dan membeli produk marketplace secara terintegrasi. | Terbentuk dua catatan komisi independen pada tabel terpisah: `service_commissions` (5%) dan `marketplace_commissions` (2%) (**AB-005 & K-5**). | **LULUS** |
| **UAT-10** | Toko mengubah harga produk di katalog setelah pelanggan checkout. | Nilai harga pada butir pesanan lama (`ProductOrderItem`) tetap mengunci harga awal saat transaksi dilakukan. | **LULUS** |

---

## 3. Panduan Pengujian Manual via Web Browser

### Langkah 1: Skenario Pemesanan Jasa & Rekomendasi Material (UAT-02 & UAT-03)
1. Buka [http://localhost](http://localhost) dan login sebagai **Pelanggan** (`pelanggan@fixhome.test`).
2. Masuk ke halaman **Cari Jasa**, pilih kategori **Perbaikan Pipa**, dan pilih teknisi **Budi Santoso**.
3. Isi formulir pemesanan jasa (deskripsi keluhan, tanggal, alamat). Amati kalkulasi biaya jarak secara transparan.
4. Klik **Kirim Pemesanan Jasa**.
5. Buka tab browser baru (atau mode incognito), login sebagai **Teknisi** (`budi.teknisi@fixhome.test`), masuk ke `/teknisi/pesanan`.
6. Klik **Terima Pesanan**, lalu masuk ke tab **Rekomendasi Material** untuk menambahkan butir barang (misal: Pipa PVC AW 1/2" sebanyak 2 batang).
7. Kembali ke tab Pelanggan, muat ulang halaman detail pesanan:
   - Amati tersedianya **Dua Tombol Pilihan Setara**: tombol hijau *"Lihat Marketplace Material"* berdampingan seimbang dengan tombol putih-hijau *"Lewati Marketplace"*.
   - Bila memilih *"Lewati Marketplace"*, pesanan jasa tetap berlanjut tanpa hambatan.
   - Bila memilih *"Lihat Marketplace"*, pelanggan diarahkan ke katalog material bangunan dengan konteks rekomendasi aktif.

### Langkah 2: Skenario Checkout Multi-Toko & Pelacakan Kurir (UAT-03, UAT-04, UAT-07)
1. Di katalog marketplace, tambahkan 1 produk dari **TB Jaya Abadi** dan 1 produk dari **TB Sumber Rejeki** ke keranjang.
2. Buka keranjang di `/cart`: perhatikan butir dikelompokkan visual per toko dengan ongkos kirim masing-masing (Rp15.000).
3. Lanjutkan ke `/checkout`: periksa ringkasan pemecahan menjadi 2 pesanan terpisah.
4. Konfirmasi pembayaran (mode simulasi QRIS).
5. Pada halaman riwayat pesanan material, klik **Lacak Kurir**:
   - Amati stepper 4 tahap pengiriman dan riwayat checkpoint.
   - Uji tombol **Simulator Kurir Instan (Sandbox)** untuk memajukan status secara interaktif: *dijemput* &rarr; *dalam_perjalanan* &rarr; *terkirim*.

### Langkah 3: Skenario Audit Laporan Komisi Terpisah di Admin (AB-005 & K-5)
1. Login sebagai **Admin** (`admin@fixhome.test`) di `/admin`.
2. Di navigasi samping grup *Laporan & Keuangan*, buka halaman **Laporan Komisi Terpisah** (`/admin/commission-report`).
3. Verifikasi kepatuhan:
   - Terdapat **4 Kartu Ringkasan KPI Terpisah**: Komisi Jasa 5%, Hak Teknisi 95%, Komisi Produk 2%, dan Hak Mitra Toko 98%.
   - Tabel Komisi Jasa dan Tabel Komisi Marketplace disajikan pada dua kolom/blok terpisah tanpa pernah dijumlahkan.
   - Tersedia tombol ekspor CSV terpisah: **"Unduh CSV Komisi Jasa (5%)"** dan **"Unduh CSV Komisi Marketplace (2%)"**.
