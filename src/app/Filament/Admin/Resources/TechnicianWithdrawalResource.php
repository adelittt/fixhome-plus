<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TechnicianWithdrawalResource\Pages;
use App\Models\TechnicianWithdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TechnicianWithdrawalResource extends Resource
{
    protected static ?string $model = TechnicianWithdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'Penarikan Dana Teknisi';

    protected static ?string $pluralModelLabel = 'Penyaluran Dana & Saldo Teknisi';

    protected static ?string $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = TechnicianWithdrawal::where('status', 'menunggu')->count();
        return $count > 0 ? "{$count} Baru" : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
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
                Forms\Components\Section::make('Rincian Permohonan Penarikan Dana')
                    ->schema([
                        Forms\Components\TextInput::make('withdrawal_code')
                            ->label('Kode Penarikan')
                            ->disabled(),
                        Forms\Components\TextInput::make('technician.name')
                            ->label('Nama Mitra Teknisi')
                            ->disabled(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Nominal Penarikan')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->label('Status Saat Ini')
                            ->disabled(),
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank / E-Wallet Tujuan')
                            ->disabled(),
                        Forms\Components\TextInput::make('account_number')
                            ->label('Nomor Rekening / HP')
                            ->disabled(),
                        Forms\Components\TextInput::make('account_holder_name')
                            ->label('Atas Nama Rekening')
                            ->disabled(),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Waktu Pengajuan')
                            ->dateTime('d M Y, H:i')
                            ->disabled(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan dari Teknisi')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin / Referensi Transfer')
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('withdrawal_code')
                    ->label('Kode Permohonan')
                    ->weight('bold')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Nama Teknisi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('technician.phone')
                    ->label('No. Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal Dana')
                    ->money('IDR', locale: 'id')
                    ->weight('black')
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Bank/E-Wallet')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('account_number')
                    ->label('No. Rekening')
                    ->copyable(),

                Tables\Columns\TextColumn::make('account_holder_name')
                    ->label('Atas Nama'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'menunggu',
                        'info' => 'diproses',
                        'success' => 'berhasil',
                        'danger' => 'ditolak',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu' => 'Menunggu Transfer',
                        'diproses' => 'Sedang Ditransfer',
                        'berhasil' => 'Berhasil Ditransfer',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Diajukan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Penarikan')
                    ->options([
                        'menunggu' => 'Menunggu Transfer',
                        'diproses' => 'Sedang Ditransfer',
                        'berhasil' => 'Berhasil Ditransfer',
                        'ditolak' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('bank_name')
                    ->label('Bank / E-Wallet'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),

                // Aksi Transfer Selesai (Setujui)
                Tables\Actions\Action::make('approve')
                    ->label('Setujui & Transfer')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (TechnicianWithdrawal $record) => in_array($record->status, ['menunggu', 'diproses']))
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penyaluran Dana')
                    ->modalDescription(fn (TechnicianWithdrawal $record) => "Konfirmasi bahwa dana sebesar Rp" . number_format($record->amount, 0, ',', '.') . " telah ditransfer ke rekening {$record->bank_name} ({$record->account_number} a.n {$record->account_holder_name}).")
                    ->form([
                        Forms\Components\TextInput::make('admin_notes')
                            ->label('Nomor Referensi Bank / Catatan Pengiriman')
                            ->placeholder('Contoh: TRF-BCA-981729 atau Berhasil via Flip')
                            ->required(),
                    ])
                    ->action(function (TechnicianWithdrawal $record, array $data) {
                        $record->update([
                            'status' => 'berhasil',
                            'admin_notes' => $data['admin_notes'],
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Penarikan Dana Disetujui')
                            ->body("Penyaluran dana #{$record->withdrawal_code} berhasil ditandai selesai.")
                            ->success()
                            ->send();
                    }),

                // Aksi Tolak Penarikan
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (TechnicianWithdrawal $record) => in_array($record->status, ['menunggu', 'diproses']))
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pengajuan Penarikan Dana')
                    ->modalDescription('Saldo teknisi akan otomatis dikembalikan ke saldo dompet tersedianya.')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan alasan penolakan, misalnya: Nomor rekening tidak cocok dengan nama akun bank...')
                            ->required(),
                    ])
                    ->action(function (TechnicianWithdrawal $record, array $data) {
                        $record->update([
                            'status' => 'ditolak',
                            'admin_notes' => $data['admin_notes'],
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Penarikan Dana Ditolak')
                            ->body("Pengajuan #{$record->withdrawal_code} telah ditolak dan saldo dikembalikan ke teknisi.")
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTechnicianWithdrawals::route('/'),
            'view' => Pages\ViewTechnicianWithdrawal::route('/{record}'),
        ];
    }
}
