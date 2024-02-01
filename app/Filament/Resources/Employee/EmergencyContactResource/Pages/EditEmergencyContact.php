<?php

namespace App\Filament\Resources\Employee\EmergencyContactResource\Pages;

use App\Filament\Resources\Employee\EmergencyContactResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmergencyContact extends EditRecord
{
    protected static string $resource = EmergencyContactResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
