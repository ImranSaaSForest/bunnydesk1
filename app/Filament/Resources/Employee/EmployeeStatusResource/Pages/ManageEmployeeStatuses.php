<?php

namespace App\Filament\Resources\Employee\EmployeeStatusResource\Pages;

use App\Filament\Resources\Employee\EmployeeStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployeeStatuses extends ManageRecords
{
    protected static string $resource = EmployeeStatusResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
