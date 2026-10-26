<?php

namespace App\Services;

use App\Services\Contracts\RouteDistanceServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OsrmRouteDistanceService implements RouteDistanceServiceInterface
{
    /**
     * Menghitung jarak tempuh rute (jalan) dalam kilometer antara dua titik koordinat.
     * Menggunakan OSRM publik dengan caching 24 jam dan fallback Haversine 1.3x.
     */
    public function calculateDistanceKm(float $originLat, float $originLng, float $destLat, float $destLng): float
    {
        // Koordinat dibulatkan ke 4 desimal (~11 meter) untuk efisiensi caching
        $cacheKey = sprintf(
            'osrm_route_%.4f_%.4f_%.4f_%.4f',
            $originLat,
            $originLng,
            $destLat,
            $destLng
        );

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($originLat, $originLng, $destLat, $destLng) {
            try {
                // OSRM format koordinat: {longitude},{latitude};{longitude},{latitude}
                $url = sprintf(
                    'https://router.project-osrm.org/route/v1/driving/%.6f,%.6f;%.6f,%.6f?overview=false',
                    $originLng,
                    $originLat,
                    $destLng,
                    $destLat
                );

                $response = Http::timeout(3)->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['code'] ?? '') === 'Ok' && ! empty($data['routes'][0]['distance'])) {
                        $distanceMeters = (float) $data['routes'][0]['distance'];
                        return round($distanceMeters / 1000.0, 2);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal memanggil OSRM, beralih ke estimasi Haversine 1.3x: ' . $e->getMessage());
            }

            // Fallback: Haversine dengan faktor jalan perkotaan 1.3x
            return $this->calculateHaversineDistanceKm($originLat, $originLng, $destLat, $destLng);
        });
    }

    /**
     * Rumus Haversine dikalikan 1.3x sebagai estimasi rute jalan darat saat OSRM offline.
     */
    public function calculateHaversineDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371.0;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $straightDistance = $earthRadiusKm * $c;

        // Faktor rute jalan perkotaan (urban circuity factor) 1.3x
        return round($straightDistance * 1.3, 2);
    }
}
