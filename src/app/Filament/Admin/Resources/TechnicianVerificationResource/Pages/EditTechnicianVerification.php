<?php

namespace App\Filament\Admin\Resources\TechnicianVerificationResource\Pages;

use App\Filament\Admin\Resources\TechnicianVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnicianVerification extends EditRecord
{
    protected static string $resource = TechnicianVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
