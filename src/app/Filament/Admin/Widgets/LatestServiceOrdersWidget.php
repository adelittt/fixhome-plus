<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ServiceOrder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestServiceOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pesanan Jasa Perbaikan Rumah Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ServiceOrder::query()
                    ->with(['customer', 'technician', 'category'])
                    ->latest()
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan Jasa')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan'),
                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Teknisi'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori Layanan')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('total_service_fee')
                    ->label('Total Biaya')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'menunggu_teknisi',
                        'info' => 'diterima',
                        'primary' => 'berlangsung',
                        'success' => 'selesai',
                        'danger' => ['ditolak', 'dibatalkan'],
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
