<?php

namespace App\Services;

use App\Services\Contracts\InstantCourierServiceInterface;
use Illuminate\Support\Manager;

class ShippingManager extends Manager
{
    /**
     * Dapatkan nama driver default dari konfigurasi (AB-013, PK-01).
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('shipping.default', 'mock');
    }

    /**
     * Buat driver pengiriman simulasi instan lokal.
     */
    protected function createMockDriver(): InstantCourierServiceInterface
    {
        return new MockInstantCourierService();
    }

    /**
     * Buat driver pengiriman integrasi Biteship Sandbox API.
     */
    protected function createBiteshipDriver(): InstantCourierServiceInterface
    {
        return new BiteshipInstantCourierService(
            $this->config->get('shipping.drivers.biteship', [])
        );
    }
}
