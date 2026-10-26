<?php

namespace Tests\Feature;

use App\Enums\MarketplaceChoice;
use App\Enums\ProductOrderStatus;
use App\Enums\ServiceOrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Exceptions\ShippingServiceException;
use App\Models\CartItem;
use App\Models\MarketplaceCommission;
use App\Models\MaterialRecommendation;
use App\Models\MaterialRecommendationItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\ServiceCategory;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;
use App\Models\Shipment;
use App\Models\Store;
use App\Models\TechnicianProfile;
use App\Models\User;
use App\Services\BiteshipInstantCourierService;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Uji Penerimaan Pengguna Menyeluruh (UAT-01 s.d. UAT-10)
 * Acuan: PRD FixHome Plus Bagian 13 & BRD Lampiran C
 */
class EndToEndUatTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $technician;
    protected User $tokoUserA;
    protected User $tokoUserB;
    protected Store $storeA;
    protected Store $storeB;
    protected ServiceCategory $serviceCat;
    protected ProductCategory $productCat;
    protected Product $productA;
    protected Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => UserRole::PELANGGAN,
            'name' => 'Ahmad Pelanggan',
        ]);

        $this->technician = User::factory()->create([
            'role' => UserRole::TEKNISI,
            'name' => 'Budi Teknisi',
        ]);

        $this->serviceCat = ServiceCategory::create([
            'name' => 'Perbaikan Pipa',
            'slug' => 'perbaikan-pipa',
        ]);

        TechnicianProfile::create([
            'user_id' => $this->technician->id,
            'service_category_id' => $this->serviceCat->id,
            'bio' => 'Spesialis pipa berpengalaman',
            'base_rate' => 100000,
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_available' => true,
            'address' => 'Jakarta Barat',
            'latitude' => -6.1700,
            'longitude' => 106.7500,
        ]);

        $this->tokoUserA = User::factory()->create([
            'role' => UserRole::MITRA_TOKO,
            'name' => 'Toko A Owner',
        ]);
        $this->storeA = Store::create([
            'user_id' => $this->tokoUserA->id,
            'name' => 'TB Sentosa Jaya',
            'slug' => 'tb-sentosa-jaya',
            'address' => 'Jl. Panjang No. 1, Jakarta Barat',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        $this->tokoUserB = User::factory()->create([
            'role' => UserRole::MITRA_TOKO,
            'name' => 'Toko B Owner',
        ]);
        $this->storeB = Store::create([
            'user_id' => $this->tokoUserB->id,
            'name' => 'TB Berkah Bangunan',
            'slug' => 'tb-berkah-bangunan',
            'address' => 'Jl. Merdeka No. 2, Tangerang',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        $this->productCat = ProductCategory::create([
            'name' => 'Pipa Sanitasi',
            'slug' => 'pipa-sanitasi',
        ]);

        $this->productA = Product::create([
            'store_id' => $this->storeA->id,
            'product_category_id' => $this->productCat->id,
            'name' => 'Pipa PVC AW 1/2 Inch',
            'slug' => 'pipa-pvc-aw-half-inch',
            'price' => 35000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $this->productB = Product::create([
            'store_id' => $this->storeB->id,
            'product_category_id' => $this->productCat->id,
            'name' => 'Lem Pipa PVC 40g',
            'slug' => 'lem-pipa-pvc-40g',
            'price' => 15000,
            'stock' => 30,
            'is_active' => true,
        ]);
    }

    /**
     * UAT-01: Pesan jasa, teknisi menerima, selesaikan tanpa rekomendasi ->
     * Jasa selesai, komisi 5% terbentuk, tidak ada komisi marketplace.
     */
    public function test_uat01_service_order_completes_without_material_recommendation(): void
    {
        $order = ServiceOrder::create([
            'order_number' => 'UAT-01-SRV',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCat->id,
            'job_description' => 'Ganti kran air',
            'address' => 'Jl. Anggrek No. 10',
            'latitude' => -6.1800,
            'longitude' => 106.7600,
            'distance_km' => 3.0,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'total_service_fee' => 100000,
            'status' => ServiceOrderStatus::DITERIMA,
            'scheduled_at' => now()->addDay(),
        ]);

        // Selesaikan pesanan jasa
        $order->update(['status' => ServiceOrderStatus::SELESAI]);

        // Komisi jasa 5% terbentuk otomatis
        $serviceCommission = ServiceCommission::where('service_order_id', $order->id)->first();
        $this->assertNotNull($serviceCommission);
        $this->assertEquals(5000, $serviceCommission->platform_commission);
        $this->assertEquals(95000, $serviceCommission->technician_earning);

        // Tidak ada komisi marketplace
        $this->assertEquals(0, MarketplaceCommission::count());
    }

    /**
     * UAT-02: Rekomendasi dibuat, pelanggan memilih Lewati ->
     * Jasa selesai, tidak ada komisi marketplace (AB-006 & K-4).
     */
    public function test_uat02_customer_skips_marketplace_no_marketplace_commission_created(): void
    {
        $order = ServiceOrder::create([
            'order_number' => 'UAT-02-SRV',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCat->id,
            'job_description' => 'Sambung pipa utama',
            'address' => 'Jl. Mawar No. 12',
            'latitude' => -6.1800,
            'longitude' => 106.7600,
            'distance_km' => 2.5,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'total_service_fee' => 100000,
            'status' => ServiceOrderStatus::BERLANGSUNG,
            'scheduled_at' => now()->addDay(),
        ]);

        $recommendation = MaterialRecommendation::create([
            'service_order_id' => $order->id,
            'technician_id' => $this->technician->id,
            'notes' => 'Siapkan pipa PVC',
        ]);
        MaterialRecommendationItem::create([
            'material_recommendation_id' => $recommendation->id,
            'material_name' => 'Pipa PVC',
            'quantity' => 1,
            'unit' => 'Batang',
        ]);

        // Pelanggan memilih "Lewati Marketplace"
        $order->update(['marketplace_choice' => MarketplaceChoice::LEWATI]);

        // Jasa tetap tuntas dan selesai
        $order->update(['status' => ServiceOrderStatus::SELESAI]);

        // Komisi jasa terbentuk
        $this->assertDatabaseHas('service_commissions', [
            'service_order_id' => $order->id,
            'platform_commission' => 5000,
        ]);

        // Tidak ada komisi marketplace (K-4)
        $this->assertEquals(0, MarketplaceCommission::count());
    }

    /**
     * UAT-03: Pelanggan memilih Lihat & beli dari 1 toko ->
     * Pesanan produk dibuat, pengiriman simulasi, komisi 2% tercatat (K-1).
     */
    public function test_uat03_customer_buys_from_one_store_with_instant_courier_and_2_percent_commission(): void
    {
        $productOrder = ProductOrder::create([
            'order_number' => 'UAT-03-PRD',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 500000,
            'shipping_fee' => 15000,
            'grand_total' => 515000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        $productOrder->items()->create([
            'product_id' => $this->productA->id,
            'product_name' => $this->productA->name,
            'price' => 250000,
            'quantity' => 2,
            'subtotal' => 500000,
        ]);

        // Kurir mengantar hingga selesai
        $shipment = Shipment::create([
            'product_order_id' => $productOrder->id,
            'provider_name' => 'FixHome Express (Simulasi Instan)',
            'tracking_number' => 'FIX-UAT03-TRK',
            'origin_address' => $this->storeA->address,
            'destination_address' => 'Alamat Pelanggan',
            'shipping_cost' => 15000,
            'status' => ShipmentStatus::TERKIRIM,
            'delivered_at' => now(),
        ]);

        // Tandai pesanan produk selesai
        $productOrder->update(['status' => ProductOrderStatus::SELESAI]);

        // Komisi 2% tercatat, ongkir dikecualikan (K-1)
        $commission = MarketplaceCommission::where('product_order_id', $productOrder->id)->first();
        $this->assertNotNull($commission);
        $this->assertEquals(500000, $commission->gross_product_amount);
        $this->assertEquals(10000, $commission->platform_commission); // 2% dari 500k
        $this->assertEquals(490000, $commission->store_earning); // 98%
    }

    /**
     * UAT-04: Pelanggan beli dari 2 toko -> 2 pesanan produk terpisah dibuat;
     * komisi per toko sesuai aturan K-2 (AB-010).
     */
    public function test_uat04_multi_store_checkout_splits_orders_and_commissions(): void
    {
        // Order Toko A: Rp300.000
        $orderA = ProductOrder::create([
            'order_number' => 'UAT-04-STA',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 300000,
            'shipping_fee' => 15000,
            'grand_total' => 315000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        // Order Toko B: Rp200.000
        $orderB = ProductOrder::create([
            'order_number' => 'UAT-04-STB',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 200000,
            'shipping_fee' => 15000,
            'grand_total' => 215000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        $commA = MarketplaceCommission::where('product_order_id', $orderA->id)->first();
        $commB = MarketplaceCommission::where('product_order_id', $orderB->id)->first();

        $this->assertNotNull($commA);
        $this->assertNotNull($commB);

        // Toko A: 2% dari 300k = 6k, hak toko 294k
        $this->assertEquals(6000, $commA->platform_commission);
        $this->assertEquals(294000, $commA->store_earning);

        // Toko B: 2% dari 200k = 4k, hak toko 196k
        $this->assertEquals(4000, $commB->platform_commission);
        $this->assertEquals(196000, $commB->store_earning);
    }

    /**
     * UAT-05: Mitra Toko A mencoba mengedit produk Toko B -> Akses ditolak (403/404 - AB-009).
     */
    public function test_uat05_store_a_cannot_modify_store_b_product(): void
    {
        $response = $this->actingAs($this->tokoUserA)
            ->get("/toko/products/{$this->productB->id}/edit");

        // Filament authorization policy / scoped query returns 403 or 404
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    /**
     * UAT-06: Jarak teknisi ke pelanggan >= 10 km -> Biaya tambahan Rp10.000 otomatis ditambahkan (AB-002).
     */
    public function test_uat06_distance_greater_or_equal_10km_adds_ten_thousand_rupiah(): void
    {
        $pricingService = app(PricingService::class);

        $priceResult = $pricingService->calculateServiceOrderPricing(
            baseRate: 85000,
            distanceKm: 12.5 // Jarak >= 10 km
        );

        $this->assertEquals(85000, $priceResult['base_service_fee']);
        $this->assertEquals(10000, $priceResult['distance_fee']);
        $this->assertEquals(95000, $priceResult['total_service_fee']);
    }

    /**
     * UAT-07: Adapter pengiriman gagal merespons -> Muncul pesan kesalahan yang jelas,
     * alur pesanan jasa perbaikan rumah tidak terputus.
     */
    public function test_uat07_shipping_adapter_failure_does_not_break_service_order(): void
    {
        $srvOrder = ServiceOrder::create([
            'order_number' => 'UAT-07-SRV',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCat->id,
            'job_description' => 'Perbaikan saluran',
            'address' => 'Jl. Cempaka No. 5',
            'latitude' => -6.1800,
            'longitude' => 106.7600,
            'distance_km' => 2.0,
            'base_service_fee' => 100000,
            'distance_fee' => 0,
            'total_service_fee' => 100000,
            'status' => ServiceOrderStatus::BERLANGSUNG,
            'scheduled_at' => now()->addDay(),
        ]);

        $prdOrder = ProductOrder::create([
            'order_number' => 'UAT-07-PRD',
            'service_order_id' => $srvOrder->id,
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 70000,
            'shipping_fee' => 15000,
            'grand_total' => 85000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        Http::fake([
            'api.biteship.com/v1/orders' => Http::response(['message' => 'Network error'], 500),
        ]);

        $service = new BiteshipInstantCourierService([
            'sandbox' => false,
        ]);

        try {
            $service->requestPickup($prdOrder);
            $this->fail('Harus throw ShippingServiceException.');
        } catch (ShippingServiceException $e) {
            $this->assertStringContainsString('gagal merespons', $e->getMessage());
        }

        // Pastikan pesanan jasa tetap BERLANGSUNG (tidak terpengaruh/tidak dibatalkan)
        $this->assertEquals(ServiceOrderStatus::BERLANGSUNG, $srvOrder->fresh()->status);
    }

    /**
     * UAT-08: Toko menolak pesanan produk -> Hanya pesanan toko bersangkutan yang dibatalkan;
     * pesanan toko lain tetap berjalan (AB-010).
     */
    public function test_uat08_one_store_cancels_other_store_order_remains_active(): void
    {
        $orderA = ProductOrder::create([
            'order_number' => 'UAT-08-ORD-A',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 100000,
            'shipping_fee' => 15000,
            'grand_total' => 115000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        $orderB = ProductOrder::create([
            'order_number' => 'UAT-08-ORD-B',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeB->id,
            'total_product_amount' => 150000,
            'shipping_fee' => 15000,
            'grand_total' => 165000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        // Toko A menolak pesanan
        $orderA->update(['status' => ProductOrderStatus::DITOLAK_TOKO]);

        // Pesanan Toko A ditolak
        $this->assertEquals(ProductOrderStatus::DITOLAK_TOKO, $orderA->fresh()->status);

        // Pesanan Toko B tetap DIBAYAR dan siap diproses
        $this->assertEquals(ProductOrderStatus::DIBAYAR, $orderB->fresh()->status);
    }

    /**
     * UAT-09: Pelanggan memesan jasa dan produk material -> Tercatat 2 transaksi komisi
     * terpisah (service_commissions dan marketplace_commissions - AB-005 & K-5).
     */
    public function test_uat09_separate_commission_records_created_for_service_and_marketplace(): void
    {
        $srvOrder = ServiceOrder::create([
            'order_number' => 'UAT-09-SRV',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->serviceCat->id,
            'job_description' => 'Servis pipa dan kran',
            'address' => 'Jl. Flamboyan No. 7',
            'latitude' => -6.1800,
            'longitude' => 106.7600,
            'distance_km' => 4.0,
            'base_service_fee' => 200000,
            'distance_fee' => 0,
            'total_service_fee' => 200000,
            'status' => ServiceOrderStatus::SELESAI,
            'scheduled_at' => now()->addDay(),
        ]);

        $prdOrder = ProductOrder::create([
            'order_number' => 'UAT-09-PRD',
            'service_order_id' => $srvOrder->id,
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => 100000,
            'shipping_fee' => 15000,
            'grand_total' => 115000,
            'status' => ProductOrderStatus::SELESAI,
        ]);

        $srvComm = ServiceCommission::where('service_order_id', $srvOrder->id)->first();
        $prdComm = MarketplaceCommission::where('product_order_id', $prdOrder->id)->first();

        // 1. Catatan komisi berada di tabel yang sepenuhnya berbeda
        $this->assertNotNull($srvComm);
        $this->assertNotNull($prdComm);

        // 2. Nilai terhitung presisi
        $this->assertEquals(10000, $srvComm->platform_commission); // 5% dari 200k
        $this->assertEquals(190000, $srvComm->technician_earning); // 95% dari 200k

        $this->assertEquals(2000, $prdComm->platform_commission); // 2% dari 100k
        $this->assertEquals(98000, $prdComm->store_earning); // 98% dari 100k
    }

    /**
     * UAT-10: Perubahan harga produk setelah checkout ->
     * Pesanan lama tetap mengunci harga saat checkout (ProductOrderItem price terisolasi).
     */
    public function test_uat10_product_price_change_does_not_alter_past_order_price(): void
    {
        $originalPrice = $this->productA->price; // Rp35.000

        $order = ProductOrder::create([
            'order_number' => 'UAT-10-PRD',
            'customer_id' => $this->customer->id,
            'store_id' => $this->storeA->id,
            'total_product_amount' => $originalPrice,
            'shipping_fee' => 15000,
            'grand_total' => $originalPrice + 15000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        $item = $order->items()->create([
            'product_id' => $this->productA->id,
            'product_name' => $this->productA->name,
            'price' => $originalPrice,
            'quantity' => 1,
            'subtotal' => $originalPrice,
        ]);

        // Toko menaikkan harga produk di katalog menjadi Rp50.000
        $this->productA->update(['price' => 50000]);

        // Pastikan harga pada pesanan yang sudah tercatat tetap Rp35.000
        $this->assertEquals(35000, $item->fresh()->price);
        $this->assertEquals(35000, $order->fresh()->total_product_amount);
        $this->assertEquals(50000, $this->productA->fresh()->price);
    }
}
