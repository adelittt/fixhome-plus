<?php

namespace Tests\Unit;

use App\Enums\MarketplaceChoice;
use App\Enums\UserRole;
use App\Models\MarketplaceCommission;
use App\Models\ProductOrder;
use App\Models\ServiceCategory;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;
use App\Models\Store;
use App\Models\User;
use App\Services\CommissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommissionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CommissionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CommissionService::class);
    }

    /**
     * Kasus Uji K-1: Produk Rp500.000, satu toko, ongkir Rp15.000.
     * Hasil yang diharapkan: Komisi Rp10.000 (2%), bagian toko Rp490.000,
     * dan ongkos kirim Rp15.000 tidak dihitung ke dasar komisi (AB-004).
     */
    public function test_k1_marketplace_commission_single_store_excluding_shipping_fee(): void
    {
        $productAmount = 500000;
        $shippingFee = 15000;

        $result = $this->service->calculateMarketplaceCommission($productAmount, $shippingFee);

        $this->assertIsInt($result['platform_commission'], 'Komisi platform harus bertipe integer rupiah');
        $this->assertIsInt($result['store_earning'], 'Bagian toko harus bertipe integer rupiah');
        $this->assertEquals(10000, $result['platform_commission']);
        $this->assertEquals(490000, $result['store_earning']);
        $this->assertEquals(15000, $result['shipping_fee']);
    }

    /**
     * Kasus Uji K-2: Pembelian dari 2 toko berbeda (Toko A Rp300.000 dan Toko B Rp200.000).
     * Hasil yang diharapkan: Komisi Toko A Rp6.000, komisi Toko B Rp4.000, total komisi Rp10.000.
     */
    public function test_k2_marketplace_commission_multi_store_split(): void
    {
        $amountTokoA = 300000;
        $amountTokoB = 200000;

        $calcA = $this->service->calculateMarketplaceCommission($amountTokoA);
        $calcB = $this->service->calculateMarketplaceCommission($amountTokoB);

        $this->assertEquals(6000, $calcA['platform_commission']);
        $this->assertEquals(294000, $calcA['store_earning']);

        $this->assertEquals(4000, $calcB['platform_commission']);
        $this->assertEquals(196000, $calcB['store_earning']);

        $totalCommission = $calcA['platform_commission'] + $calcB['platform_commission'];
        $this->assertEquals(10000, $totalCommission);
    }

    /**
     * Kasus Uji K-3: Dasar jasa Rp200.000.
     * Hasil yang diharapkan: Komisi jasa 5% = Rp10.000, bagian teknisi 95% = Rp190.000.
     */
    public function test_k3_service_commission_calculation(): void
    {
        $baseServiceFee = 200000;

        $result = $this->service->calculateServiceCommission($baseServiceFee);

        $this->assertIsInt($result['platform_commission'], 'Komisi jasa harus bertipe integer rupiah');
        $this->assertIsInt($result['technician_earning'], 'Bagian teknisi harus bertipe integer rupiah');
        $this->assertEquals(10000, $result['platform_commission']);
        $this->assertEquals(190000, $result['technician_earning']);
    }

    /**
     * Kasus Uji K-4: Pelanggan memilih Lewati Marketplace.
     * Hasil yang diharapkan: Tidak ada catatan komisi marketplace yang dibuat.
     */
    public function test_k4_customer_chooses_skip_marketplace_records_no_commission(): void
    {
        $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $technician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $storeOwner = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $store = Store::create([
            'user_id' => $storeOwner->id,
            'name' => 'Toko Uji',
            'slug' => 'toko-uji-k4',
        ]);

        $serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-TEST-K4',
            'customer_id' => $customer->id,
            'technician_id' => $technician->id,
            'job_description' => 'Perbaikan saluran pipa',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Uji K4',
            'latitude' => -6.17,
            'longitude' => 106.63,
            'distance_km' => 3.5,
            'base_service_fee' => 150000,
            'distance_fee' => 0,
            'total_service_fee' => 150000,
            'marketplace_choice' => MarketplaceChoice::LEWATI,
        ]);

        $productOrder = new ProductOrder([
            'order_number' => 'ORD-TEST-K4',
            'customer_id' => $customer->id,
            'store_id' => $store->id,
            'total_product_amount' => 250000,
            'shipping_fee' => 10000,
            'grand_total' => 260000,
        ]);
        $productOrder->serviceOrder()->associate($serviceOrder);
        $productOrder->save();

        $commission = $this->service->recordMarketplaceCommission($productOrder);

        $this->assertNull($commission, 'Komisi marketplace tidak boleh dicatat saat pelanggan memilih Lewati Marketplace');
        $this->assertDatabaseCount('marketplace_commissions', 0);
    }

    /**
     * Kasus Uji K-5: Satu alur pemesanan memiliki pesanan jasa dan pesanan produk.
     * Hasil yang diharapkan: Dua catatan komisi terpisah dibuat di tabel masing-masing, tidak pernah digabung.
     */
    public function test_k5_service_and_marketplace_commissions_stored_in_separate_tables(): void
    {
        $customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $technician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $storeOwner = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $store = Store::create([
            'user_id' => $storeOwner->id,
            'name' => 'Toko Uji K5',
            'slug' => 'toko-uji-k5',
        ]);

        // 1. Pesanan Jasa
        $serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-TEST-K5',
            'customer_id' => $customer->id,
            'technician_id' => $technician->id,
            'job_description' => 'Perbaikan listrik',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. Uji K5',
            'latitude' => -6.17,
            'longitude' => 106.63,
            'distance_km' => 4.0,
            'base_service_fee' => 200000,
            'distance_fee' => 0,
            'total_service_fee' => 200000,
            'marketplace_choice' => MarketplaceChoice::LIHAT,
        ]);

        // 2. Pesanan Produk
        $productOrder = ProductOrder::create([
            'order_number' => 'ORD-TEST-K5',
            'service_order_id' => $serviceOrder->id,
            'customer_id' => $customer->id,
            'store_id' => $store->id,
            'total_product_amount' => 500000,
            'shipping_fee' => 15000,
            'grand_total' => 515000,
        ]);

        // Catat kedua komisi
        $serviceCommission = $this->service->recordServiceCommission($serviceOrder);
        $marketplaceCommission = $this->service->recordMarketplaceCommission($productOrder);

        // Verifikasi terpisah di tabel masing-masing
        $this->assertInstanceOf(ServiceCommission::class, $serviceCommission);
        $this->assertInstanceOf(MarketplaceCommission::class, $marketplaceCommission);

        $this->assertDatabaseHas('service_commissions', [
            'id' => $serviceCommission->id,
            'service_order_id' => $serviceOrder->id,
            'gross_amount' => 200000,
            'platform_commission' => 10000,
            'technician_earning' => 190000,
        ]);

        $this->assertDatabaseHas('marketplace_commissions', [
            'id' => $marketplaceCommission->id,
            'product_order_id' => $productOrder->id,
            'gross_product_amount' => 500000,
            'platform_commission' => 10000,
            'store_earning' => 490000,
        ]);

        // Pastikan tabel independen
        $this->assertDatabaseCount('service_commissions', 1);
        $this->assertDatabaseCount('marketplace_commissions', 1);
    }

    /**
     * Pengujian Pembulatan ke Rupiah Terdekat (Strict Integer Rupiah).
     * Misal: Nilai produk Rp12.345 x 2% = 246.9 => dibulatkan ke Rp247.
     * Bagian toko = Rp12.345 - Rp247 = Rp12.098.
     */
    public function test_commission_rounding_to_nearest_integer_rupiah(): void
    {
        $oddProductAmount = 12345;

        $result = $this->service->calculateMarketplaceCommission($oddProductAmount);

        $this->assertIsInt($result['platform_commission']);
        $this->assertIsInt($result['store_earning']);
        $this->assertEquals(247, $result['platform_commission']);
        $this->assertEquals(12098, $result['store_earning']);
        $this->assertEquals($oddProductAmount, $result['platform_commission'] + $result['store_earning']);
    }
}
