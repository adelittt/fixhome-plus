<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\ProductOrderStatus;
use App\Enums\ServiceOrderStatus;
use App\Enums\VerificationStatus;
use App\Models\MarketplaceCommission;
use App\Models\ProductOrder;
use App\Models\ServiceCommission;
use App\Models\ServiceOrder;
use App\Models\Store;
use App\Models\TechnicianProfile;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Komisi Jasa Platform 5% (AB-001, AB-005)
        $totalServiceCommission = ServiceCommission::sum('platform_commission');
        $serviceOrderCount = ServiceCommission::count();

        // 2. Komisi Marketplace Platform 2% (AB-003, AB-005)
        $totalMarketplaceCommission = MarketplaceCommission::sum('platform_commission');
        $marketplaceOrderCount = MarketplaceCommission::count();

        // 3. Menunggu Verifikasi Admin (AB-011)
        $pendingTech = TechnicianProfile::where('verification_status', VerificationStatus::MENUNGGU)->count();
        $pendingStore = Store::where('verification_status', VerificationStatus::MENUNGGU)->count();
        $totalPending = $pendingTech + $pendingStore;

        // 4. Transaksi Berjalan (Jasa & Marketplace)
        $activeServiceOrders = ServiceOrder::whereIn('status', [
            ServiceOrderStatus::MENUNGGU_TEKNISI,
            ServiceOrderStatus::DITERIMA,
            ServiceOrderStatus::BERLANGSUNG,
        ])->count();

        $activeProductOrders = ProductOrder::whereIn('status', [
            ProductOrderStatus::DIBAYAR,
            ProductOrderStatus::DIPROSES,
            ProductOrderStatus::SIAP_DIKIRIM,
            ProductOrderStatus::DIKIRIM,
        ])->count();

        return [
            Stat::make('Komisi Jasa (5%)', 'Rp' . number_format($totalServiceCommission, 0, ',', '.'))
                ->description("Dari {$serviceOrderCount} transaksi jasa selesai (AB-001)")
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('success'),

            Stat::make('Komisi Marketplace (2%)', 'Rp' . number_format($totalMarketplaceCommission, 0, ',', '.'))
                ->description("Dari {$marketplaceOrderCount} pesanan material selesai (AB-003)")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Perlu Verifikasi', "{$totalPending} Mitra")
                ->description("{$pendingTech} Teknisi • {$pendingStore} Toko menunggu (AB-011)")
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($totalPending > 0 ? 'danger' : 'gray')
                ->url($pendingTech > 0 ? '/admin/technician-profiles' : '/admin/stores'),

            Stat::make('Pesanan Aktif', "{$activeServiceOrders} Jasa • {$activeProductOrders} Toko")
                ->description('Dalam proses pengerjaan dan pengiriman kurir')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary'),
        ];
    }
}
