<?php

namespace App\Filament\Admin\Resources\StoreVerificationResource\Pages;

use App\Filament\Admin\Resources\StoreVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreVerification extends EditRecord
{
    protected static string $resource = StoreVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
