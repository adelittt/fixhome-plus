<?php

namespace App\Filament\Store\Resources\ProductResource\Pages;

use App\Filament\Store\Resources\ProductResource;
use App\Filament\Store\Resources\ProductResource\Widgets\ProductOverviewWidget;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Produk Material')
                ->icon('heroicon-m-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return [];
        }

        $allCount = Product::where('store_id', $storeId)->count();
        $safeCount = Product::where('store_id', $storeId)->where('is_active', true)->where('stock', '>', 15)->count();
        $lowStockCount = Product::where('store_id', $storeId)->where('stock', '>', 0)->where('stock', '<=', 15)->count();
        $outOfStockCount = Product::where('store_id', $storeId)->where('stock', '<=', 0)->count();
        $inactiveCount = Product::where('store_id', $storeId)->where('is_active', false)->count();

        return [
            'all' => Tab::make('Semua Material')
                ->icon('heroicon-m-squares-2x2')
                ->badge($allCount)
                ->badgeColor('gray'),

            'safe' => Tab::make('Stok Aman')
                ->icon('heroicon-m-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)->where('stock', '>', 15))
                ->badge($safeCount > 0 ? $safeCount : null)
                ->badgeColor('success'),

            'low_stock' => Tab::make('⚠️ Stok Menipis')
                ->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '>', 0)->where('stock', '<=', 15))
                ->badge($lowStockCount > 0 ? $lowStockCount : null)
                ->badgeColor('warning'),

            'out_of_stock' => Tab::make('❌ Habis (0)')
                ->icon('heroicon-m-archive-box-x-mark')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '<=', 0))
                ->badge($outOfStockCount > 0 ? $outOfStockCount : null)
                ->badgeColor('danger'),

            'inactive' => Tab::make('Disembunyikan')
                ->icon('heroicon-m-eye-slash')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge($inactiveCount > 0 ? $inactiveCount : null)
                ->badgeColor('gray'),
        ];
    }
}
