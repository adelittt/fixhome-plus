<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\MarketplaceCommissionResource\Pages;
use App\Models\MarketplaceCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCommissionResource extends Resource
{
    protected static ?string $model = MarketplaceCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'Penghasilan & Komisi Toko';

    protected static ?string $pluralModelLabel = 'Penghasilan & Komisi Toko (98%)';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 3;

    /**
     * Isolasi Data Kueri (AB-009).
     * Mitra Toko HANYA dapat melihat catatan komisi & penghasilan tokonya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('store_id', $storeId);
    }

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
                Forms\Components\Section::make('Rincian Pembagian Hasil Transaksi Toko')
                    ->schema([
                        Forms\Components\TextInput::make('productOrder.order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Forms\Components\TextInput::make('gross_product_amount')
                            ->label('Nilai Belanja Produk (Murni)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('platform_commission')
                            ->label('Komisi Platform FixHome Plus (2%)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('store_earning')
                            ->label('Penghasilan Bersih Diterima Toko (98%)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('productOrder.shipping_fee')
                            ->label('Ongkir Diterima Kurir (Info Terpisah)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->label('Status Pencatatan')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Waktu Selesai Transaksi')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productOrder.order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('gross_product_amount')
                    ->label('Nilai Produk')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('platform_commission')
                    ->label('Komisi Platform (2%)')
                    ->money('IDR', locale: 'id')
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('store_earning')
                    ->label('Bersih Toko (98%)')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('success')
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
