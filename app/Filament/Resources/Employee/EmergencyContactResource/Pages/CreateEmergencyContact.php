<?php

namespace App\Filament\Resources\Employee\EmergencyContactResource\Pages;

use App\Filament\Resources\Employee\EmergencyContactResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmergencyContact extends CreateRecord
{
    protected static string $resource = EmergencyContactResource::class;
}
