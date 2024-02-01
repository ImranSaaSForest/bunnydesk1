<?php

namespace App\Filament\Resources\Timesheet\TimesheetResource\Pages;

use App\Filament\Resources\Timesheet\TimesheetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getActions(): array
    {
        return [
         
        ];
    }
}
