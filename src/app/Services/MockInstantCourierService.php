<?php

namespace App\Services;

use App\Enums\ProductOrderStatus;
use App\Enums\ShipmentStatus;
use App\Models\ProductOrder;
use App\Models\Shipment;
use App\Services\Contracts\InstantCourierServiceInterface;
use Illuminate\Support\Str;

class MockInstantCourierService implements InstantCourierServiceInterface
{
    /**
     * Mengajukan permintaan penjemputan paket kurir instan (AB-013).
     */
    public function requestPickup(ProductOrder $order): Shipment
    {
        $shipment = $order->shipment ?: new Shipment(['product_order_id' => $order->id]);

        $trackingNumber = $shipment->tracking_number ?: ('FIX-EXP-' . date('Ymd') . '-' . strtoupper(Str::random(6)));

        $storeAddress = $order->store?->address ?: 'Gudang Toko ' . ($order->store?->name ?? 'Mitra');
        $customerAddress = $order->customer?->name . ' - ' . ($order->serviceOrder?->address ?: 'Alamat Pengiriman Pelanggan');

        $shipment->fill([
            'provider_name' => 'FixHome Express (Simulasi Instan)',
            'tracking_number' => $trackingNumber,
            'origin_address' => $shipment->origin_address ?: $storeAddress,
            'destination_address' => $shipment->destination_address ?: $customerAddress,
            'shipping_cost' => $order->shipping_fee ?: 15000,
            'estimated_hours' => 2.0,
            'status' => ShipmentStatus::DIMINTA,
        ]);

        $shipment->save();

        // Update status pesanan produk menjadi siap dikirim
        $order->update(['status' => ProductOrderStatus::SIAP_DIKIRIM]);

        return $shipment;
    }

    /**
     * Melacak status pengiriman dan riwayat timeline perjalanan paket.
     */
    public function trackShipment(string $trackingNumber): array
    {
        $shipment = Shipment::query()
            ->with(['productOrder.store', 'productOrder.customer'])
            ->where('tracking_number', $trackingNumber)
            ->firstOrFail();

        $checkpoints = [];

        // Checkpoint 1: Dibuat
        $checkpoints[] = [
            'title' => 'Pesanan Dibuat & Pembayaran Dikonfirmasi',
            'description' => 'Pesanan material bangunan telah terdaftar di sistem toko mitra.',
            'timestamp' => $shipment->created_at->translatedFormat('d M Y, H:i'),
            'completed' => true,
        ];

        // Checkpoint 2: Permintaan Penjemputan Kurir
        $pickupRequested = in_array($shipment->status, [
            ShipmentStatus::DIMINTA,
            ShipmentStatus::DIJEMPUT,
            ShipmentStatus::DALAM_PERJALANAN,
            ShipmentStatus::TERKIRIM,
        ]);
        $checkpoints[] = [
            'title' => 'Permintaan Kurir Instan',
            'description' => 'Mitra toko telah meminta alokasi kurir instan untuk penjemputan barang.',
            'timestamp' => $pickupRequested ? $shipment->updated_at->translatedFormat('d M Y, H:i') : null,
            'completed' => $pickupRequested,
        ];

        // Checkpoint 3: Kurir Menjemput Barang
        $pickedUp = in_array($shipment->status, [
            ShipmentStatus::DIJEMPUT,
            ShipmentStatus::DALAM_PERJALANAN,
            ShipmentStatus::TERKIRIM,
        ]);
        $checkpoints[] = [
            'title' => 'Paket Dijemput Kurir',
            'description' => 'Kurir telah mengambil material dari toko mitra: ' . ($shipment->productOrder->store->name ?? '-'),
            'timestamp' => $pickedUp ? $shipment->updated_at->translatedFormat('d M Y, H:i') : null,
            'completed' => $pickedUp,
        ];

        // Checkpoint 4: Dalam Perjalanan
        $onTheWay = in_array($shipment->status, [
            ShipmentStatus::DALAM_PERJALANAN,
            ShipmentStatus::TERKIRIM,
        ]);
        $checkpoints[] = [
            'title' => 'Dalam Perjalanan ke Tujuan',
            'description' => 'Kurir sedang dalam perjalanan menuju lokasi pelanggan.',
            'timestamp' => $onTheWay ? $shipment->updated_at->translatedFormat('d M Y, H:i') : null,
            'completed' => $onTheWay,
        ];

        // Checkpoint 5: Terkirim
        $isDelivered = $shipment->status === ShipmentStatus::TERKIRIM;
        $checkpoints[] = [
            'title' => 'Paket Telah Tiba & Diterima',
            'description' => 'Material bangunan telah diserahkan dan diterima oleh pelanggan.',
            'timestamp' => $shipment->delivered_at ? $shipment->delivered_at->translatedFormat('d M Y, H:i') : null,
            'completed' => $isDelivered,
        ];

        return [
            'shipment' => $shipment,
            'driver_name' => 'Budi Santoso (Kurir Motor Instan)',
            'driver_plate' => 'B 4123 SXZ',
            'driver_phone' => '081288887777',
            'estimated_time' => '1 - 2 Jam (Pengiriman Hari yang Sama)',
            'checkpoints' => $checkpoints,
        ];
    }

    /**
     * Memperbarui status pengiriman dan menyinkronkan status pesanan produk.
     */
    public function updateStatus(Shipment $shipment, ShipmentStatus $status): Shipment
    {
        $shipment->status = $status;

        if ($status === ShipmentStatus::TERKIRIM) {
            $shipment->delivered_at = now();
        }

        $shipment->save();

        // Sinkronkan status ProductOrder terkait
        $order = $shipment->productOrder;
        if ($order) {
            if ($status === ShipmentStatus::DALAM_PERJALANAN || $status === ShipmentStatus::DIJEMPUT) {
                $order->update(['status' => ProductOrderStatus::DIKIRIM]);
            } elseif ($status === ShipmentStatus::TERKIRIM) {
                $order->update(['status' => ProductOrderStatus::SELESAI]);
            } elseif ($status === ShipmentStatus::DIBATALKAN || $status === ShipmentStatus::GAGAL) {
                $order->update(['status' => ProductOrderStatus::DIBATALKAN]);
            }
        }

        return $shipment->fresh();
    }

    /**
     * Membatalkan pengiriman kurir.
     */
    public function cancelShipment(string $trackingNumber): bool
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->first();

        if (! $shipment) {
            return false;
        }

        if (in_array($shipment->status, [ShipmentStatus::DALAM_PERJALANAN, ShipmentStatus::TERKIRIM])) {
            return false; // Tidak bisa dibatalkan jika sudah di perjalanan atau terkirim
        }

        $this->updateStatus($shipment, ShipmentStatus::DIBATALKAN);

        return true;
    }
}
