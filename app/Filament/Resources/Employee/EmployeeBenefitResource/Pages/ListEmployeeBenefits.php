<?php

namespace App\Filament\Resources\Employee\EmployeeBenefitResource\Pages;

use App\Filament\Resources\Employee\EmployeeBenefitResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeBenefits extends ListRecords
{
    protected static string $resource = EmployeeBenefitResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
