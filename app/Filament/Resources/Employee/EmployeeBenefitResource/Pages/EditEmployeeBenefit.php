<?php

namespace App\Filament\Resources\Employee\EmployeeBenefitResource\Pages;

use App\Filament\Resources\Employee\EmployeeBenefitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeBenefit extends EditRecord
{
    protected static string $resource = EmployeeBenefitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
