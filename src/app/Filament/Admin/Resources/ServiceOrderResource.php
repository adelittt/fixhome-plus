<?php

namespace App\Filament\Admin\Resources;

use App\Enums\MarketplaceChoice;
use App\Enums\ServiceOrderStatus;
use App\Filament\Admin\Resources\ServiceOrderResource\Pages;
use App\Models\ServiceOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderResource extends Resource
{
    protected static ?string $model = ServiceOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $modelLabel = 'Pengawasan Pesanan Jasa';

    protected static ?string $pluralModelLabel = 'Pesanan Layanan Jasa';

    protected static ?string $navigationGroup = 'Layanan & Teknisi';

    protected static ?int $navigationSort = 2;

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
                Forms\Components\Section::make('Informasi Pesanan Jasa (Layar 20)')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Forms\Components\TextInput::make('customer.name')
                            ->label('Pelanggan')
                            ->disabled(),
                        Forms\Components\TextInput::make('technician.name')
                            ->label('Teknisi Bertugas')
                            ->disabled(),
                        Forms\Components\TextInput::make('category.name')
                            ->label('Kategori Jasa')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Jadwal Pelaksanaan')
                            ->disabled(),
                        Forms\Components\TextInput::make('distance_km')
                            ->label('Jarak Rute (OSRM/Haversine)')
                            ->suffix('km')
                            ->disabled(),
                        Forms\Components\TextInput::make('base_service_fee')
                            ->label('Tarif Dasar Jasa')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('distance_fee')
                            ->label('Biaya Tambahan Jarak')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('total_service_fee')
                            ->label('Total Biaya Jasa')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options(collect(ServiceOrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]))
                            ->disabled(),
                        Forms\Components\Select::make('marketplace_choice')
                            ->label('Pilihan Marketplace')
                            ->options(collect(MarketplaceChoice::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]))
                            ->disabled(),
                        Forms\Components\Textarea::make('job_description')
                            ->label('Deskripsi Permasalahan')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Rumah Pelanggan')
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columns(3),
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
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('distance_km')
                    ->label('Jarak')
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' km')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_service_fee')
                    ->label('Total Biaya')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('marketplace_choice')
                    ->label('Opsi Marketplace')
                    ->badge()
                    ->colors([
                        'gray' => 'belum_dipilih',
                        'success' => 'lihat',
                        'warning' => 'lewati',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pemesanan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(ServiceOrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                Tables\Filters\SelectFilter::make('service_category_id')
                    ->label('Kategori Jasa')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceOrders::route('/'),
            'view' => Pages\ViewServiceOrder::route('/{record}'),
        ];
    }
}
