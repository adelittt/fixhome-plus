<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceCommissionResource\Pages;
use App\Models\ServiceCommission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ServiceCommissionResource extends Resource
{
    protected static ?string $model = ServiceCommission::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $modelLabel = 'Audit Komisi Jasa';

    protected static ?string $pluralModelLabel = 'Audit Komisi Jasa (5%)';

    protected static ?string $navigationGroup = 'Laporan & Keuangan';

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
                Forms\Components\Section::make('Rincian Komisi Layanan Jasa (AB-001 & AB-005)')
                    ->schema([
                        Forms\Components\TextInput::make('serviceOrder.order_number')
                            ->label('Nomor Pesanan Jasa')
                            ->disabled(),
                        Forms\Components\TextInput::make('technician.name')
                            ->label('Teknisi Pelaksana')
                            ->disabled(),
                        Forms\Components\TextInput::make('gross_amount')
                            ->label('Dasar Perhitungan Komisi Jasa')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('platform_rate')
                            ->label('Tarif Komisi Platform')
                            ->formatStateUsing(fn ($state) => ($state * 100).'%')
                            ->disabled(),
                        Forms\Components\TextInput::make('platform_commission')
                            ->label('Komisi Platform (5%)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('technician_earning')
                            ->label('Hak Penghasilan Teknisi (95%)')
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
                Tables\Columns\TextColumn::make('serviceOrder.order_number')
                    ->label('No. Pesanan Jasa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_amount')
                    ->label('Nilai Dasar Jasa')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('platform_commission')
                    ->label('Komisi (5%)')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('technician_earning')
                    ->label('Hak Teknisi (95%)')
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
                Tables\Filters\SelectFilter::make('technician_id')
                    ->label('Teknisi')
                    ->relationship('technician', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCommissions::route('/'),
            'view' => Pages\ViewServiceCommission::route('/{record}'),
        ];
    }
}
