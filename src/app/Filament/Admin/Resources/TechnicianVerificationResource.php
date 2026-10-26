<?php

namespace App\Filament\Admin\Resources;

use App\Enums\VerificationStatus;
use App\Filament\Admin\Resources\TechnicianVerificationResource\Pages;
use App\Models\TechnicianProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TechnicianVerificationResource extends Resource
{
    protected static ?string $model = TechnicianProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'Verifikasi & Kemitraan';

    protected static ?string $modelLabel = 'Verifikasi Teknisi';

    protected static ?string $pluralModelLabel = 'Verifikasi Teknisi';

    protected static ?int $navigationSort = 1;

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
                Forms\Components\Section::make('Data Teknisi')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengguna Teknisi')
                            ->relationship('user', 'name')
                            ->disabled(),
                        Forms\Components\Select::make('service_category_id')
                            ->label('Kategori Keahlian Utama')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('base_rate')
                            ->label('Tarif Dasar (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        Forms\Components\Select::make('verification_status')
                            ->label('Status Verifikasi')
                            ->options([
                                VerificationStatus::MENUNGGU->value => 'Menunggu',
                                VerificationStatus::DISETUJUI->value => 'Disetujui',
                                VerificationStatus::DITOLAK->value => 'Ditolak',
                                VerificationStatus::DITANGGUHKAN->value => 'Ditangguhkan',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_available')
                            ->label('Tersedia Menerima Pesanan')
                            ->default(true),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Operasional')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('bio')
                            ->label('Profil & Pengalaman')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Teknisi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori Jasa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('base_rate')
                    ->label('Tarif Dasar')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('verification_status')
                    ->label('Status Verifikasi')
                    ->formatStateUsing(fn ($state) => $state instanceof VerificationStatus ? $state->label() : ucfirst($state))
                    ->colors([
                        'warning' => fn ($state) => $state === VerificationStatus::MENUNGGU || $state === 'menunggu',
                        'success' => fn ($state) => $state === VerificationStatus::DISETUJUI || $state === 'disetujui',
                        'danger' => fn ($state) => $state === VerificationStatus::DITOLAK || $state === 'ditolak',
                        'secondary' => fn ($state) => $state === VerificationStatus::DITANGGUHKAN || $state === 'ditangguhkan',
                    ]),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
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
                    ->visible(fn (TechnicianProfile $record) => $record->verification_status !== VerificationStatus::DISETUJUI)
                    ->requiresConfirmation()
                    ->action(function (TechnicianProfile $record) {
                        $record->update(['verification_status' => VerificationStatus::DISETUJUI]);
                        Notification::make()
                            ->title('Teknisi Berhasil Disetujui')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (TechnicianProfile $record) => $record->verification_status !== VerificationStatus::DITOLAK)
                    ->requiresConfirmation()
                    ->action(function (TechnicianProfile $record) {
                        $record->update(['verification_status' => VerificationStatus::DITOLAK]);
                        Notification::make()
                            ->title('Verifikasi Teknisi Ditolak')
                            ->danger()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTechnicianVerifications::route('/'),
            'edit' => Pages\EditTechnicianVerification::route('/{record}/edit'),
        ];
    }
}
