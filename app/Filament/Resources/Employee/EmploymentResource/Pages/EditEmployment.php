<?php

namespace App\Filament\Resources\Employee\EmploymentResource\Pages;

use App\Filament\Resources\Employee\EmploymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployment extends EditRecord
{
    protected static string $resource = EmploymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
