<?php

namespace App\Filament\Resources\TimeOff\LeaveTypeResource\Pages;

use App\Filament\Resources\TimeOff\LeaveTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLeaveTypes extends ManageRecords
{
    protected static string $resource = LeaveTypeResource::class;

    protected static string $view = 'filament::pages.timeoff-settings';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(False),
        ];
    }
}
