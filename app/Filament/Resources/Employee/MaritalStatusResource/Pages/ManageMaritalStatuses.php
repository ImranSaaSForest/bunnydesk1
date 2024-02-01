<?php

namespace App\Filament\Resources\Employee\MaritalStatusResource\Pages;

use App\Filament\Resources\Employee\MaritalStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMaritalStatuses extends ManageRecords
{
    protected static string $resource = MaritalStatusResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
