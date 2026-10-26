<?php

namespace App\Filament\Admin\Resources\TechnicianWithdrawalResource\Pages;

use App\Filament\Admin\Resources\TechnicianWithdrawalResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTechnicianWithdrawal extends ViewRecord
{
    protected static string $resource = TechnicianWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
