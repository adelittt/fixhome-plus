<?php

namespace App\Observers;

use App\Enums\ProductOrderStatus;
use App\Models\ProductOrder;
use App\Services\CommissionService;

class ProductOrderObserver
{
    /**
     * Otomatisasi pencatatan komisi marketplace 2% saat status menjadi 'selesai' (AB-003, AB-005, PK-04).
     * Ongkir tidak masuk dasar perhitungan (AB-004).
     * Jika pesanan dibatalkan/ditolak toko, perbarui status komisi menjadi 'dibatalkan'.
     */
    public function saved(ProductOrder $order): void
    {
        if ($order->wasChanged('status') || ($order->wasRecentlyCreated && $order->status === ProductOrderStatus::SELESAI)) {
            if ($order->status === ProductOrderStatus::SELESAI) {
                app(CommissionService::class)->recordMarketplaceCommission($order);
            } elseif (in_array($order->status, [ProductOrderStatus::DIBATALKAN, ProductOrderStatus::DITOLAK_TOKO])) {
                if ($order->marketplaceCommission) {
                    $order->marketplaceCommission->update(['status' => 'dibatalkan']);
                }
            }
        }
    }
}
