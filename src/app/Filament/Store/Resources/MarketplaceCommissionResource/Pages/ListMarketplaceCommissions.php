<?php

namespace App\Filament\Store\Resources\MarketplaceCommissionResource\Pages;

use App\Filament\Store\Resources\MarketplaceCommissionResource;
use Filament\Resources\Pages\ListRecords;

class ListMarketplaceCommissions extends ListRecords
{
    protected static string $resource = MarketplaceCommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
