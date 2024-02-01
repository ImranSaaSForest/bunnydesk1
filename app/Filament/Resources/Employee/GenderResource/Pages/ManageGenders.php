<?php

namespace App\Filament\Resources\Employee\GenderResource\Pages;

use App\Filament\Resources\Employee\GenderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGenders extends ManageRecords
{

    protected static string $resource = GenderResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
