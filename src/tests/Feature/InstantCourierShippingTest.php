<?php

namespace Tests\Feature;

use App\Enums\ProductOrderStatus;
use App\Enums\ShipmentStatus;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Livewire\OrderShipmentTracker;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\Shipment;
use App\Models\Store;
use App\Models\User;
use App\Services\Contracts\InstantCourierServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InstantCourierShippingTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $otherCustomer;
    protected User $tokoUser;
    protected Store $store;
    protected ProductOrder $order;
    protected InstantCourierServiceInterface $courierService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->courierService = app(InstantCourierServiceInterface::class);

        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $this->otherCustomer = User::factory()->create(['role' => UserRole::PELANGGAN]);

        $this->tokoUser = User::factory()->create(['role' => UserRole::MITRA_TOKO]);
        $this->store = Store::create([
            'user_id' => $this->tokoUser->id,
            'name' => 'Toko Bangunan Perkasa Jaya',
            'slug' => 'toko-bangunan-perkasa-jaya',
            'address' => 'Jl. Daan Mogot KM 11, Jakarta Barat',
            'phone' => '081234567890',
            'verification_status' => VerificationStatus::DISETUJUI,
            'is_active' => true,
        ]);

        $category = ProductCategory::create([
            'name' => 'Bahan Bangunan Dasar',
            'slug' => 'bahan-bangunan-dasar',
        ]);

        $product = Product::create([
            'store_id' => $this->store->id,
            'product_category_id' => $category->id,
            'name' => 'Semen Gresik 40kg',
            'slug' => 'semen-gresik-40kg',
            'price' => 65000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $this->order = ProductOrder::create([
            'order_number' => 'ORD-TEST-EXP-01',
            'customer_id' => $this->customer->id,
            'store_id' => $this->store->id,
            'total_product_amount' => 130000,
            'shipping_fee' => 15000,
            'grand_total' => 145000,
            'status' => ProductOrderStatus::DIBAYAR,
        ]);

        $this->order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 65000,
            'quantity' => 2,
            'subtotal' => 130000,
        ]);
    }

    /**
     * Toko meminta alokasi kurir instan via adapter service -> shipment diterbitkan (AB-013).
     */
    public function test_courier_service_requests_pickup_and_issues_tracking_number(): void
    {
        $shipment = $this->courierService->requestPickup($this->order);

        $this->assertNotNull($shipment);
        $this->assertEquals($this->order->id, $shipment->product_order_id);
        $this->assertStringStartsWith('FIX-EXP-', $shipment->tracking_number);
        $this->assertEquals(ShipmentStatus::DIMINTA, $shipment->status);
        $this->assertEquals(ProductOrderStatus::SIAP_DIKIRIM, $this->order->fresh()->status);
    }

    /**
     * Pelacakan pengiriman kurir instan mengembalikan timeline checkpoints dan info pengemudi.
     */
    public function test_courier_service_tracks_checkpoints(): void
    {
        $shipment = $this->courierService->requestPickup($this->order);

        $trackingData = $this->courierService->trackShipment($shipment->tracking_number);

        $this->assertArrayHasKey('shipment', $trackingData);
        $this->assertArrayHasKey('driver_name', $trackingData);
        $this->assertArrayHasKey('driver_plate', $trackingData);
        $this->assertArrayHasKey('checkpoints', $trackingData);
        $this->assertCount(5, $trackingData['checkpoints']);

        // Checkpoint awal sudah aktif
        $this->assertTrue($trackingData['checkpoints'][0]['completed']); // Dibuat
        $this->assertTrue($trackingData['checkpoints'][1]['completed']); // Diminta
        $this->assertFalse($trackingData['checkpoints'][4]['completed']); // Belum terkirim
    }

    /**
     * Sinkronisasi status: Update status kurir otomatis menyinkronkan status ProductOrder.
     */
    public function test_courier_status_updates_sync_with_product_order_status(): void
    {
        $shipment = $this->courierService->requestPickup($this->order);

        // 1. Kurir mulai jalan -> Status pesanan jadi DIKIRIM
        $this->courierService->updateStatus($shipment, ShipmentStatus::DALAM_PERJALANAN);
        $this->assertEquals(ShipmentStatus::DALAM_PERJALANAN, $shipment->fresh()->status);
        $this->assertEquals(ProductOrderStatus::DIKIRIM, $this->order->fresh()->status);

        // 2. Kurir tiba -> Status pesanan jadi SELESAI & delivered_at terisi
        $this->courierService->updateStatus($shipment, ShipmentStatus::TERKIRIM);
        $freshShipment = $shipment->fresh();
        $this->assertEquals(ShipmentStatus::TERKIRIM, $freshShipment->status);
        $this->assertNotNull($freshShipment->delivered_at);
        $this->assertEquals(ProductOrderStatus::SELESAI, $this->order->fresh()->status);
    }

    /**
     * Pelanggan pemesan dapat mengakses antarmuka pelacakan kurir livewire.
     */
    public function test_customer_can_view_shipment_tracking_page(): void
    {
        $shipment = $this->courierService->requestPickup($this->order);

        Livewire::actingAs($this->customer)
            ->test(OrderShipmentTracker::class, ['orderId' => $this->order->id])
            ->assertSee($shipment->tracking_number)
            ->assertSee('Toko Bangunan Perkasa Jaya')
            ->assertSee('Semen Gresik 40kg')
            ->assertSee('Simulator Kurir Instan (Sandbox)');
    }

    /**
     * Otorisasi: Pengguna lain tidak memiliki hak akses melihat pelacakan pesanan orang lain (403).
     */
    public function test_unauthorized_user_cannot_view_shipment_tracking(): void
    {
        $this->courierService->requestPickup($this->order);

        Livewire::actingAs($this->otherCustomer)
            ->test(OrderShipmentTracker::class, ['orderId' => $this->order->id])
            ->assertStatus(403);
    }

    /**
     * Simulasi progress kurir via tombol di antarmuka berhasil memajukan status secara interaktif.
     */
    public function test_simulator_progresses_courier_status_successfully(): void
    {
        $this->courierService->requestPickup($this->order);

        Livewire::actingAs($this->customer)
            ->test(OrderShipmentTracker::class, ['orderId' => $this->order->id])
            ->call('simulateStep', 'dalam_perjalanan')
            ->assertHasNoErrors();

        $this->assertEquals(ShipmentStatus::DALAM_PERJALANAN, $this->order->fresh()->shipment->status);
        $this->assertEquals(ProductOrderStatus::DIKIRIM, $this->order->fresh()->status);

        // Simulasi sampai tujuan
        Livewire::actingAs($this->customer)
            ->test(OrderShipmentTracker::class, ['orderId' => $this->order->id])
            ->call('simulateStep', 'terkirim')
            ->assertHasNoErrors();

        $this->assertEquals(ShipmentStatus::TERKIRIM, $this->order->fresh()->shipment->status);
        $this->assertEquals(ProductOrderStatus::SELESAI, $this->order->fresh()->status);
    }
}
