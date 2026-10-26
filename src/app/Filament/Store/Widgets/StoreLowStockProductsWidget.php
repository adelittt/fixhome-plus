<?php

namespace App\Filament\Store\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StoreLowStockProductsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Peringatan Stok & Inventaris Material Terendah';

    public function table(Table $table): Table
    {
        $storeId = auth()->user()?->store?->id ?? 0;

        return $table
            ->query(
                Product::query()
                    ->where('store_id', $storeId)
                    ->with('category')
                    ->orderBy('stock', 'asc')
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk Material')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 10 => 'danger',
                        $state <= 20 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => match (true) {
                        $state <= 10 => "{$state} unit (Kritis)",
                        $state <= 20 => "{$state} unit (Menipis)",
                        default => "{$state} unit",
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif di Toko')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Kelola / Tambah Stok')
                    ->icon('heroicon-m-pencil-square')
                    ->color('primary')
                    ->url(fn (Product $record): string => "/toko/products/{$record->id}/edit"),
            ])
            ->emptyStateHeading('Belum Ada Katalog Produk')
            ->emptyStateDescription('Mulai tambahkan daftar material bangunan dan perkakas yang tersedia di toko Anda.')
            ->emptyStateIcon('heroicon-o-cube')
            ->paginated(false);
    }
}
