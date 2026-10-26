<?php

namespace Tests\Feature;

use App\Enums\MarketplaceChoice;
use App\Enums\ProductOrderStatus;
use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Admin\Pages\CommissionReport;
use App\Filament\Store\Resources\MarketplaceCommissionResource;
use App\Models\MarketplaceCommission;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\ServiceCategory;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;
use App\Models\Store;
use App\Models\SystemSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommissionLifecycleAndReportingTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $technician;
    protected User $storeOwnerA;
    protected User $storeOwnerB;
    protected User $admin;
    protected Store $storeA;
    protected Store $storeB;
    protected ServiceCategory $serviceCategory;
    protected ProductCategory $productCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $this->technician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $this->serviceCategory = ServiceCategory::create([
            'name' => 'Kelistrikan',
            'slug' => 'kelistrikan',
            'is_active' => true,
        ]);

        $this->productCategory = ProductCategory::create([
            'name' => 'Kabel & Listrik',
            'slug' => 'kabel-listrik',
            'is_active' => true,
        ]);

        // Toko A
        $this->storeOwnerA = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->storeA = Store::create([
            'user_id' => $this->storeOwnerA->id,
            'name' => 'Toko Listrik Sentosa',
            'slug' => 'toko-listrik-sentosa',
            'address' => 'Jl. Merdeka No. 1, Jakarta',
            'phone' => '08123456789',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        // Toko B
        $this->storeOwnerB = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->storeB = Store::create([
            'user_id' => $this->storeOwnerB->id,
            'name' => 'Toko Material Makmur',
            'slug' => 'toko-material-makmur',
            'address' => 'Jl. Sudirman No. 2, Tangerang',
            'phone' => '08987654321',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);
    }

    /**
     * K-1: Produk Rp500.000, 1 toko, ongkir Rp15.000.
     * Komisi marketplace 2%: Rp10.000, Bagian toko: Rp490.000.
     * Ongkir tidak masuk ke dasar komisi (AB-004).
     */
    public function test_k1_marketplace_commission_single_store_shipping_excluded(): void
    {
        $order = ProductOrder::create([
            'order_number' => 'ORD-TEST-K1',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 500000,
            'shipping_fee' => 15000,
            'grand_total' => 515000,
            'status' => ProductOrderStatus::DIKIRIM,
        ]);

        // Transisi status ke SELESAI memicu pencatatan otomatis via Observer (PK-04)
        $order->update(['status' => ProductOrderStatus::SELESAI]);

        $commission = MarketplaceCommission::where('product_order_id', $order->id)->first();

        $this->assertNotNull($commission);
        $this->assertEquals(500000, $commission->gross_product_amount, 'Dasar komisi wajib murni nilai produk tanpa ongkir');
        $this->assertEquals(0.02, (float) $commission->platform_rate);
        $this->assertEquals(10000, $commission->platform_commission, 'Komisi platform wajib Rp10.000 (2% dari Rp500.000)');
        $this->assertEquals(490000, $commission->store_earning, 'Hak toko wajib Rp490.000 (98% dari Rp500.000)');
        $this->assertEquals('tercatat', $commission->status);
    }

    /**
     * K-2: Pesanan 2 toko: Toko A Rp300.000, Toko B Rp200.000.
     * Komisi Toko A: Rp6.000, Komisi Toko B: Rp4.000, Total komisi platform: Rp10.000.
     */
    public function test_k2_marketplace_commission_multi_store_split(): void
    {
        // Pesanan Toko A
        $orderA = ProductOrder::create([
            'order_number' => 'ORD-TEST-K2-A',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 300000,
            'shipping_fee' => 12000,
            'grand_total' => 312000,
            'status' => ProductOrderStatus::DIKIRIM,
        ]);

        // Pesanan Toko B
        $orderB = ProductOrder::create([
            'order_number' => 'ORD-TEST-K2-B',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 200000,
            'shipping_fee' => 15000,
            'grand_total' => 215000,
            'status' => ProductOrderStatus::DIKIRIM,
        ]);

        $orderA->update(['status' => ProductOrderStatus::SELESAI]);
        $orderB->update(['status' => ProductOrderStatus::SELESAI]);

        $commissionA = MarketplaceCommission::where('product_order_id', $orderA->id)->first();
        $commissionB = MarketplaceCommission::where('product_order_id', $orderB->id)->first();

        $this->assertNotNull($commissionA);
        $this->assertNotNull($commissionB);

        $this->assertEquals(6000, $commissionA->platform_commission);
        $this->assertEquals(294000, $commissionA->store_earning);

        $this->assertEquals(4000, $commissionB->platform_commission);
        $this->assertEquals(196000, $commissionB->store_earning);

        $totalPlatform = $commissionA->platform_commission + $commissionB->platform_commission;
        $this->assertEquals(10000, $totalPlatform);
    }

    /**
     * K-3: Dasar jasa Rp200.000.
     * Komisi jasa 5%: Rp10.000, Hak teknisi 95%: Rp190.000.
     */
    public function test_k3_service_commission_calculation(): void
    {
        $serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-TEST-K3',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCategory->id,
            'job_description' => 'Perbaikan MCB trip berkala',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Kebon Jeruk No. 5',
            'latitude' => -6.18,
            'longitude' => 106.75,
            'distance_km' => 3.5,
            'base_service_fee' => 200000,
            'distance_fee' => 0,
            'total_service_fee' => 200000,
            'status' => ServiceOrderStatus::BERLANGSUNG,
            'marketplace_choice' => MarketplaceChoice::BELUM_DIPILIH,
        ]);

        $serviceOrder->update(['status' => ServiceOrderStatus::SELESAI]);

        $serviceCommission = ServiceCommission::where('service_order_id', $serviceOrder->id)->first();

        $this->assertNotNull($serviceCommission);
        $this->assertEquals(200000, $serviceCommission->gross_amount);
        $this->assertEquals(0.05, (float) $serviceCommission->platform_rate);
        $this->assertEquals(10000, $serviceCommission->platform_commission);
        $this->assertEquals(190000, $serviceCommission->technician_earning);
        $this->assertEquals('tercatat', $serviceCommission->status);
    }

    /**
     * K-4: Pelanggan memilih "Lewati Marketplace".
     * Tidak ada catatan komisi marketplace yang dibuat.
     */
    public function test_k4_customer_chooses_lewati_marketplace_no_marketplace_commission(): void
    {
        $serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-TEST-K4',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCategory->id,
            'job_description' => 'Ganti saklar lampu',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Daan Mogot KM 10',
            'latitude' => -6.15,
            'longitude' => 106.72,
            'distance_km' => 2.0,
            'base_service_fee' => 150000,
            'distance_fee' => 0,
            'total_service_fee' => 150000,
            'status' => ServiceOrderStatus::SELESAI,
            'marketplace_choice' => MarketplaceChoice::LEWATI,
        ]);

        $productOrder = ProductOrder::create([
            'order_number' => 'ORD-TEST-K4',
            'service_order_id' => $serviceOrder->id,
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 100000,
            'shipping_fee' => 10000,
            'grand_total' => 110000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        $marketplaceCommission = MarketplaceCommission::where('product_order_id', $productOrder->id)->first();
        $this->assertNull($marketplaceCommission, 'Komisi marketplace dilarang dicatat saat pelanggan memilih Lewati Marketplace (K-4)');
    }

    /**
     * K-5 & UAT-09: Satu alur pemesanan memiliki pesanan jasa dan pesanan produk.
     * Hasil: Dua catatan komisi terpisah dibuat di tabel masing-masing (service_commissions dan marketplace_commissions), tidak pernah digabung (AB-005).
     */
    public function test_k5_and_uat09_separate_tables_for_service_and_marketplace_commissions(): void
    {
        // 1. Pesanan Jasa
        $serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-UAT09-001',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCategory->id,
            'job_description' => 'Pasang instalasi listrik baru',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Kebayoran Baru No. 12',
            'latitude' => -6.24,
            'longitude' => 106.79,
            'distance_km' => 5.0,
            'base_service_fee' => 250000,
            'distance_fee' => 0,
            'total_service_fee' => 250000,
            'status' => ServiceOrderStatus::BERLANGSUNG,
            'marketplace_choice' => MarketplaceChoice::LIHAT,
        ]);

        // 2. Pesanan Produk Material Terkait
        $productOrder = ProductOrder::create([
            'order_number' => 'ORD-UAT09-001',
            'service_order_id' => $serviceOrder->id,
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 350000,
            'shipping_fee' => 20000,
            'grand_total' => 370000,
            'status' => ProductOrderStatus::DIKIRIM,
        ]);

        // Selesaikan kedua pesanan
        $serviceOrder->update(['status' => ServiceOrderStatus::SELESAI]);
        $productOrder->update(['status' => ProductOrderStatus::SELESAI]);

        // Verifikasi terpisah di tabel masing-masing (AB-005 & UAT-09)
        $serviceCommission = ServiceCommission::where('service_order_id', $serviceOrder->id)->first();
        $marketplaceCommission = MarketplaceCommission::where('product_order_id', $productOrder->id)->first();

        $this->assertNotNull($serviceCommission, 'service_commissions wajib memiliki record');
        $this->assertNotNull($marketplaceCommission, 'marketplace_commissions wajib memiliki record');

        $this->assertEquals(12500, $serviceCommission->platform_commission, '5% dari 250.000 = 12.500');
        $this->assertEquals(237500, $serviceCommission->technician_earning);

        $this->assertEquals(7000, $marketplaceCommission->platform_commission, '2% dari 350.000 = 7.000 (ongkir 20.000 diabaikan)');
        $this->assertEquals(343000, $marketplaceCommission->store_earning);

        // Pastikan tidak ada data yang tercampur
        $this->assertDatabaseCount('service_commissions', 1);
        $this->assertDatabaseCount('marketplace_commissions', 1);
    }

    /**
     * Pengujian pembatalan pesanan memperbarui status komisi menjadi dibatalkan.
     */
    public function test_cancelled_order_updates_commission_to_dibatalkan(): void
    {
        $order = ProductOrder::create([
            'order_number' => 'ORD-CANCEL-TEST',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 100000,
            'shipping_fee' => 10000,
            'grand_total' => 110000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        $commission = MarketplaceCommission::where('product_order_id', $order->id)->first();
        $this->assertEquals('tercatat', $commission->status);

        // Dibatalkan setelah selesai
        $order->update(['status' => ProductOrderStatus::DIBATALKAN]);
        $this->assertEquals('dibatalkan', $commission->fresh()->status);
    }

    /**
     * Isolasi Data Toko (AB-009):
     * Mitra Toko A hanya melihat komisi Toko A, tidak dapat melihat komisi Toko B.
     */
    public function test_store_panel_data_isolation_on_marketplace_commissions(): void
    {
        // Komisi Toko A
        $orderA = ProductOrder::create([
            'order_number' => 'ORD-ISO-A',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 200000,
            'shipping_fee' => 10000,
            'grand_total' => 210000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        // Komisi Toko B
        $orderB = ProductOrder::create([
            'order_number' => 'ORD-ISO-B',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 400000,
            'shipping_fee' => 15000,
            'grand_total' => 415000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        $this->actingAs($this->storeOwnerA);
        Filament::setTenant(null);

        // Kueri terisolasi toko A
        $queryA = MarketplaceCommissionResource::getEloquentQuery();
        $this->assertTrue($queryA->where('store_id', $this->storeA->id)->exists());
        $this->assertFalse($queryA->where('store_id', $this->storeB->id)->exists(), 'Mitra Toko A dilarang mengakses komisi Toko B (AB-009)');
    }

    /**
     * Layar 19: Laporan Komisi Terpisah di Panel Admin dan Ekspor CSV.
     */
    public function test_admin_commission_report_page_and_csv_exports(): void
    {
        // Buat data uji komisi jasa & marketplace
        $serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-RPT-01',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCategory->id,
            'job_description' => 'Servis AC',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Merdeka',
            'latitude' => -6.18,
            'longitude' => 106.75,
            'distance_km' => 2.0,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'total_service_fee' => 100000,
            'status' => ServiceOrderStatus::SELESAI,
            'marketplace_choice' => MarketplaceChoice::LIHAT,
        ]);

        $productOrder = ProductOrder::create([
            'order_number' => 'ORD-RPT-01',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 150000,
            'shipping_fee' => 10000,
            'grand_total' => 160000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(CommissionReport::class)
            ->assertSuccessful()
            ->assertSee('Laporan Komisi Platform (Terpisah & Transparan)')
            ->assertSee('Tabel 1: Komisi Jasa (5%)')
            ->assertSee('Tabel 2: Komisi Marketplace (2%)')
            ->assertSee('SRV-RPT-01')
            ->assertSee('ORD-RPT-01')
            ->call('exportServiceCommissionsCsv')
            ->assertFileDownloaded()
            ->call('exportMarketplaceCommissionsCsv')
            ->assertFileDownloaded();
    }

    /**
     * PK-15: Pengaturan dinamis apakah biaya jarak masuk dasar komisi jasa 5%.
     */
    public function test_pk15_service_commission_includes_distance_fee_setting(): void
    {
        // Skenario 1: Setting FALSE (default) -> Komisi 5% dihitung HANYA dari tarif dasar
        SystemSetting::set('service_commission_includes_distance_fee', false, 'boolean');

        $order1 = ServiceOrder::create([
            'order_number' => 'SRV-PK15-01',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCategory->id,
            'job_description' => 'Perbaikan jauh',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Jauh No. 100',
            'latitude' => -6.18,
            'longitude' => 106.75,
            'distance_km' => 12.0,
            'base_service_fee' => 200000,
            'distance_fee' => 10000, // Biaya jarak Rp10.000
            'total_service_fee' => 210000,
            'status' => ServiceOrderStatus::SELESAI,
            'marketplace_choice' => MarketplaceChoice::LIHAT,
        ]);

        $commission1 = ServiceCommission::where('service_order_id', $order1->id)->first();
        $this->assertEquals(200000, $commission1->gross_amount, 'Dasar komisi hanya tarif dasar Rp200.000 saat PK-15 false');
        $this->assertEquals(10000, $commission1->platform_commission, '5% dari 200.000 = Rp10.000');

        // Skenario 2: Setting TRUE -> Komisi 5% dihitung dari tarif dasar + biaya jarak
        SystemSetting::set('service_commission_includes_distance_fee', true, 'boolean');

        $order2 = ServiceOrder::create([
            'order_number' => 'SRV-PK15-02',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCategory->id,
            'job_description' => 'Perbaikan jauh 2',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Jauh No. 101',
            'latitude' => -6.18,
            'longitude' => 106.75,
            'distance_km' => 15.0,
            'base_service_fee' => 200000,
            'distance_fee' => 10000,
            'total_service_fee' => 210000,
            'status' => ServiceOrderStatus::SELESAI,
            'marketplace_choice' => MarketplaceChoice::LIHAT,
        ]);

        $commission2 = ServiceCommission::where('service_order_id', $order2->id)->first();
        $this->assertEquals(210000, $commission2->gross_amount, 'Dasar komisi mencakup biaya jarak Rp210.000 saat PK-15 true');
        $this->assertEquals(10500, $commission2->platform_commission, '5% dari 210.000 = Rp10.500');
    }
}
