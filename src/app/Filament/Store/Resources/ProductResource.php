<?php

namespace App\Filament\Store\Resources;

use App\Filament\Store\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $modelLabel = 'Produk Material';

    protected static ?string $pluralModelLabel = 'Katalog Produk Toko';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $storeId = auth()->user()?->store?->id;
        if (! $storeId) {
            return null;
        }

        $lowStock = Product::where('store_id', $storeId)->where('stock', '<=', 15)->count();
        if ($lowStock > 0) {
            return "{$lowStock} Stok Tipis";
        }

        $total = Product::where('store_id', $storeId)->count();
        return $total > 0 ? (string) $total : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $storeId = auth()->user()?->store?->id;
        if (! $storeId) {
            return null;
        }

        $lowStock = Product::where('store_id', $storeId)->where('stock', '<=', 15)->count();
        return $lowStock > 0 ? 'warning' : 'gray';
    }

    /**
     * Pembatasan Data Kueri (Data Scoping / Tenant Isolation).
     * Mitra Toko HANYA dapat melihat data produk yang dimiliki oleh tokonya sendiri.
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
                Forms\Components\Grid::make(3)
                    ->schema([
                        // Kolom Kiri: Informasi Utama
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Informasi & Identitas Material')
                                ->description('Nama dan klasifikasi kategori produk material bangunan Anda.')
                                ->icon('heroicon-o-cube')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Produk Material')
                                        ->placeholder('Contoh: Pipa PVC Wavin AW 1/2 Inch 4 Meter')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                    Forms\Components\TextInput::make('slug')
                                        ->label('Slug URL (Otomatis)')
                                        ->disabled()
                                        ->dehydrated()
                                        ->required()
                                        ->maxLength(255),

                                    Forms\Components\Select::make('product_category_id')
                                        ->label('Kategori Material')
                                        ->relationship('category', 'name')
                                        ->placeholder('Pilih kategori material...')
                                        ->searchable()
                                        ->preload()
                                        ->required(),

                                    Forms\Components\Textarea::make('description')
                                        ->label('Deskripsi & Spesifikasi Teknis')
                                        ->placeholder('Tuliskan ukuran, ketebalan, merek, atau ketentuan pemakaian material...')
                                        ->rows(4)
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(['lg' => 2]),

                        // Kolom Kanan: Harga, Stok & Status
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Harga & Manajemen Stok')
                                ->description('Atur harga jual dan sisa ketersediaan unit di gudang toko.')
                                ->icon('heroicon-o-currency-dollar')
                                ->schema([
                                    Forms\Components\TextInput::make('price')
                                        ->label('Harga Satuan')
                                        ->prefix('Rp')
                                        ->numeric()
                                        ->minValue(100)
                                        ->placeholder('0')
                                        ->helperText('Harga bersih per unit material.')
                                        ->required(),

                                    Forms\Components\TextInput::make('stock')
                                        ->label('Stok Tersedia')
                                        ->suffix('Unit')
                                        ->numeric()
                                        ->minValue(0)
                                        ->default(10)
                                        ->helperText('Peringatan kritis otomatis jika stok ≤ 15.')
                                        ->required(),

                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Tampilkan di Marketplace')
                                        ->helperText('Jika nonaktif, produk disembunyikan dari katalog pelanggan.')
                                        ->default(true),
                                ]),
                        ])->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk Material')
                    ->weight('bold')
                    ->icon('heroicon-o-cube')
                    ->iconColor('primary')
                    ->description(fn (Product $record) => Str::limit($record->description ?? 'Belum ada deskripsi material', 45))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-tag')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 15 => 'warning',
                        default => 'success',
                    })
                    ->icon(fn (int $state): string => match (true) {
                        $state <= 0 => 'heroicon-m-x-circle',
                        $state <= 15 => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-check-circle',
                    })
                    ->formatStateUsing(fn (int $state): string => match (true) {
                        $state <= 0 => 'Habis (0 unit)',
                        $state <= 15 => "{$state} unit (Kritis)",
                        default => "{$state} unit (Aman)",
                    })
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Marketplace')
                    ->afterStateUpdated(function (Product $record, $state) {
                        Notification::make()
                            ->title('Status Visibilitas Diperbarui')
                            ->body("Produk '{$record->name}' kini " . ($state ? 'aktif tayang di marketplace.' : 'disembunyikan dari marketplace.'))
                            ->success()
                            ->send();
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->since()
                    ->dateTimeTooltip('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_category_id')
                    ->label('Kategori Material')
                    ->relationship('category', 'name')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Marketplace'),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Hanya Stok Menipis (≤ 15)')
                    ->query(fn (Builder $query) => $query->where('stock', '<=', 15)),
            ])
            ->actions([
                // Quick Stock Adjustment Modal
                Tables\Actions\Action::make('quick_stock')
                    ->label('Restok')
                    ->icon('heroicon-m-arrow-path-rounded-square')
                    ->color('warning')
                    ->modalHeading(fn (Product $record) => "Restok Cepat: {$record->name}")
                    ->modalDescription('Perbarui jumlah stok barang di toko secara instan tanpa perlu membuka form edit lengkap.')
                    ->modalIcon('heroicon-o-cube')
                    ->form([
                        Forms\Components\TextInput::make('stock')
                            ->label('Jumlah Stok Baru (Unit)')
                            ->numeric()
                            ->default(fn (Product $record) => $record->stock)
                            ->minValue(0)
                            ->required(),
                    ])
                    ->action(function (Product $record, array $data) {
                        $oldStock = $record->stock;
                        $record->update(['stock' => $data['stock']]);
                        Notification::make()
                            ->title('Stok Berhasil Diperbarui')
                            ->body("Stok '{$record->name}' telah diperbarui dari {$oldStock} menjadi {$data['stock']} unit.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->color('primary'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->color('danger'),
            ])
            ->emptyStateHeading('Katalog Material Masih Kosong')
            ->emptyStateDescription('Mulai tambahkan daftar material bangunan dan perlengkapan perkakas ke toko Anda.')
            ->emptyStateIcon('heroicon-o-cube')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Produk Pertama')
                    ->icon('heroicon-m-plus-circle'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
