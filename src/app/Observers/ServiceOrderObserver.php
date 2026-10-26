<?php

namespace App\Observers;

use App\Enums\ServiceOrderStatus;
use App\Models\ServiceOrder;
use App\Services\CommissionService;

class ServiceOrderObserver
{
    /**
     * Otomatisasi pencatatan komisi jasa 5% saat status menjadi 'selesai' (AB-001, AB-005).
     * Jika pesanan dibatalkan/ditolak setelah tercatat, perbarui status komisi menjadi 'dibatalkan'.
     */
    public function saved(ServiceOrder $order): void
    {
        if ($order->wasChanged('status') || ($order->wasRecentlyCreated && $order->status === ServiceOrderStatus::SELESAI)) {
            if ($order->status === ServiceOrderStatus::SELESAI) {
                app(CommissionService::class)->recordServiceCommission($order);
            } elseif (in_array($order->status, [ServiceOrderStatus::DIBATALKAN, ServiceOrderStatus::DITOLAK])) {
                if ($order->serviceCommission) {
                    $order->serviceCommission->update(['status' => 'dibatalkan']);
                }
            }
        }
    }
}
