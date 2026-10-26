<?php

namespace Tests\Feature;

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\TechnicianProfile;
use App\Models\User;
use App\Services\FakeRouteDistanceService;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistanceFeeOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $technician;
    protected ServiceCategory $category;
    protected FakeRouteDistanceService $fakeRouter;
    protected PricingService $pricingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeRouter = new FakeRouteDistanceService();
        $this->pricingService = new PricingService($this->fakeRouter);

        $this->customer = User::factory()->create([
            'role' => UserRole::PELANGGAN,
            'name' => 'Pelanggan Setia',
        ]);

        $this->technician = User::factory()->create([
            'role' => UserRole::TEKNISI,
            'name' => 'Teknisi Handal',
        ]);

        $this->category = ServiceCategory::create([
            'name' => 'Perbaikan Pipa',
            'slug' => 'perbaikan-pipa',
        ]);

        TechnicianProfile::create([
            'user_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'base_rate' => 80000, // Rp80.000
            'latitude' => -6.1702,
            'longitude' => 106.6403,
        ]);
    }

    /**
     * UAT-06: Jarak rute teknisi ke pelanggan >= 10 km (misal 11.5 km).
     * Hasil yang diharapkan: Biaya tambahan Rp10.000 otomatis bertambah,
     * total biaya jasa = base_rate + Rp10.000 (seluruhnya integer).
     */
    public function test_distance_greater_than_or_equal_to_10km_adds_10000_fee(): void
    {
        $this->fakeRouter->setDistanceKm(11.5);

        // Simulasi koordinat pelanggan (jarak rute 11.5 km dari teknisi)
        $customerLat = -6.2500;
        $customerLng = 106.7200;

        $distanceKm = $this->fakeRouter->calculateDistanceKm(
            -6.1702, 106.6403,
            $customerLat, $customerLng
        );

        $pricing = $this->pricingService->calculateServiceOrderPricing(80000, $distanceKm);

        $this->assertIsInt($pricing['base_service_fee']);
        $this->assertIsInt($pricing['distance_fee']);
        $this->assertIsInt($pricing['total_service_fee']);
        $this->assertEquals(10000, $pricing['distance_fee']);
        $this->assertEquals(90000, $pricing['total_service_fee']);

        // Simpan ke pesanan jasa
        $order = ServiceOrder::create([
            'order_number' => 'SRV-TEST-LONG-DIST',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'job_description' => 'Perbaikan pipa bocor lantai 2',
            'scheduled_at' => now()->addDays(2),
            'address' => 'Jl. Panjang No. 123, Jakarta Barat',
            'latitude' => $customerLat,
            'longitude' => $customerLng,
            'distance_km' => $distanceKm,
            'base_service_fee' => $pricing['base_service_fee'],
            'distance_fee' => $pricing['distance_fee'],
            'total_service_fee' => $pricing['total_service_fee'],
            'status' => ServiceOrderStatus::MENUNGGU_TEKNISI,
            'marketplace_choice' => MarketplaceChoice::BELUM_DIPILIH,
        ]);

        $this->assertDatabaseHas('service_orders', [
            'id' => $order->id,
            'distance_fee' => 10000,
            'base_service_fee' => 80000,
            'total_service_fee' => 90000,
        ]);
    }

    /**
     * UAT-06 (Kontrol Negatif): Jarak rute teknisi ke pelanggan < 10 km (misal 5.2 km).
     * Hasil yang diharapkan: Biaya tambahan jarak Rp0, total biaya jasa = base_rate.
     */
    public function test_distance_less_than_10km_does_not_add_fee(): void
    {
        $this->fakeRouter->setDistanceKm(5.2);

        $customerLat = -6.1800;
        $customerLng = 106.6500;

        $distanceKm = $this->fakeRouter->calculateDistanceKm(
            -6.1702, 106.6403,
            $customerLat, $customerLng
        );

        $pricing = $this->pricingService->calculateServiceOrderPricing(80000, $distanceKm);

        $this->assertIsInt($pricing['distance_fee']);
        $this->assertEquals(0, $pricing['distance_fee']);
        $this->assertEquals(80000, $pricing['total_service_fee']);

        $order = ServiceOrder::create([
            'order_number' => 'SRV-TEST-SHORT-DIST',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'job_description' => 'Ganti kran wastafel',
            'scheduled_at' => now()->addDays(1),
            'address' => 'Jl. Dekat No. 10, Tangerang',
            'latitude' => $customerLat,
            'longitude' => $customerLng,
            'distance_km' => $distanceKm,
            'base_service_fee' => $pricing['base_service_fee'],
            'distance_fee' => $pricing['distance_fee'],
            'total_service_fee' => $pricing['total_service_fee'],
            'status' => ServiceOrderStatus::MENUNGGU_TEKNISI,
            'marketplace_choice' => MarketplaceChoice::BELUM_DIPILIH,
        ]);

        $this->assertDatabaseHas('service_orders', [
            'id' => $order->id,
            'distance_fee' => 0,
            'base_service_fee' => 80000,
            'total_service_fee' => 80000,
        ]);
    }
}
