<?php

namespace App\Services\Contracts;

use App\Enums\ShipmentStatus;
use App\Models\ProductOrder;
use App\Models\Shipment;

interface InstantCourierServiceInterface
{
    /**
     * Mengajukan permintaan penjemputan paket ke penyedia kurir instan (AB-013).
     */
    public function requestPickup(ProductOrder $order): Shipment;

    /**
     * Melacak status pengiriman dan riwayat timeline perjalanan paket.
     */
    public function trackShipment(string $trackingNumber): array;

    /**
     * Memperbarui status pengiriman (digunakan oleh webhook / callback kurir / simulasi pengujian).
     */
    public function updateStatus(Shipment $shipment, ShipmentStatus $status): Shipment;

    /**
     * Membatalkan pengiriman paket jika belum dijemput kurir.
     */
    public function cancelShipment(string $trackingNumber): bool;
}
