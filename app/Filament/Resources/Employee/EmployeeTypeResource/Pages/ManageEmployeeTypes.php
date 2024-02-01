<?php

namespace App\Filament\Resources\Employee\EmployeeTypeResource\Pages;

use App\Filament\Resources\Employee\EmployeeTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployeeTypes extends ManageRecords
{
    protected static string $resource = EmployeeTypeResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
