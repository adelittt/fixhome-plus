<?php

namespace App\Services;

use App\Enums\MarketplaceChoice;
use App\Models\MarketplaceCommission;
use App\Models\ProductOrder;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;

class CommissionService
{
    public const SERVICE_COMMISSION_RATE = 0.05; // 5%
    public const MARKETPLACE_COMMISSION_RATE = 0.02; // 2%

    public function __construct(
        protected ?PricingService $pricingService = null
    ) {}

    /**
     * Hitung komisi jasa perbaikan rumah (5% platform, 95% teknisi).
     * Nilai dibulatkan ke rupiah terdekat dan bertipe integer.
     */
    public function calculateServiceCommission(int $grossAmount): array
    {
        $platformCommission = (int) round($grossAmount * self::SERVICE_COMMISSION_RATE);
        $technicianEarning = (int) ($grossAmount - $platformCommission);

        return [
            'gross_amount' => (int) $grossAmount,
            'platform_rate' => self::SERVICE_COMMISSION_RATE,
            'platform_commission' => $platformCommission,
            'technician_earning' => $technicianEarning,
        ];
    }

    /**
     * Hitung komisi marketplace material (2% platform dari nilai produk murni, 98% toko).
     * Ongkir TIDAK dihitung ke dalam dasar komisi (AB-004).
     * Nilai dibulatkan ke rupiah terdekat dan bertipe integer.
     */
    public function calculateMarketplaceCommission(int $grossProductAmount, int $shippingFee = 0): array
    {
        // Ongkos kirim dipisahkan dan diabaikan dari dasar komisi
        $platformCommission = (int) round($grossProductAmount * self::MARKETPLACE_COMMISSION_RATE);
        $storeEarning = (int) ($grossProductAmount - $platformCommission);

        return [
            'gross_product_amount' => (int) $grossProductAmount,
            'shipping_fee' => (int) $shippingFee, // hanya dicatat sebagai informasi terpisah
            'platform_rate' => self::MARKETPLACE_COMMISSION_RATE,
            'platform_commission' => $platformCommission,
            'store_earning' => $storeEarning,
        ];
    }

    /**
     * Catat komisi jasa ke tabel terpisah `service_commissions` (AB-005).
     */
    public function recordServiceCommission(ServiceOrder $order): ServiceCommission
    {
        // Menentukan dasar komisi jasa sesuai PK-15
        $pricing = $this->pricingService ?? app(PricingService::class);
        $commissionBase = $pricing->getCommissionBaseAmount(
            $order->base_service_fee,
            $order->distance_fee
        );

        $calculation = $this->calculateServiceCommission($commissionBase);

        return ServiceCommission::updateOrCreate(
            ['service_order_id' => $order->id],
            [
                'technician_id' => $order->technician_id,
                'gross_amount' => $calculation['gross_amount'],
                'platform_rate' => $calculation['platform_rate'],
                'platform_commission' => $calculation['platform_commission'],
                'technician_earning' => $calculation['technician_earning'],
                'status' => 'tercatat',
            ]
        );
    }

    /**
     * Catat komisi marketplace ke tabel terpisah `marketplace_commissions` (AB-005).
     * Jika pelanggan memilih 'Lewati Marketplace' pada pesanan jasa induk,
     * tidak ada komisi marketplace yang dicatat (K-4).
     */
    public function recordMarketplaceCommission(ProductOrder $order): ?MarketplaceCommission
    {
        // Verifikasi K-4: Jika terikat ke pesanan jasa dan pelanggan memilih Lewati
        if ($order->serviceOrder && $order->serviceOrder->marketplace_choice === MarketplaceChoice::LEWATI) {
            return null;
        }

        $calculation = $this->calculateMarketplaceCommission(
            $order->total_product_amount,
            $order->shipping_fee
        );

        return MarketplaceCommission::updateOrCreate(
            ['product_order_id' => $order->id],
            [
                'store_id' => $order->store_id,
                'gross_product_amount' => $calculation['gross_product_amount'],
                'platform_rate' => $calculation['platform_rate'],
                'platform_commission' => $calculation['platform_commission'],
                'store_earning' => $calculation['store_earning'],
                'status' => 'tercatat',
            ]
        );
    }
}
