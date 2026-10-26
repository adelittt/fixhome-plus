# Business Requirements Document (BRD)
# FixHome Plus

**Pengembangan Platform Digital FixHome Plus melalui Integrasi Pemesanan Jasa Perbaikan Rumah dan Marketplace Material Bangunan Berbasis Kebutuhan Customer**  
Mata kuliah: Kapita Selekta Sistem Informasi  
Versi dokumen: 1.0 (draf awal) | Bulan penyusunan: Oktober 2026  

---

## Keterangan Status dalam Dokumen Ini
- **Berjalan (FixHome)**: Sudah menjadi konsep atau aturan bisnis FixHome sebelumnya dan dipertahankan.
- **Diusulkan**: Konsep baru FixHome Plus yang direncanakan dan belum diimplementasikan.
- **Perlu Konfirmasi (PK)**: Belum ada keputusan bisnis final; dicatat di Lampiran C.

---

## BAB I. PENDAHULUAN

### 1.1 Latar Belakang
FixHome merupakan platform digital pemesanan jasa perawatan dan perbaikan rumah yang menghubungkan pelanggan dengan teknisi. Komisi platform pada layanan jasa ditetapkan sebesar 5%.  
FixHome Plus dikembangkan untuk mengintegrasikan pemesanan jasa perbaikan rumah dengan marketplace material bangunan multi-store. Teknisi memberikan rekomendasi material sesuai kebutuhan pekerjaan, dan pelanggan dapat memilih untuk membeli material melalui marketplace atau melewatinya ("Lihat Marketplace" vs "Lewati Marketplace").

### 1.2 Identifikasi Masalah
- **M-01**: Pelanggan kesulitan menentukan jenis dan spesifikasi material yang tepat.
- **M-02**: Proses jasa dan pembelian material berjalan terpisah di luar platform.
- **M-03**: Toko material bangunan belum memiliki kanal digital yang terhubung langsung dengan kebutuhan perbaikan rumah.
- **M-04**: Pengiriman material tidak terkoordinasi dengan jadwal teknisi.
- **M-05**: Pencatatan transaksi jasa, produk, dan komisi belum terpisah dan transparan.
- **M-06**: Pengelolaan data banyak toko membutuhkan pembatasan hak akses ketat (data isolation).

### 1.3 Tujuan Pengembangan Sistem
1. Mempertahankan layanan pemesanan jasa perbaikan rumah FixHome sebagai layanan inti.
2. Memungkinkan teknisi memberikan rekomendasi material berdasarkan hasil diskusi dengan pelanggan.
3. Menyediakan marketplace material bangunan multi-store yang bersifat opsional bagi pelanggan.
4. Memungkinkan Mitra Toko mengelola profil, produk, harga, stok, dan pesanan tokonya sendiri.
5. Menyediakan mekanisme pengiriman kurir instan berbasis adapter API (simulasi pada tahap awal).
6. Mencatat komisi jasa (5%) dan komisi marketplace (2%) secara terpisah dan transparan.
7. Menyediakan fungsi pengawasan dan pelaporan operasional bagi Admin FixHome Plus.

### 1.4 & 1.5 Ruang Lingkup
- **Dalam Lingkup**: Pemesanan jasa, live chat, rekomendasi material teknisi, opsi Lihat/Lewati marketplace, marketplace multi-store, checkout multi-toko (pecah order per toko), pencatatan komisi terpisah, verifikasi teknisi & toko oleh admin.
- **Di Luar Lingkup**: Gudang/fulfillment milik platform, armada kurir sendiri, refund & retur otomatis, program voucher/diskon/loyalitas, aplikasi mobile native.

### 1.6 Batasan Proyek
1. Dokumen ini membahas kebutuhan bisnis.
2. Teknologi yang direncanakan: Laravel 12, Filament v3, Livewire, PostgreSQL, Docker.
3. Integrasi pengiriman & pembayaran bersifat simulasi/sandbox pada rilis awal.
4. Komisi jasa 5% dan biaya tambahan jarak (jarak rute >= 10 km = Rp10.000) tidak diubah tanpa keputusan bisnis.

---

## BAB II. GAMBARAN UMUM BISNIS & MODEL BISNIS

### 2.3 Model Bisnis & Komisi
- **Komisi Jasa Perbaikan Rumah**: 5% untuk FixHome Plus, 95% untuk teknisi. Dasar perhitungan: total transaksi jasa (apakah biaya jarak masuk dasar komisi jasa diatur via konfigurasi PK-15).
- **Biaya Tambahan Jarak**: Jarak tempuh rute 10 km atau lebih dikenakan biaya tambahan Rp10.000 (AB-002).
- **Komisi Marketplace**: 2% untuk FixHome Plus dari nilai transaksi produk yang memenuhi ketentuan komisi. Sisanya 98% untuk Mitra Toko. Biaya pengiriman dicatat terpisah dan TIDAK masuk dasar komisi.
- **Aturan Pemisahan Komisi**: Komisi jasa 5% dan komisi marketplace 2% disimpan di tabel terpisah dan tidak boleh dicampur dalam satu perhitungan maupun satu catatan (AB-005).

