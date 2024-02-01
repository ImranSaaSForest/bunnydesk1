<?php

namespace App\Filament\Resources\TimeOff\HolidayResource\Pages;

use App\Filament\Resources\TimeOff\HolidayResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;

    protected static string $view = 'filament::pages.timeoff-settings';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
