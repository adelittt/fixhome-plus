# Spesifikasi Desain UI/UX & Design System FixHome Plus

**Platform Digital Terintegrasi Pemesanan Jasa Perbaikan Rumah & Marketplace Material Bangunan Multi-Toko**  
Mata Kuliah: Kapita Selekta Sistem Informasi | Bulan: Oktober 2026 | Perancang: Senior UI/UX Designer

---

## 1. Fondasi Visual & Design Tokens

### 1.1 Palet Warna: Hijau + Krem (WCAG AA Compliant)
Bahasa visual mempertahankan identitas awal FixHome (header putih, logo lingkaran hijau, kartu putih membulat, footer gelap 4 kolom), diperbarui ke palet harmonis **Hijau + Krem**:

| Peran Warna | Kode HEX | Nilai RGB | Penggunaan Utama | Rasio Kontras WCAG |
|---|---|---|---|---|
| **Hijau Tua (Primer)** | `#1F4D2B` | `rgb(31, 77, 43)` | Tombol utama, judul utama, link aktif, border tombol setara | 9.8:1 pada `#FAF6EA` (AAA) |
| **Hijau Medium** | `#2E7D45` | `rgb(46, 125, 69)` | State hover, ikon aksen, badge aktif, tautan sekunder | 5.6:1 pada `#FAF6EA` (AA) |
| **Hijau Muda** | `#DCEBD9` | `rgb(220, 235, 217)` | Latar chip berhasil, latar ikon lingkaran, seleksi aktif | Kontras pendukung |
| **Krem (Latar Halaman)** | `#FAF6EA` | `rgb(250, 246, 234)` | Latar belakang seluruh halaman kanvas | Warna latar utama |
| **Krem Tua (Panel)** | `#F0E8D2` | `rgb(240, 232, 210)` | Latar seksi, kartu sekunder, border lembut pemisah | Pembatas struktur |
| **Putih (Kartu)** | `#FFFFFF` | `rgb(255, 255, 255)` | Kartu konten utama, modal dialog, formulir input | Kartu melayang (*elevated*) |
| **Teks Utama** | `#1B2B20` | `rgb(27, 43, 32)` | Tipografi judul dan teks isi primer | 12.1:1 pada `#FAF6EA` (AAA) |
| **Teks Sekunder** | `#5E6B60` | `rgb(94, 107, 96)` | Metadata, label formulir, keterangan status | 4.8:1 pada `#FAF6EA` (AA) |
| **Aksen Peringatan / Bintang** | `#E0A526` | `rgb(224, 165, 38)` | Rating ulasan bintang, badge mode simulasi, perhatian | Kontras visual aksen |
| **Aksen Error / Bahaya** | `#B3402F` | `rgb(179, 64, 47)` | Validasi error input, status pesanan ditolak/batal | 5.2:1 pada `#FAF6EA` (AA) |
| **Footer Gelap** | `#1F2A22` | `rgb(31, 42, 34)` | Latar footer 4 kolom dengan tipografi krem `#FAF6EA` | 11.4:1 (AAA) |

### 1.2 Skala Tipografi (Plus Jakarta Sans / Inter)
- **Display / H1**: 28–32 px (Font-weight: 800 ExtraBold, Line-height: 1.2, Letter-spacing: -0.02em)
- **Heading H2**: 22–24 px (Font-weight: 800 Bold, Line-height: 1.3, Letter-spacing: -0.01em)
- **Subheading H3**: 16–18 px (Font-weight: 700 Bold, Line-height: 1.4)
- **Body Regular**: 14 px (Font-weight: 400 Regular / 500 Medium, Line-height: 1.6)
- **Body Small**: 12–13 px (Font-weight: 500 Medium, Line-height: 1.5)
- **Caption & Label**: 11–12 px (Font-weight: 700 Bold, Uppercase Letter-spacing: 0.05em)
- **Nomor Finansial & Resi**: JetBrains Mono / Inter (Font-weight: 800, Tabular Numbers)

