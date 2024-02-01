<?php

namespace App\Filament\Resources\Settings\DesignationResource\Pages;

use App\Filament\Resources\Settings\DesignationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDesignations extends ManageRecords
{
    protected static string $resource = DesignationResource::class;

    protected static string $view = 'filament::pages.company-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add New Designation')->createAnother(false),
        ];
    }
}
