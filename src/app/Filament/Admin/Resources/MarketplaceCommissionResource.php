<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MarketplaceCommissionResource\Pages;
use App\Models\MarketplaceCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCommissionResource extends Resource
{
    protected static ?string $model = MarketplaceCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $modelLabel = 'Audit Komisi Marketplace';

    protected static ?string $pluralModelLabel = 'Audit Komisi Marketplace (2%)';

    protected static ?string $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rincian Komisi Marketplace Toko (AB-003, AB-004 & AB-005)')
                    ->schema([
                        Forms\Components\TextInput::make('productOrder.order_number')
                            ->label('Nomor Pesanan Toko')
                            ->disabled(),
                        Forms\Components\TextInput::make('store.name')
                            ->label('Mitra Toko')
                            ->disabled(),
                        Forms\Components\TextInput::make('gross_product_amount')
                            ->label('Nilai Murni Produk (Dasar Komisi)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('productOrder.shipping_fee')
                            ->label('Ongkos Kirim Terpisah (Tidak Masuk Komisi)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('platform_rate')
                            ->label('Tarif Komisi Platform')
                            ->formatStateUsing(fn ($state) => ($state * 100).'%')
                            ->disabled(),
                        Forms\Components\TextInput::make('platform_commission')
                            ->label('Komisi Platform (2%)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('store_earning')
                            ->label('Hak Penghasilan Toko (98%)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->label('Status Pencatatan')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Waktu Selesai & Tercatat')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productOrder.order_number')
                    ->label('No. Pesanan Toko')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('store.name')
                    ->label('Mitra Toko')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_product_amount')
                    ->label('Nilai Murni Produk')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('productOrder.shipping_fee')
                    ->label('Ongkir (Terpisah)')
                    ->money('IDR', locale: 'id')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('platform_commission')
                    ->label('Komisi (2%)')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_earning')
                    ->label('Hak Toko (98%)')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'tercatat',
                        'info' => 'dicairkan',
                        'danger' => 'dibatalkan',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'tercatat' => 'Tercatat',
                        'dicairkan' => 'Dicairkan',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('store_id')
                    ->label('Mitra Toko')
                    ->relationship('store', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketplaceCommissions::route('/'),
            'view' => Pages\ViewMarketplaceCommission::route('/{record}'),
        ];
    }
}
