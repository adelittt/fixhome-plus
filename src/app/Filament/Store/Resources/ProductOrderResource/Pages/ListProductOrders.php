<?php

namespace App\Filament\Store\Resources\ProductOrderResource\Pages;

use App\Filament\Store\Resources\ProductOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListProductOrders extends ListRecords
{
    protected static string $resource = ProductOrderResource::class;
}
