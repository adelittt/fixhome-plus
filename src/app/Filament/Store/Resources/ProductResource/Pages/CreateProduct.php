<?php

namespace App\Filament\Store\Resources\ProductResource\Pages;

use App\Filament\Store\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['store_id'] = auth()->user()->store->id;

        return $data;
    }
}
