<?php

namespace App\Filament\Admin\Resources\ProductOrderResource\Pages;

use App\Filament\Admin\Resources\ProductOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListProductOrders extends ListRecords
{
    protected static string $resource = ProductOrderResource::class;
}
