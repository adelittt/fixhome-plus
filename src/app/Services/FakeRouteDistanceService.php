<?php

namespace App\Services;

use App\Services\Contracts\RouteDistanceServiceInterface;

class FakeRouteDistanceService implements RouteDistanceServiceInterface
{
    protected ?float $fixedDistanceKm = null;

    public function setDistanceKm(float $distanceKm): self
    {
        $this->fixedDistanceKm = $distanceKm;
        return $this;
    }

    public function calculateDistanceKm(float $originLat, float $originLng, float $destLat, float $destLng): float
    {
        if ($this->fixedDistanceKm !== null) {
            return $this->fixedDistanceKm;
        }

        // Default mock deterministic distance based on delta if not explicitly set
        $latDiff = abs($originLat - $destLat);
        $lngDiff = abs($originLng - $destLng);
        return round(($latDiff + $lngDiff) * 111.0, 2);
    }
}