---

## BAB IV. ATURAN BISNIS (AB) & KEBUTUHAN

### 4.6 Aturan Bisnis Inti
- **AB-001**: Komisi jasa 5%; teknisi 95% dari total transaksi jasa dasar komisi.
- **AB-002**: Biaya tambahan jarak rute >= 10 km sebesar Rp10.000 berdasarkan rute peta (bukan garis lurus).
- **AB-003**: Komisi marketplace 2% dari nilai produk yang memenuhi syarat komisi.
- **AB-004**: Ongkos kirim dicatat terpisah dan tidak menjadi dasar komisi marketplace.
- **AB-005**: Komisi jasa 5% dan komisi marketplace 2% tidak boleh dicampur dalam satu perhitungan atau satu catatan.
- **AB-006**: Pembelian material melalui marketplace bersifat opsional dan bukan syarat menggunakan jasa perbaikan rumah ("Lewati Marketplace" tidak memblokir alur jasa).
- **AB-007**: Teknisi bertugas merekomendasikan material, tidak diwajibkan membeli dengan uang pribadi, dan tidak ada pesanan otomatis atas nama teknisi.
- **AB-008**: FixHome Plus bertindak sebagai perantara antara pelanggan dan Mitra Toko (bukan penjual material / tidak memegang stok).
- **AB-009**: Mitra Toko hanya dapat mengelola data, produk, harga, stok, dan pesanan tokonya sendiri (data isolation).
- **AB-010**: Produk dari beberapa toko dalam satu keranjang dipecah menjadi pesanan per toko saat checkout.
- **AB-011**: Hanya teknisi dan Mitra Toko yang telah diverifikasi Admin yang tampil kepada pelanggan.
- **AB-012**: Transaksi dibatalkan/retur ditangani secara manual oleh admin; tidak ada refund otomatis.
- **AB-013**: Pengiriman hari yang sama adalah target, bukan jaminan.
- **AB-014**: Status jasa, pembayaran, pesanan produk, dan pengiriman dikelola terpisah.
- **AB-015**: Ulasan hanya dapat diberikan pada pesanan jasa yang telah selesai.

---

## LAMPIRAN C. DAFTAR PERTANYAAN YANG PERLU DIKONFIRMASI (PK)
- **PK-01**: Pemilihan penyedia kurir (Biteship, GoSend, Grab, Lalamove). -> Default: Adapter terpadu, simulasi pada R1.
- **PK-02**: Metode & waktu pembayaran jasa. -> Default: Simulasi/sandbox terpisah.
- **PK-03**: Kebijakan pembatalan, retur, refund. -> Default: Pencatatan manual oleh Admin.
- **PK-04**: Syarat pesanan produk memenuhi komisi 2%. -> Default: Pesanan berstatus `selesai`.
- **PK-05 & PK-06**: Ongkos kirim multi-toko & pembayaran keranjang. -> Default: 1 kali sesi bayar, dipecah per toko, ongkir dihitung per toko.
- **PK-07**: Penyaluran dana teknisi & toko. -> Default: Rekap pencatatan saja, pencairan manual di luar sistem.
- **PK-08**: Kriteria verifikasi toko. -> Default: Verifikasi dokumen oleh Admin.
- **PK-09**: Kategori produk yang diperbolehkan. -> Default: Kategori material/alat bangunan terkurasi.
- **PK-10**: Notifikasi real-time. -> Default: In-app database/polling.
- **PK-11**: Lingkup integrasi sandbox/simulasi. -> Ya, simulasi untuk fase akademik.
- **PK-12**: Wilayah operasional awal. -> Default: Jabodetabek / Tangerang.
- **PK-13**: Visibilitas status pembelian untuk teknisi. -> Default: Teknisi hanya melihat pilihan pelanggan (Lihat atau Lewati).
- **PK-14**: Ambang metrik bisnis & kinerja.
- **PK-15**: Apakah biaya tambahan jarak masuk dasar komisi jasa 5%? -> Harus dibuat sebagai pengaturan (config/setting), bukan hardcoded.
- **PK-16**: Penanganan produk rekomendasi tidak tersedia di toko. -> Pelanggan dapat berdiskusi ulang atau memilih Lewati.
- **PK-17**: Batas waktu pelanggan mengubah pilihan Lewati ke Lihat. -> Selama pesanan jasa belum berstatus selesai.
- **PK-18**: Pajak & biaya penanganan tambahan. -> Belum ada tambahan pajak terpisah.
- **PK-19**: Akses marketplace mandiri tanpa jasa. -> Fokus utama adalah terintegrasi dengan pemesanan jasa.
