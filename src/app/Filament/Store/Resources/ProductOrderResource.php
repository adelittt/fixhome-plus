<?php

namespace App\Filament\Store\Resources;

use App\Enums\ProductOrderStatus;
use App\Enums\ShipmentStatus;
use App\Filament\Store\Resources\ProductOrderResource\Pages;
use App\Models\ProductOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductOrderResource extends Resource
{
    protected static ?string $model = ProductOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $modelLabel = 'Pesanan Masuk';

    protected static ?string $pluralModelLabel = 'Pesanan Toko Masuk';

    protected static ?int $navigationSort = 2;

    /**
     * Isolasi Data Kueri (AB-009).
     * Mitra Toko HANYA dapat melihat pesanan produk yang ditujukan untuk tokonya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        $storeId = auth()->user()?->store?->id;

        if (! $storeId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('store_id', $storeId);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Forms\Components\TextInput::make('customer.name')
                            ->label('Nama Pemesan')
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
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Butir Material yang Dipesan')
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
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options(collect(ProductOrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                // Aksi Proses Pesanan
                Tables\Actions\Action::make('proses')
                    ->label('Proses Pesanan')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->visible(fn (ProductOrder $record) => $record->status === ProductOrderStatus::DIBAYAR)
                    ->requiresConfirmation()
                    ->action(function (ProductOrder $record) {
                        $record->update(['status' => ProductOrderStatus::DIPROSES]);
                        Notification::make()
                            ->title('Pesanan Sedang Diproses')
                            ->body("Pesanan #{$record->order_number} berhasil diubah ke status Diproses Toko.")
                            ->success()
                            ->send();
                    }),

                // Aksi Minta Penjemputan Kurir Instan (Fase 7: AB-013, UAT-07)
                Tables\Actions\Action::make('minta_kurir')
                    ->label('Minta Kurir Instan')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->visible(fn (ProductOrder $record) => in_array($record->status, [ProductOrderStatus::DIBAYAR, ProductOrderStatus::DIPROSES]))
                    ->requiresConfirmation()
                    ->modalHeading('Panggil Kurir Instan')
                    ->modalDescription('Sistem akan menghubungi kurir instan untuk melakukan penjemputan paket material di lokasi toko Anda.')
                    ->action(function (ProductOrder $record) {
                        try {
                            $courierService = app(\App\Services\Contracts\InstantCourierServiceInterface::class);
                            $shipment = $courierService->requestPickup($record);

                            Notification::make()
                                ->title('Kurir Instan Ditugaskan')
                                ->body("Kurir instan ({$shipment->provider_name}) telah dialokasikan dengan nomor resi {$shipment->tracking_number}.")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Permintaan Kurir Gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                // Aksi Kirim Pesanan
                Tables\Actions\Action::make('kirim')
                    ->label('Kirim Material')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (ProductOrder $record) => in_array($record->status, [ProductOrderStatus::DIPROSES, ProductOrderStatus::SIAP_DIKIRIM]))
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi / Pelacakan Kurir')
                            ->default(fn (ProductOrder $record) => $record->shipment?->tracking_number)
                            ->required(),
                    ])
                    ->action(function (ProductOrder $record, array $data) {
                        $record->update(['status' => ProductOrderStatus::DIKIRIM]);
                        if ($record->shipment) {
                            $record->shipment->update([
                                'tracking_number' => $data['tracking_number'],
                                'status' => ShipmentStatus::DALAM_PERJALANAN,
                            ]);
                        }
                        Notification::make()
                            ->title('Pesanan Telah Dikirim')
                            ->body("Pesanan #{$record->order_number} telah diserahkan ke kurir pengiriman.")
                            ->success()
                            ->send();
                    }),
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
