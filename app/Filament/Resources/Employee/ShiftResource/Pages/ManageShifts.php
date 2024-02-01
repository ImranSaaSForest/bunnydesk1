<?php

namespace App\Filament\Resources\Employee\ShiftResource\Pages;

use App\Filament\Resources\Employee\ShiftResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShifts extends ManageRecords
{
    protected static string $resource = ShiftResource::class;



    protected static string $view = 'filament::pages.company-settings';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(False),
        ];
    }
}
