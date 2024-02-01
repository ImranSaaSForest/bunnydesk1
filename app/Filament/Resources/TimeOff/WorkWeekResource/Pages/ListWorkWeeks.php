<?php

namespace App\Filament\Resources\TimeOff\WorkWeekResource\Pages;

use App\Filament\Resources\TimeOff\WorkWeekResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkWeeks extends ListRecords
{
    protected static string $resource = WorkWeekResource::class;


    protected static string $view = 'filament::pages.timeoff-settings';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
