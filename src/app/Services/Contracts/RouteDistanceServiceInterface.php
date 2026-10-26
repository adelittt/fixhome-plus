<?php

namespace App\Services\Contracts;

interface RouteDistanceServiceInterface
{
    /**
     * Menghitung jarak tempuh rute (jalan) dalam kilometer antara dua titik koordinat.
     * Mengembalikan jarak rute dalam kilometer (float).
     */
    public function calculateDistanceKm(float $originLat, float $originLng, float $destLat, float $destLng): float;
}
