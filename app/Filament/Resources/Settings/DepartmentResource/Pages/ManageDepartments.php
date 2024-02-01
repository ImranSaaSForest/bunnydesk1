<?php

namespace App\Filament\Resources\Settings\DepartmentResource\Pages;

use App\Filament\Resources\Settings\DepartmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDepartments extends ManageRecords
{
    protected static string $resource = DepartmentResource::class;

    protected static string $view = 'filament::pages.company-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add New Department')->createAnother(false),
        ];
    }
}
