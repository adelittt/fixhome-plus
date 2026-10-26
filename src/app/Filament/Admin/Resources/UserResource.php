<?php

namespace App\Filament\Admin\Resources;

use App\Enums\UserRole;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Daftar Pengguna';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone', 'role'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->required()
                            ->prefixIcon('heroicon-m-envelope')
                            ->email(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon / WhatsApp')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('role')
                            ->label('Peran (Role)')
                            ->options([
                                UserRole::PELANGGAN->value => 'Pelanggan',
                                UserRole::TEKNISI->value => 'Teknisi',
                                UserRole::MITRA_TOKO->value => 'Mitra Toko',
                                UserRole::ADMIN->value => 'Administrator',
                            ])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status Akun')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                                'ditangguhkan' => 'Ditangguhkan',
                            ])
                            ->default('aktif')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Keamanan Kata Sandi')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->confirmed()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->password(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('role')
                    ->label('Peran')
                    ->formatStateUsing(fn ($state) => $state instanceof UserRole ? $state->label() : ucfirst($state))
                    ->colors([
                        'primary' => fn ($state) => $state === UserRole::PELANGGAN || $state === 'pelanggan',
                        'warning' => fn ($state) => $state === UserRole::TEKNISI || $state === 'teknisi',
                        'success' => fn ($state) => $state === UserRole::MITRA_TOKO || $state === 'mitra_toko',
                        'danger' => fn ($state) => $state === UserRole::ADMIN || $state === 'admin',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                        'warning' => 'ditangguhkan',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Filter Peran')
                    ->options([
                        'pelanggan' => 'Pelanggan',
                        'teknisi' => 'Teknisi',
                        'mitra_toko' => 'Mitra Toko',
                        'admin' => 'Admin',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
