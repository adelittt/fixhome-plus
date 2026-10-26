<?php

namespace App\Filament\Admin\Resources;

use App\Enums\ProductOrderStatus;
use App\Filament\Admin\Resources\ProductOrderResource\Pages;
use App\Models\ProductOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductOrderResource extends Resource
{
    protected static ?string $model = ProductOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Pesanan Marketplace';

    protected static ?string $pluralModelLabel = 'Pesanan Marketplace';

    protected static ?string $navigationGroup = 'Marketplace & Transaksi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan Marketplace')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Forms\Components\TextInput::make('store.name')
                            ->label('Mitra Toko')
                            ->disabled(),
                        Forms\Components\TextInput::make('customer.name')
                            ->label('Pelanggan Pemesan')
                            ->disabled(),
                        Forms\Components\TextInput::make('total_product_amount')
                            ->label('Nilai Produk')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_fee')
                            ->label('Ongkos Kirim Toko')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('grand_total')
                            ->label('Total Transaksi')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options(collect(ProductOrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]))
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Rincian Butir Material')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\TextInput::make('product_name')
                                    ->label('Nama Produk')
                                    ->disabled(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled(),
                            ])
                            ->columns(4)
                            ->disableItemCreation()
                            ->disableItemDeletion(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->label('Mitra Toko')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_product_amount')
                    ->label('Nilai Produk')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_fee')
                    ->label('Ongkir')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Tagihan')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary')
                    ->sortable(),
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
                    ->label('Waktu Pemesanan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options(collect(ProductOrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                Tables\Filters\SelectFilter::make('store_id')
                    ->label('Filter Mitra Toko')
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
            'index' => Pages\ListProductOrders::route('/'),
            'view' => Pages\ViewProductOrder::route('/{record}'),
        ];
    }
}
