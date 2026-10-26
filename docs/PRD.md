# Product Requirements Document (PRD)
# FixHome Plus

**Platform Digital FixHome Plus: Integrasi Pemesanan Jasa Perbaikan Rumah dan Marketplace Material Bangunan Berbasis Kebutuhan Customer**  
Mata kuliah: Kapita Selekta Sistem Informasi  
Versi dokumen: 1.0 (draf awal) | Bulan penyusunan: Oktober 2026  
Acuan: BRD FixHome Plus v1.0  

---

## 1. Ringkasan & Prinsip Produk
1. **Marketplace Tidak Pernah Menjadi Syarat**: Tombol "Lewati Marketplace" harus setara dalam ukuran, posisi, dan kontras dengan "Lihat Marketplace", tanpa teks pemaksa.
2. **Teknisi Merekomendasikan, Bukan Membelikan**: Tidak ada auto-purchase atau pembebanan biaya ke teknisi.
3. **Satu Hal, Satu Catatan**: Status jasa, pembayaran, pesanan produk, dan pengiriman terpisah.
4. **Komisi Tidak Dicampur**: 5% jasa dan 2% marketplace disimpan di tabel terpisah.
5. **Jujur tentang Ketersediaan**: Pengiriman hari yang sama adalah estimasi, bukan jaminan.

---

## 2. Persona dan Peran
- **Pelanggan**: Cari jasa, pesan teknisi, chat, lihat rekomendasi material, pilih lihat/lewati marketplace, checkout per toko, bayar, lacak, ulas.
- **Teknisi**: Terima/tolak pesanan, chat, buat rekomendasi butir material, perbarui status kerja, lihat riwayat & komisi.
- **Mitra Toko**: Kelola profil toko, katalog produk, harga, stok, proses pesanan produk per tokonya sendiri.
- **Admin**: Verifikasi teknisi & toko, kelola kategori layanan & produk, pantau pesanan & pengiriman, audit komisi terpisah, kelola pengaturan sistem.

---

## 6.5 Perhitungan Komisi & Kasus Uji (K-1 s.d. K-5)
- **Komisi Jasa**: `komisi_jasa = dasar_jasa * 5%`; `bagian_teknisi = dasar_jasa - komisi_jasa` (pembulatan rupiah).
  - *Catatan PK-15*: Dasar jasa secara default adalah tarif jasa. Konfigurasi `service_commission_includes_distance_fee` menentukan apakah biaya jarak (Rp10.000 jika >= 10 km) ikut dihitung dalam dasar komisi jasa.
- **Komisi Marketplace**: `komisi_mp = subtotal_produk_memenuhi_syarat * 2%`; `bagian_toko = subtotal - komisi_mp` (ongkos kirim TIDAK dihitung).

### Tabel Kasus Uji Komisi:
- **K-1**: Produk Rp500.000, 1 toko, ongkir Rp15.000.  
  -> Komisi marketplace: Rp10.000, Bagian toko: Rp490.000, Ongkir tidak dihitung ke dasar komisi.
- **K-2**: Pesanan 2 toko: Toko A Rp300.000, Toko B Rp200.000.  
  -> Komisi Toko A: Rp6.000, Komisi Toko B: Rp4.000, Total komisi platform: Rp10.000.
- **K-3**: Dasar jasa Rp200.000.  
  -> Komisi jasa: Rp10.000 (5%), Bagian teknisi: Rp190.000 (95%).
- **K-4**: Pelanggan memilih "Lewati Marketplace".  
  -> Tidak ada catatan komisi marketplace yang dibuat.
- **K-5**: Satu alur pemesanan memiliki jasa dan produk marketplace.  
  -> Dua catatan komisi terpisah dibuat di tabel masing-masing (`service_commissions` dan `marketplace_commissions`), tidak pernah digabung.

---

## 7. Model Status Terpisah (Enum)

| Objek | Nilai Enum Status | Aturan Transisi |
| :--- | :--- | :--- |
| **Pesanan Jasa** (`ServiceOrderStatus`) | `menunggu_teknisi`, `diterima`, `berlangsung`, `selesai`, `ditolak`, `dibatalkan` | Hanya teknisi bersangkutan yang menerima/menolak; `selesai` hanya dari `berlangsung`. |
| **Pilihan Marketplace** (`MarketplaceChoice`) | `belum_dipilih`, `lihat`, `lewati` | Dapat berubah selama pesanan jasa belum selesai (PK-17). |
| **Pembayaran** (`PaymentStatus`) | `menunggu`, `berhasil`, `gagal`, `kedaluwarsa`, `dikembalikan_manual` | Terpisah untuk pembayaran jasa vs pembayaran produk. |
| **Pesanan Produk** (`ProductOrderStatus`) | `menunggu_pembayaran`, `dibayar`, `diproses`, `siap_dikirim`, `dikirim`, `selesai`, `ditolak_toko`, `dibatalkan` | Mitra Toko hanya dapat mengubah hingga `siap_dikirim`; tahap selanjutnya sinkron dengan pengiriman. |
| **Pengiriman** (`ShipmentStatus`) | `belum_dibuat`, `diminta`, `dijemput`, `dalam_perjalanan`, `terkirim`, `gagal`, `dibatalkan` | Diperbarui oleh adapter penyedia/simulator. |
| **Verifikasi Akun** (`AccountVerificationStatus`) | `menunggu`, `disetujui`, `ditolak`, `ditangguhkan` | Hanya dapat diubah oleh Admin. |

