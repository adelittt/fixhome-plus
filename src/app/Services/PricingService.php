<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Services\Contracts\RouteDistanceServiceInterface;

class PricingService
{
    public const DISTANCE_THRESHOLD_KM = 10.0;
    public const DISTANCE_FEE_AMOUNT = 10000;

    public function __construct(
        protected RouteDistanceServiceInterface $routeDistanceService
    ) {}

    /**
     * Hitung biaya tambahan jarak rute (AB-002).
     * Jarak rute >= 10.00 km dikenakan biaya tambahan Rp10.000.
     * Jarak rute < 10.00 km = Rp0.
     */
    public function calculateDistanceFee(float $distanceKm): int
    {
        // Presisi 2 desimal: 9.99 km => Rp0, 10.00 km => Rp10.000
        if (round($distanceKm, 2) >= self::DISTANCE_THRESHOLD_KM) {
            return self::DISTANCE_FEE_AMOUNT;
        }

        return 0;
    }

    /**
     * Hitung jarak rute dari koordinat asal ke tujuan, beserta biaya tambahan jarak.
     */
    public function calculateRouteAndDistanceFee(float $originLat, float $originLng, float $destLat, float $destLng): array
    {
        $distanceKm = $this->routeDistanceService->calculateDistanceKm($originLat, $originLng, $destLat, $destLng);
        $distanceFee = $this->calculateDistanceFee($distanceKm);

        return [
            'distance_km' => $distanceKm,
            'distance_fee' => $distanceFee,
        ];
    }

    /**
     * Hitung rincian total biaya jasa.
     * Total jasa = tarif dasar jasa + distance_fee (seluruhnya bertipe integer).
     */
    public function calculateServiceOrderPricing(int $baseRate, float $distanceKm): array
    {
        $distanceFee = $this->calculateDistanceFee($distanceKm);
        $totalFee = $baseRate + $distanceFee;

        return [
            'base_service_fee' => (int) $baseRate,
            'distance_fee' => (int) $distanceFee,
            'total_service_fee' => (int) $totalFee,
        ];
    }

    /**
     * Menentukan dasar komisi jasa (PK-15).
     * Jika pengaturan 'service_commission_includes_distance_fee' bernilai true,
     * dasar komisi adalah tarif dasar + biaya jarak.
     * Jika false (default), dasar komisi adalah murni tarif dasar jasa.
     */
    public function getCommissionBaseAmount(int $baseRate, int $distanceFee): int
    {
        $includesDistanceFee = SystemSetting::get('service_commission_includes_distance_fee', false);

        if ($includesDistanceFee) {
            return (int) ($baseRate + $distanceFee);
        }

        return (int) $baseRate;
    }
}
