<?php

namespace App\Filament\Admin\Resources\ServiceCommissionResource\Pages;

use App\Filament\Admin\Resources\ServiceCommissionResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceCommissions extends ListRecords
{
    protected static string $resource = ServiceCommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
