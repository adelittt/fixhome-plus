<?php

namespace App\Filament\Store\Resources\ProductOrderResource\Pages;

use App\Enums\ProductOrderStatus;
use App\Filament\Store\Resources\ProductOrderResource;
use App\Models\ProductOrder;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProductOrders extends ListRecords
{
    protected static string $resource = ProductOrderResource::class;

    public function getTabs(): array
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return [];
        }

        $allCount = ProductOrder::where('store_id', $storeId)->count();
        $needProcessCount = ProductOrder::where('store_id', $storeId)
            ->where('status', ProductOrderStatus::DIBAYAR)
            ->count();
        $inProgressCount = ProductOrder::where('store_id', $storeId)
            ->where('status', ProductOrderStatus::DIPROSES)
            ->count();
        $shippingCount = ProductOrder::where('store_id', $storeId)
            ->whereIn('status', [ProductOrderStatus::SIAP_DIKIRIM, ProductOrderStatus::DIKIRIM])
            ->count();
        $completedCount = ProductOrder::where('store_id', $storeId)
            ->where('status', ProductOrderStatus::SELESAI)
            ->count();

        return [
            'all' => Tab::make('Semua Pesanan')
                ->icon('heroicon-m-squares-2x2')
                ->badge($allCount)
                ->badgeColor('gray'),

            'need_process' => Tab::make('Perlu Diproses')
                ->icon('heroicon-m-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ProductOrderStatus::DIBAYAR))
                ->badge($needProcessCount > 0 ? $needProcessCount : null)
                ->badgeColor('warning'),

            'preparing' => Tab::make('Sedang Disiapkan')
                ->icon('heroicon-m-cube')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ProductOrderStatus::DIPROSES))
                ->badge($inProgressCount > 0 ? $inProgressCount : null)
                ->badgeColor('primary'),

            'shipping' => Tab::make('Pengiriman Kurir')
                ->icon('heroicon-m-truck')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [ProductOrderStatus::SIAP_DIKIRIM, ProductOrderStatus::DIKIRIM]))
                ->badge($shippingCount > 0 ? $shippingCount : null)
                ->badgeColor('info'),

            'completed' => Tab::make('Selesai')
                ->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ProductOrderStatus::SELESAI))
                ->badge($completedCount > 0 ? $completedCount : null)
                ->badgeColor('success'),
        ];
    }
}
