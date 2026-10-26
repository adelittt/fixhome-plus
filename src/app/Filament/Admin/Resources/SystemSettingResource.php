<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SystemSettingResource\Pages;
use App\Models\SystemSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SystemSettingResource extends Resource
{
    protected static ?string $model = SystemSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $modelLabel = 'Pengaturan Sistem';

    protected static ?string $pluralModelLabel = 'Pengaturan Sistem (PK-15)';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konfigurasi Parameter Sistem')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Kunci Pengaturan (Key)')
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Data')
                            ->options([
                                'boolean' => 'Boolean (true / false)',
                                'integer' => 'Integer (Angka Bulat)',
                                'float' => 'Float (Desimal)',
                                'string' => 'String (Teks)',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('value')
                            ->label('Nilai Pengaturan (Value)')
                            ->helperText('Contoh boolean: true atau false. Integer: 10000.')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Fungsi & Ketentuan PRD/BRD')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Kunci Parameter')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai Aktif')
                    ->badge()
                    ->color(fn ($state) => $state === 'true' ? 'success' : ($state === 'false' ? 'gray' : 'primary')),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(60),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemSettings::route('/'),
            'edit' => Pages\EditSystemSetting::route('/{record}/edit'),
        ];
    }
}
