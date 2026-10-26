<?php

namespace App\Filament\Admin\Resources\TechnicianWithdrawalResource\Pages;

use App\Filament\Admin\Resources\TechnicianWithdrawalResource;
use App\Models\TechnicianWithdrawal;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTechnicianWithdrawals extends ListRecords
{
    protected static string $resource = TechnicianWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $allCount = TechnicianWithdrawal::count();
        $pendingCount = TechnicianWithdrawal::where('status', 'menunggu')->count();
        $successCount = TechnicianWithdrawal::where('status', 'berhasil')->count();
        $rejectedCount = TechnicianWithdrawal::where('status', 'ditolak')->count();

        return [
            'all' => Tab::make('Semua Pengajuan')
                ->badge($allCount)
                ->badgeColor('gray'),

            'pending' => Tab::make('Menunggu Transfer')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'menunggu'))
                ->badge($pendingCount > 0 ? $pendingCount : null)
                ->badgeColor('warning'),

            'success' => Tab::make('Berhasil Ditransfer')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'berhasil'))
                ->badge($successCount > 0 ? $successCount : null)
                ->badgeColor('success'),

            'rejected' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'ditolak'))
                ->badge($rejectedCount > 0 ? $rejectedCount : null)
                ->badgeColor('danger'),
        ];
    }
}
