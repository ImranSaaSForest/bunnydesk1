<?php

namespace App\Filament\Resources\Employee\TeamResource\Pages;

use App\Filament\Resources\Employee\TeamResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected static string $view = 'filament::pages.company-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