---

## 8. Matriks Hak Akses (Authorization Matrix)

| Sumber Daya | Pelanggan | Teknisi | Mitra Toko | Admin |
| :--- | :--- | :--- | :--- | :--- |
| **Pesanan Jasa** | Milik sendiri | Ditugaskan padanya | Tidak | Semua |
| **Chat Pesanan** | Pesanan sendiri | Pesanan ditugaskan | Tidak | Khusus penanganan sengketa |
| **Rekomendasi Material**| Lihat & tentukan pilihan | Buat & revisi miliknya | Tidak | Lihat |
| **Produk / Katalog** | Lihat katalog aktif | Lihat katalog aktif | Kelola tokonya sendiri | Semua |
| **Pesanan Produk** | Milik sendiri | Status pilihan saja (PK-13) | Pesanan tokonya saja | Semua |
| **Catatan Komisi** | Tidak ada akses | Bagian teknisi miliknya | Bagian toko miliknya | Semua (Audit & Laporan) |
| **Verifikasi Akun** | Tidak | Tidak | Tidak | Kelola & Putuskan |

---

## 10.1 Entitas Tingkat Tinggi (Database Model)
1. `User` (id, name, email, password, role [pelanggan, teknisi, mitra_toko, admin], phone, status)
2. `TechnicianProfile` (user_id, service_category_id, bio, hourly_rate/base_rate, verification_status, is_available, latitude, longitude, address)
3. `TechnicianService` (id, technician_profile_id, name, description, price)
4. `Store` (user_id, name, slug, address, latitude, longitude, phone, verification_status, is_active)
5. `Product` (store_id, product_category_id, name, slug, description, price, stock, is_active)
6. `ServiceCategory` (name, slug, icon, description, is_active)
7. `ProductCategory` (name, slug, icon, description, is_active)
8. `ServiceOrder` (order_number, customer_id, technician_id, category_id, job_description, scheduled_at, address, latitude, longitude, distance_km, distance_fee, base_service_fee, total_fee, status, marketplace_choice)
9. `ChatMessage` (service_order_id, sender_id, receiver_id, message, read_at)
10. `MaterialRecommendation` (service_order_id, notes, status)
11. `MaterialRecommendationItem` (recommendation_id, product_id [nullable], material_name, specification, quantity, unit, notes)
12. `CartItem` (customer_id, product_id, quantity)
13. `ProductOrder` (order_number, service_order_id [nullable], customer_id, store_id, total_product_amount, shipping_fee, grand_total, status)
14. `ProductOrderItem` (product_order_id, product_id, product_name, price, quantity, subtotal)
15. `Payment` (payable_type, payable_id, payment_number, amount, payment_method, status, paid_at, payload)
16. `Shipment` (product_order_id, provider_name, tracking_number, origin_address, destination_address, shipping_cost, estimated_hours, status, delivered_at)
17. `ServiceCommission` (service_order_id, technician_id, gross_amount, platform_rate [0.05], platform_commission, technician_earning, status)
18. `MarketplaceCommission` (product_order_id, store_id, gross_product_amount, platform_rate [0.02], platform_commission, store_earning, status)
19. `Review` (service_order_id, customer_id, technician_id, rating, comment)
20. `AuditLog` (user_id, action, auditable_type, auditable_id, old_values, new_values, ip_address)
21. `SystemSetting` (key, value, description) -> Menyimpan `service_commission_includes_distance_fee`, `distance_fee_threshold_km`, `distance_fee_amount`, dll.

---

## 13. Rencana Pengujian Penerimaan (UAT)
- **UAT-01**: Pesan jasa, teknisi menerima, selesaikan tanpa rekomendasi -> Jasa selesai, tidak ada komisi marketplace.
- **UAT-02**: Rekomendasi dibuat, pelanggan memilih Lewati -> Jasa selesai, tidak ada komisi marketplace.
- **UAT-03**: Pelanggan memilih Lihat & beli dari 1 toko -> Pesanan produk dibuat, pengiriman simulasi, komisi 2% tercatat.
- **UAT-04**: Pelanggan beli dari 2 toko -> 2 pesanan produk terpisah dibuat; komisi per toko sesuai K-2.
- **UAT-05**: Mitra Toko A mencoba membuka/mengedit produk Toko B -> Akses ditolak (403 Forbidden).
- **UAT-06**: Jarak teknisi ke pelanggan >= 10 km -> Biaya tambahan Rp10.000 otomatis ditambahkan.
- **UAT-07**: Adapter pengiriman gagal merespons -> Muncul pesan kesalahan yang jelas, alur jasa tidak terputus.
- **UAT-08**: Toko menolak pesanan produk -> Hanya pesanan toko bersangkutan yang dibatalkan; pesanan toko lain tetap berjalan.
- **UAT-09**: Pelanggan memesan jasa dan produk material -> Tercatat 2 transaksi komisi terpisah (`service_commissions` dan `marketplace_commissions`).
- **UAT-10**: Perubahan harga produk setelah checkout -> Pesanan lama tetap mengunci harga saat checkout.
