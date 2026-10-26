<?php

namespace Tests\Unit;

use App\Models\SystemSetting;
use App\Services\FakeRouteDistanceService;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistancePricingTest extends TestCase
{
    use RefreshDatabase;

    protected FakeRouteDistanceService $fakeRouter;
    protected PricingService $pricingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeRouter = new FakeRouteDistanceService();
        $this->pricingService = new PricingService($this->fakeRouter);
    }

    /**
     * Uji batas jarak rute:
     * - 9,99 km = Rp0
     * - 10,00 km = Rp10.000
     * - 10,01 km = Rp10.000
     */
    public function test_distance_fee_threshold_boundary_cases(): void
    {
        // 1. Batas bawah: 9.99 km
        $feeBelow = $this->pricingService->calculateDistanceFee(9.99);
        $this->assertIsInt($feeBelow);
        $this->assertEquals(0, $feeBelow, 'Jarak 9,99 km harus dikenakan biaya jarak Rp0');

        // 2. Tepat pada ambang batas: 10.00 km
        $feeExact = $this->pricingService->calculateDistanceFee(10.00);
        $this->assertIsInt($feeExact);
        $this->assertEquals(10000, $feeExact, 'Jarak 10,00 km harus dikenakan biaya jarak Rp10.000');

        // 3. Batas atas: 10.01 km
        $feeAbove = $this->pricingService->calculateDistanceFee(10.01);
        $this->assertIsInt($feeAbove);
        $this->assertEquals(10000, $feeAbove, 'Jarak 10,01 km harus dikenakan biaya jarak Rp10.000');
    }

    /**
     * Jarak harus jarak RUTE menggunakan RouteDistanceServiceInterface,
     * dan menggunakan FakeRouteDistanceService (tidak memanggil network luar).
     */
    public function test_route_distance_service_fake_integration(): void
    {
        $this->fakeRouter->setDistanceKm(12.45);

        $calculation = $this->pricingService->calculateRouteAndDistanceFee(
            -6.1702, 106.6403,
            -6.2615, 106.8106
        );

        $this->assertEquals(12.45, $calculation['distance_km']);
        $this->assertEquals(10000, $calculation['distance_fee']);
    }

    /**
     * Uji PK-15 pada dua nilai pengaturan (service_commission_includes_distance_fee = false vs true).
     * Nilai DEFAULT: false (sesuai docs/ASSUMPTIONS.md).
     */
    public function test_pk15_commission_base_with_setting_toggle(): void
    {
        $baseRate = 80000;
        $distanceFee = 10000;

        // 1. Uji kondisi DEFAULT (false): komisi jasa murni dari tarif dasar, biaya jarak TIDAK masuk dasar komisi
        SystemSetting::set('service_commission_includes_distance_fee', false, 'boolean');
        $baseDefault = $this->pricingService->getCommissionBaseAmount($baseRate, $distanceFee);

        $this->assertIsInt($baseDefault);
        $this->assertEquals(80000, $baseDefault, 'Saat pengaturan false, dasar komisi hanya sebesar tarif dasar jasa');

        // 2. Uji kondisi saat pengaturan diubah menjadi TRUE: biaya jarak masuk ke dasar komisi
        SystemSetting::set('service_commission_includes_distance_fee', true, 'boolean');
        $baseWithDistance = $this->pricingService->getCommissionBaseAmount($baseRate, $distanceFee);

        $this->assertIsInt($baseWithDistance);
        $this->assertEquals(90000, $baseWithDistance, 'Saat pengaturan true, dasar komisi = tarif dasar + biaya jarak');
    }

    /**
     * Total jasa = tarif jasa + distance_fee dengan integer, dan jarak < 10 km tidak menambah biaya.
     */
    public function test_total_service_fee_integer_arithmetic(): void
    {
        $baseRate = 125000;

        // Kasus 1: Jarak < 10 km (misal 4.5 km) => total = baseRate + 0
        $shortDistance = $this->pricingService->calculateServiceOrderPricing($baseRate, 4.5);
        $this->assertIsInt($shortDistance['total_service_fee']);
        $this->assertEquals(0, $shortDistance['distance_fee']);
        $this->assertEquals(125000, $shortDistance['total_service_fee']);

        // Kasus 2: Jarak >= 10 km (misal 15.0 km) => total = baseRate + 10000
        $longDistance = $this->pricingService->calculateServiceOrderPricing($baseRate, 15.0);
        $this->assertIsInt($longDistance['total_service_fee']);
        $this->assertEquals(10000, $longDistance['distance_fee']);
        $this->assertEquals(135000, $longDistance['total_service_fee']);
    }
}
