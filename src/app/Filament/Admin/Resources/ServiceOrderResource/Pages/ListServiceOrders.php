<?php

namespace App\Filament\Admin\Resources\ServiceOrderResource\Pages;

use App\Filament\Admin\Resources\ServiceOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceOrders extends ListRecords
{
    protected static string $resource = ServiceOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