### 1.3 Sistem Grid & Spasi (8-Point Grid)
- **Grid Layout**: 12 Kolom (Desktop max-width 1200 px, gutter 24 px).
- **Mobile Layout**: 1 Kolom Fluid (Viewport 390 px, margin 16 px).
- **Spasi Kelipatan 8**: 8 px (`xs`), 16 px (`sm`), 24 px (`md`), 32 px (`lg`), 40 px (`xl`), 48 px (`2xl`).

### 1.4 Radius & Elevasi Bayangan
- **Radius Kartu**: 16 px (`--radius-card`)
- **Radius Tombol**: 10 px (`--radius-btn`)
- **Radius Chip / Badge**: 9999 px (Pill Shape)
- **Bayangan Halus**: `0 4px 16px rgba(31, 45, 34, 0.05), 0 1px 3px rgba(31, 45, 34, 0.04)`

---

## 2. Library Komponen Utama

1. **Header Konsisten**:
   - Logo lingkaran hijau tua huruf "F" putih + teks *"FixHomePlus"* (hijau tua & hijau medium).
   - Navigasi link sederhana: Cari Jasa, Marketplace Material, Pesanan Saya, Panel Toko, Admin.
   - User pill dengan inisial avatar di kanan.
2. **Kartu Teknisi**:
   - Foto avatar lingkaran, nama lengkap, spesialisasi keahlian, bintang rating `#E0A526`, jarak rute peta (km), tarif dasar (Rp), tombol *"Pilih & Pesan"*.
3. **Kartu Produk Material**:
   - Gambar material (mockup), kategori material (chip hijau muda), nama produk, nama toko mitra terverifikasi, harga satuan (Rupiah integer terformat), stok tersedia, tombol *"Detail"* dan *"+ Keranjang"*.
4. **Chip Status Konsisten**:
   - Menunggu: Krem Tua (`#F0E8D2`, teks `#6E5E35`)
   - Berjalan / Diterima: Hijau Muda (`#DCEBD9`, teks `#1F4D2B`)
   - Selesai / Terkirim: Hijau Tua (`#1F4D2B`, teks `#FAF6EA`)
   - Dibatalkan / Gagal: Merah Muda (`#FCE8E6`, teks `#B3402F`)
5. **Stepper Pengiriman**:
   - 5 Tahap: *1. Dibayar* &rarr; *2. Diproses Toko* &rarr; *3. Dijemput Kurir* &rarr; *4. Dalam Perjalanan* &rarr; *5. Diterima Pelanggan*.
6. **Formulir Input**:
   - Input field berlatar krem muda (`#FAF6EA`), border krem tua (`#F0E8D2`), fokus border hijau primer (`#1F4D2B`).
7. **Pilihan Setara "Lihat Marketplace" vs "Lewati Marketplace" (PRD 4.2 & AB-006)**:
   - DUA tombol persegi panjang berukuran sama besar (padding 16 px), sudut membulat 10 px, border 2 px `#1F4D2B`.
   - Tombol 1: Latar hijau tua `#1F4D2B`, teks krem `#FAF6EA` (*Lihat Marketplace Material*).
   - Tombol 2: Latar krem tua `#F0E8D2`, teks hijau tua `#1F4D2B` (*Lewati Marketplace*).
   - Dilengkapi teks keterangan: *"Pembelian material bersifat opsional. Menyediakan bahan sendiri tidak membatalkan atau menunda pesanan jasa."*

---

## 3. Daftar Lengkap 20 Layar yang Dirancang

### A. Layar Pelanggan (12 Layar)
1. **Layar 1: Beranda & Pencarian Jasa** (Pembaruan `dashboard-home`)
   - Banner sapaan *"Halo, Ahmad!"*, kotak pencarian terpusat, 6 ikon kategori perbaikan dalam lingkaran (Listrik, Pipa, AC, Atap, Cat, Pintu), dan grid teknisi terdekat.
