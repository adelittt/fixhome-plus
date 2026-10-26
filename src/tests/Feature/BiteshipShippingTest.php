<?php

namespace Tests\Feature;

use App\Enums\ProductOrderStatus;
use App\Enums\ServiceOrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Exceptions\ShippingServiceException;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\Shipment;
use App\Models\Store;
use App\Models\TechnicianProfile;
use App\Models\User;
use App\Services\BiteshipInstantCourierService;
use App\Services\Contracts\InstantCourierServiceInterface;
use App\Services\MockInstantCourierService;
use App\Services\ShippingManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BiteshipShippingTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $technician;
    protected User $tokoUser;
    protected Store $store;
    protected ProductOrder $productOrder;
    protected ServiceOrder $serviceOrder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $this->technician = User::factory()->create(['role' => UserRole::TEKNISI]);

        $serviceCategory = ServiceCategory::create([
            'name' => 'Perbaikan Pipa & Sanitasi',
            'slug' => 'perbaikan-pipa-sanitasi',
        ]);

        TechnicianProfile::create([
            'user_id' => $this->technician->id,
            'service_category_id' => $serviceCategory->id,
            'bio' => 'Teknisi berpengalaman',
            'hourly_rate' => 85000,
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_available' => true,
        ]);

        $this->serviceOrder = ServiceOrder::create([
            'order_number' => 'SRV-TEST-001',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $serviceCategory->id,
            'job_description' => 'Pipa wastafel bocor',
            'address' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
            'latitude' => -6.1753924,
            'longitude' => 106.8271528,
            'distance_km' => 4.5,
            'base_service_fee' => 75000,
            'distance_fee' => 0,
            'total_service_fee' => 75000,
            'scheduled_at' => now()->addDay(),
            'status' => ServiceOrderStatus::DITERIMA,
        ]);

        $this->tokoUser = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->store = Store::create([
            'user_id' => $this->tokoUser->id,
            'name' => 'TB Makmur Sejahtera',
            'slug' => 'tb-makmur-sejahtera',
            'address' => 'Jl. Panjang No. 45, Jakarta Barat',
            'phone' => '081234567890',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        $category = ProductCategory::create([
            'name' => 'Pipa & Fitting',
            'slug' => 'pipa-fitting',
        ]);

        $product = Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $category->id,
            'name' => 'Pipa PVC AW 1/2 inch Rucika',
            'slug' => 'pipa-pvc-aw-rucika',
            'price' => 35000,
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->productOrder = ProductOrder::create([
            'order_number' => 'ORD-BITESHIP-01',
            'service_order_id' => $this->serviceOrder->id,
            'customer_id' => $this->customer->id,
            'store_id' => $this->store->id,
            'total_product_amount' => 70000,
            'shipping_fee' => 15000,
            'grand_total' => 85000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        $this->productOrder->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 35000,
            'quantity' => 2,
            'subtotal' => 70000,
        ]);
    }

    /**
     * Uji ShippingManager dapat menyelesaikan driver mock dan biteship dengan benar.
     */
    public function test_shipping_manager_resolves_drivers(): void
    {
        $manager = app(ShippingManager::class);

        // Driver default
        $defaultDriver = $manager->driver();
        $this->assertInstanceOf(InstantCourierServiceInterface::class, $defaultDriver);

        // Driver Biteship
        $biteshipDriver = $manager->driver('biteship');
        $this->assertInstanceOf(BiteshipInstantCourierService::class, $biteshipDriver);

        // Driver Mock
        $mockDriver = $manager->driver('mock');
        $this->assertInstanceOf(MockInstantCourierService::class, $mockDriver);
    }

    /**
     * Uji Biteship sandbox adapter membuat shipment dan menerbitkan nomor resi (AB-013, PK-01).
     */
    public function test_biteship_adapter_requests_pickup_successfully(): void
    {
        Http::fake([
            'api.biteship.com/v1/orders' => Http::response([
                'success' => true,
                'message' => 'Order created successfully',
                'object' => 'order',
                'id' => 'bite_ord_12345',
                'tracking_id' => 'BITESHIP-EXP-20261026-TEST01',
                'courier' => [
                    'tracking_id' => 'BITESHIP-EXP-20261026-TEST01',
                    'waybill_id' => 'WB-GRAB-98765',
                    'company' => 'grab',
                    'name' => 'Grab Express Instant',
                ],
                'status' => 'allocated',
            ], 200),
        ]);

        $service = new BiteshipInstantCourierService([
            'api_key' => 'test_key',
            'base_url' => 'https://api.biteship.com/v1',
            'courier' => 'grab',
            'courier_service_type' => 'instant',
            'sandbox' => true,
        ]);

        $shipment = $service->requestPickup($this->productOrder);

        $this->assertNotNull($shipment);
        $this->assertEquals($this->productOrder->id, $shipment->product_order_id);
        $this->assertStringContainsString('Biteship', $shipment->provider_name);
        $this->assertEquals('BITESHIP-EXP-20261026-TEST01', $shipment->tracking_number);
        $this->assertEquals(ShipmentStatus::DIMINTA, $shipment->status);
        $this->assertEquals(ProductOrderStatus::SIAP_DIKIRIM, $this->productOrder->fresh()->status);
    }

    /**
     * Uji pelacakan status pengiriman Biteship mengembalikan 5 checkpoint dan info kurir.
     */
    public function test_biteship_adapter_tracks_shipment_checkpoints(): void
    {
        $shipment = Shipment::create([
            'product_order_id' => $this->productOrder->id,
            'provider_name' => 'Biteship (GRAB Instant Sandbox)',
            'tracking_number' => 'BITESHIP-EXP-TRK-999',
            'origin_address' => $this->store->address,
            'destination_address' => $this->serviceOrder->address,
            'shipping_cost' => 15000,
            'estimated_hours' => 1.5,
            'status' => ShipmentStatus::DIJEMPUT,
        ]);

        $service = new BiteshipInstantCourierService();
        $trackingData = $service->trackShipment($shipment->tracking_number);

        $this->assertArrayHasKey('shipment', $trackingData);
        $this->assertArrayHasKey('driver_name', $trackingData);
        $this->assertArrayHasKey('driver_plate', $trackingData);
        $this->assertArrayHasKey('checkpoints', $trackingData);
        $this->assertCount(5, $trackingData['checkpoints']);

        // Checkpoint 1, 2, 3 sudah completed
        $this->assertTrue($trackingData['checkpoints'][0]['completed']); // Gateway
        $this->assertTrue($trackingData['checkpoints'][1]['completed']); // Alokasi
        $this->assertTrue($trackingData['checkpoints'][2]['completed']); // Dijemput
        $this->assertFalse($trackingData['checkpoints'][4]['completed']); // Belum terkirim
    }

    /**
     * Uji sinkronisasi transisi status kurir Biteship dengan ProductOrder.
     */
    public function test_biteship_status_updates_sync_correctly(): void
    {
        $shipment = Shipment::create([
            'product_order_id' => $this->productOrder->id,
            'provider_name' => 'Biteship (GRAB Instant Sandbox)',
            'tracking_number' => 'BITESHIP-EXP-TRK-777',
            'origin_address' => $this->store->address,
            'destination_address' => $this->serviceOrder->address,
            'shipping_cost' => 15000,
            'status' => ShipmentStatus::DIMINTA,
        ]);

        $service = new BiteshipInstantCourierService();

        // 1. Bergerak ke dalam perjalanan
        $service->updateStatus($shipment, ShipmentStatus::DALAM_PERJALANAN);
        $this->assertEquals(ShipmentStatus::DALAM_PERJALANAN, $shipment->fresh()->status);
        $this->assertEquals(ProductOrderStatus::DIKIRIM, $this->productOrder->fresh()->status);

        // 2. Tiba dan selesai
        $service->updateStatus($shipment, ShipmentStatus::TERKIRIM);
        $freshShipment = $shipment->fresh();
        $this->assertEquals(ShipmentStatus::TERKIRIM, $freshShipment->status);
        $this->assertNotNull($freshShipment->delivered_at);
        $this->assertEquals(ProductOrderStatus::SELESAI, $this->productOrder->fresh()->status);
    }

    /**
     * Uji UAT-07: Adapter pengiriman gagal merespons -> muncul pesan kesalahan yang jelas,
     * dan alur jasa perbaikan rumah TIDAK terputus (tetap DITERIMA/BERLANGSUNG).
     */
    public function test_uat07_adapter_failure_shows_clear_error_and_does_not_break_service_flow(): void
    {
        Http::fake([
            'api.biteship.com/v1/orders' => Http::response([
                'success' => false,
                'message' => 'Koneksi gateway kurir sedang sibuk',
            ], 500),
        ]);

        $service = new BiteshipInstantCourierService([
            'api_key' => 'invalid_or_failing_key',
            'base_url' => 'https://api.biteship.com/v1',
            'sandbox' => false, // Set false untuk memicu exception UAT-07
        ]);

        try {
            $service->requestPickup($this->productOrder);
            $this->fail('Harus melempar ShippingServiceException saat adapter gagal.');
        } catch (ShippingServiceException $e) {
            // 1. Pesan kesalahan yang jelas dalam Bahasa Indonesia
            $this->assertStringContainsString('Layanan pengiriman Biteship Sandbox gagal merespons', $e->getMessage());
            $this->assertStringContainsString('Alur pesanan jasa perbaikan rumah tetap berjalan normal', $e->getMessage());
        }

        // 2. Alur pesanan jasa TIDAK terputus (AB-006 & UAT-07)
        $freshServiceOrder = $this->serviceOrder->fresh();
        $this->assertEquals(ServiceOrderStatus::DITERIMA, $freshServiceOrder->status);
        $this->assertNotEquals(ServiceOrderStatus::DIBATALKAN, $freshServiceOrder->status);
    }
}
