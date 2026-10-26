<?php

namespace App\Filament\Store\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductOverviewWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return [];
        }

        $totalProducts = Product::where('store_id', $storeId)->count();
        $activeProducts = Product::where('store_id', $storeId)->where('is_active', true)->count();
        
        $inventoryValue = (int) Product::where('store_id', $storeId)
            ->selectRaw('COALESCE(SUM(price * stock), 0) as val')
            ->value('val');

        $lowStockCount = Product::where('store_id', $storeId)
            ->where('stock', '>', 0)
            ->where('stock', '<=', 15)
            ->count();

        $outOfStockCount = Product::where('store_id', $storeId)
            ->where('stock', '<=', 0)
            ->count();

        return [
            Stat::make('Total Katalog', "{$totalProducts} Produk")
                ->description("{$activeProducts} produk aktif di marketplace")
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Nilai Inventaris Gudang', 'Rp' . number_format($inventoryValue, 0, ',', '.'))
                ->description('Estimasi nilai seluruh stok material')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Stok Menipis (≤ 15)', "{$lowStockCount} Produk")
                ->description($lowStockCount > 0 ? 'Perlu restok segera sebelum habis' : 'Tidak ada stok menipis')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'warning' : 'gray'),

            Stat::make('Stok Habis (Kosong)', "{$outOfStockCount} Produk")
                ->description($outOfStockCount > 0 ? 'Barang tidak dapat dipesan pelanggan' : 'Semua produk memiliki stok')
                ->descriptionIcon('heroicon-m-archive-box-x-mark')
                ->color($outOfStockCount > 0 ? 'danger' : 'gray'),
        ];
    }
}