2. **Layar 2: Daftar & Detail Teknisi** (Pembaruan `technician-listing`)
   - Filter sidebar (kategori, slider jarak &le; 10 km bebas biaya jarak, filter rating ulasan) dan kartu daftar teknisi terdekat.
3. **Layar 3: Formulir Pemesanan Jasa**
   - Deskripsi kerusakan, tanggal & jam, alamat dengan pin peta Leaflet/OSRM (jarak 11.4 km), dan rincian biaya transparan menampilkan tarif dasar Rp85.000 + Biaya Tambahan Jarak Rp10.000 = Total Rp95.000 (AB-002).
4. **Layar 4: Pembayaran Jasa** (Pembaruan `payment-page`)
   - Rincian tagihan Rp95.000, pilihan QRIS Instan / VA, dan badge *"Mode simulasi"* (PK-02, PK-11).
5. **Layar 5: Detail Pesanan Jasa (3 Tab)**
   - 3 Tab navigasi: *Ringkasan*, *Live Chat*, dan *Rekomendasi Material*. Status pesanan, kontak teknisi, jadwal kedatangan.
6. **Layar 6: Tab Rekomendasi Material (Dua Tombol Setara)**
   - Tabel butir rekomendasi teknisi (Pipa PVC AW 1/2", Kran Angsa Stainless, Lem Pipa) dengan revisi ke-1, pesan khusus teknisi, dan **Dua Tombol Pilihan Setara** ("Lihat Marketplace" vs "Lewati Marketplace").
7. **Layar 7: Marketplace Material Multi-Toko**
   - Banner kontekstual pesanan jasa dengan *chips* material rekomendasi untuk pencarian cepat, filter kategori dan toko mitra, serta grid produk material.
8. **Layar 8: Detail Produk & Halaman Toko**
   - Foto visual material, spesifikasi SNI, harga satuan Rp35.000, stok 50 batang, info profil Mitra Toko terverifikasi (TB Sentosa Jaya), stepper kuantitas, dan tombol tambah ke keranjang.
9. **Layar 9: Keranjang Belanja per Toko**
   - Pengelompokan visual per toko mitra (TB Sentosa Jaya: Rp97.000, TB Berkah Bangunan: Rp225.000) lengkap dengan ongkos kirim Rp15.000 masing-masing dan ringkasan total belanja Rp322.000.
10. **Layar 10: Checkout Multi-Toko (Pecah Order)**
    - Form alamat penerima, pilihan opsi pengiriman instan dengan estimasi 1-2 jam (target, bukan jaminan AB-013), dan ringkasan pemecahan menjadi 2 pesanan terpisah (AB-010).
11. **Layar 11: Pesanan Produk & Pelacakan Kurir Instan**
    - Stepper 5 tahap perjalanan kurir, kartu pengemudi (nama, plat motor, kontak), riwayat pos pemeriksaan waktu nyata, dan tombol konfirmasi pesanan diterima.
12. **Layar 12: Riwayat Pesanan & Formulir Ulasan**
    - Formulir pemberian bintang ulasan teknisi (`#E0A526`), input testimoni kinerja, dan rekap pekerjaan selesai.

### B. Layar Teknisi (3 Layar)
13. **Layar 13: Dasbor Teknisi (Pekerjaan Masuk)**
    - Saklar status ketersediaan kerja (*Online / Menerima Pesanan*), kartu pesanan masuk dengan rincian keluhan, jarak rute, dan bagian pendapatan teknisi (95% = Rp90.250).
14. **Layar 14: Chat & Susun Rekomendasi Material**
    - Live chat dua arah dengan pelanggan berdampingan dengan formulir penyusunan butir rekomendasi material teknis (AB-007).
15. **Layar 15: Riwayat Kerja & Pendapatan 95%**
    - Kartu metrik total jasa selesai (28 jasa), total bagian pendapatan 95% (Rp2.380.000), dan tabel rincian transaksi jasa tanpa mencampuradukkan data toko.

### C. Layar Mitra Toko (2 Layar)
16. **Layar 16: Dasbor Toko & Katalog Material**
    - Isolasi data toko (**AB-009**): hanya menampilkan produk toko sendiri (TB Sentosa Jaya), tabel stok, harga satuan, status aktif, dan tombol tambah produk.
17. **Layar 17: Pesanan Masuk Toko & Panggil Kurir**
    - Daftar pesanan masuk yang sudah dibayar, rincian barang yang wajib disiapkan, dan tombol panggil kurir instan (AB-013).

### D. Layar Admin Platform (3 Layar)
18. **Layar 18: Verifikasi Teknisi & Mitra Toko**
    - Meja kerja verifikasi dokumen mitra baru (KTP, sertifikasi BNSP, NIB Toko) sebelum ditampilkan ke publik (**AB-011**).
19. **Layar 19: Laporan Komisi Terpisah (5% & 2%)**
    - **Prinsip AB-005 & K-5**: Dua kolom/tabel terpisah yang tidak pernah digabung:
      - Kolom 1: Tabel *service_commissions* (5% dari jasa = Rp10.750).
      - Kolom 2: Tabel *marketplace_commissions* (2% murni dari nilai produk tanpa ongkir = Rp5.840).
20. **Layar 20: Pengawasan Pesanan Jasa & Produk**
    - Meja pengawasan audit operasional seluruh transaksi platform dengan filter status, kategori, dan rentang tanggal.

---

## 4. Kepatuhan Ketat terhadap 8 Aturan Desain

1. **Kesetaraan Tombol "Lihat" vs "Lewati" (PRD 4.2 & AB-006)**: Didesain berdampingan dalam kontainer khusus (`.equal-choice-grid`) dengan lebar, tinggi, dan border 2px yang sama persis. Dilarang teks yang menyudutkan pelanggan.
2. **Kelanjutan Pesanan Jasa**: Setelah tombol "Lewati" ditekan, pesanan jasa tetap berstatus aktif (`diterima` &rarr; `berlangsung` &rarr; `selesai`) tanpa hambatan apa pun.
3. **Peran Rekomendasi Teknisi**: Teknisi tidak memiliki tombol "beli" atau transaksi keuangan atas namanya (AB-007).
4. **Estimasi Pengiriman & Mode Simulasi**: Pengiriman kurir ditampilkan dengan label jelas *"Estimasi 1-2 Jam (Target, Bukan Jaminan)"* dan badge *"Mode simulasi"*.
5. **Kerahasiaan Komisi Pelanggan**: Pelanggan hanya melihat total biaya jasa dan total belanja toko; komisi platform 5% dan 2% hanya tampil pada panel teknisi, toko, dan admin.
6. **Isolasi Data Mitra Toko (AB-009)**: Panel toko hanya menyajikan kueri data milik tokonya sendiri.
7. **Konsistensi Status Chip/Stepper**: Semua status menggunakan token warna seragam (menunggu: krem tua, berjalan: hijau muda, selesai: hijau tua, batal/error: merah muda).
8. **Format Mata Uang Rupiah Integer**: Seluruh nilai transaksi menggunakan format baku Indonesia `Rp1.234.567` tanpa desimal sen.

---

## 5. Cara Mengakses Prototype Interaktif

Aplikasi showcase prototype UI/UX interaktif responsif dapat diakses langsung melalui browser di:
```
http://localhost/ui-design/
```
Fitur interaktif yang tersedia:
- **Pilihan 20 Layar** (Drop-down selector untuk berpindah antar seluruh 20 skenario layar).
- **Pengalih Viewport**: Toggle **Desktop (1440 px)** dan **Mobile (390 px)** secara real-time.
- **Pengalih State**: Menguji tampilan kondisi **Normal**, **Empty State (Kosong)**, **Skeleton Loading**, **Error**, dan **Sukses**.
