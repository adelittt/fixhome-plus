<?php

namespace App\Filament\Admin\Resources;

use App\Enums\VerificationStatus;
use App\Filament\Admin\Resources\StoreVerificationResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoreVerificationResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Verifikasi & Kemitraan';

    protected static ?string $modelLabel = 'Verifikasi Mitra Toko';

    protected static ?string $pluralModelLabel = 'Verifikasi Mitra Toko';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::where('verification_status', VerificationStatus::MENUNGGU)->count();
        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mitra Toko')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik Akun')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Toko')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug Toko')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon Toko')
                            ->tel(),
                        Forms\Components\Select::make('verification_status')
                            ->label('Status Verifikasi')
                            ->options([
                                VerificationStatus::MENUNGGU->value => 'Menunggu',
                                VerificationStatus::DISETUJUI->value => 'Disetujui',
                                VerificationStatus::DITOLAK->value => 'Ditolak',
                                VerificationStatus::DITANGGUHKAN->value => 'Ditangguhkan',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Toko Aktif')
                            ->default(true),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Fisik Toko')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Toko')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Jumlah Produk'),
                Tables\Columns\BadgeColumn::make('verification_status')
                    ->label('Status Verifikasi')
                    ->formatStateUsing(fn ($state) => $state instanceof VerificationStatus ? $state->label() : ucfirst($state))
                    ->colors([
                        'warning' => fn ($state) => $state === VerificationStatus::MENUNGGU || $state === 'menunggu',
                        'success' => fn ($state) => $state === VerificationStatus::DISETUJUI || $state === 'disetujui',
                        'danger' => fn ($state) => $state === VerificationStatus::DITOLAK || $state === 'ditolak',
                        'secondary' => fn ($state) => $state === VerificationStatus::DITANGGUHKAN || $state === 'ditangguhkan',
                    ]),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'ditangguhkan' => 'Ditangguhkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('setujui')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Store $record) => $record->verification_status !== VerificationStatus::DISETUJUI)
                    ->requiresConfirmation()
                    ->action(function (Store $record) {
                        $record->update(['verification_status' => VerificationStatus::DISETUJUI]);
                        Notification::make()
                            ->title('Mitra Toko Berhasil Disetujui')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Store $record) => $record->verification_status !== VerificationStatus::DITOLAK)
                    ->requiresConfirmation()
                    ->action(function (Store $record) {
                        $record->update(['verification_status' => VerificationStatus::DITOLAK]);
                        Notification::make()
                            ->title('Verifikasi Mitra Toko Ditolak')
                            ->danger()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoreVerifications::route('/'),
            'edit' => Pages\EditStoreVerification::route('/{record}/edit'),
        ];
    }
}
