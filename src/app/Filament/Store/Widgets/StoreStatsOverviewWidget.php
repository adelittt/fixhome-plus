<?php

namespace App\Filament\Store\Widgets;

use App\Enums\ProductOrderStatus;
use App\Models\MarketplaceCommission;
use App\Models\Product;
use App\Models\ProductOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return [
                Stat::make('Penghasilan Bersih (98%)', 'Rp0')
                    ->description('Profil toko belum terdaftar')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('gray'),
                Stat::make('Perlu Diproses', '0 Pesanan')
                    ->description('Menunggu pesanan masuk')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('gray'),
                Stat::make('Pengiriman Kurir', '0 Pesanan')
                    ->description('Tidak ada pengiriman berjalan')
                    ->descriptionIcon('heroicon-m-truck')
                    ->color('gray'),
                Stat::make('Katalog Produk', '0 Produk')
                    ->description('Belum ada produk material')
                    ->descriptionIcon('heroicon-m-cube')
                    ->color('gray'),
            ];
        }

        // 1. Penghasilan Bersih Toko (98%) (AB-003)
        $totalEarning = MarketplaceCommission::where('store_id', $storeId)->sum('store_earning');
        $completedOrderCount = MarketplaceCommission::where('store_id', $storeId)->count();

        // 2. Pesanan Masuk Perlu Diproses Toko
        $pendingOrders = ProductOrder::where('store_id', $storeId)
            ->whereIn('status', [ProductOrderStatus::DIBAYAR, ProductOrderStatus::DIPROSES])
            ->count();

        // 3. Pesanan Dalam Pengiriman Kurir (AB-013)
        $shippingOrders = ProductOrder::where('store_id', $storeId)
            ->whereIn('status', [ProductOrderStatus::SIAP_DIKIRIM, ProductOrderStatus::DIKIRIM])
            ->count();

        // 4. Katalog Material & Status Stok Menipis
        $totalProducts = Product::where('store_id', $storeId)->count();
        $lowStockProducts = Product::where('store_id', $storeId)
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->count();

        return [
            Stat::make('Penghasilan Bersih (98%)', 'Rp' . number_format($totalEarning, 0, ',', '.'))
                ->description("Dari {$completedOrderCount} pesanan selesai (AB-003)")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->url('/toko/marketplace-commissions'),

            Stat::make('Perlu Diproses', "{$pendingOrders} Pesanan")
                ->description($pendingOrders > 0 ? 'Segera siapkan barang untuk kurir' : 'Semua pesanan telah diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'primary')
                ->url('/toko/product-orders'),

            Stat::make('Pengiriman Kurir', "{$shippingOrders} Pesanan")
                ->description('Siap dipickup & dalam perjalanan (AB-013)')
                ->descriptionIcon('heroicon-m-truck')
                ->color($shippingOrders > 0 ? 'info' : 'gray')
                ->url('/toko/product-orders'),

            Stat::make('Katalog Produk', "{$totalProducts} Produk")
                ->description($lowStockProducts > 0 ? "⚠️ {$lowStockProducts} produk stok menipis (≤ 10)" : 'Semua stok produk mencukupi')
                ->descriptionIcon('heroicon-m-cube')
                ->color($lowStockProducts > 0 ? 'danger' : 'primary')
                ->url('/toko/products'),
        ];
    }
}
