<?php

namespace App\Filament\Store\Widgets;

use App\Models\ProductOrder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStoreOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pesanan Material Masuk Terbaru';

    public function table(Table $table): Table
    {
        $storeId = auth()->user()?->store?->id ?? 0;

        return $table
            ->query(
                ProductOrder::query()
                    ->where('store_id', $storeId)
                    ->with('customer')
                    ->latest()
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan'),
                Tables\Columns\TextColumn::make('total_product_amount')
                    ->label('Nilai Material')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('shipping_fee')
                    ->label('Ongkir Kurir (AB-004)')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Transaksi')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'menunggu_pembayaran',
                        'info' => 'dibayar',
                        'primary' => 'diproses',
                        'secondary' => 'siap_dikirim',
                        'purple' => 'dikirim',
                        'success' => 'selesai',
                        'danger' => ['ditolak_toko', 'dibatalkan'],
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Lihat Detail')
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                    ->url(fn (ProductOrder $record): string => "/toko/product-orders/{$record->id}"),
            ])
            ->emptyStateHeading('Belum Ada Pesanan Masuk')
            ->emptyStateDescription('Pesanan material dari pelanggan atau rekomendasi teknisi akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->paginated(false);
    }
}
