<?php

namespace App\Filament\Store\Resources\MarketplaceCommissionResource\Widgets;

use App\Models\MarketplaceCommission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreCommissionOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return [];
        }

        $commissions = MarketplaceCommission::where('store_id', $storeId)->where('status', 'settled');

        $totalStoreEarning = (int) $commissions->sum('store_earning');
        $totalGross = (int) $commissions->sum('gross_product_amount');
        $totalPlatformFee = (int) $commissions->sum('platform_commission');
        $settledCount = $commissions->count();

        return [
            Stat::make('Penghasilan Bersih Toko (98%)', 'Rp' . number_format($totalStoreEarning, 0, ',', '.'))
                ->description("Dari {$settledCount} pesanan material yang berhasil diselesaikan")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Omzet Belanja Produk', 'Rp' . number_format($totalGross, 0, ',', '.'))
                ->description('Nilai kotor seluruh material terjual')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Bagi Hasil Platform (2%)', 'Rp' . number_format($totalPlatformFee, 0, ',', '.'))
                ->description('Biaya layanan pemeliharaan sistem marketplace')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('gray'),

            Stat::make('Pesanan Selesai', "{$settledCount} Transaksi")
                ->description('Status dana telah diselesaikan (settled)')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }
}
