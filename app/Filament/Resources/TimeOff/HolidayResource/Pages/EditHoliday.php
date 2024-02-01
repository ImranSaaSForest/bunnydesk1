<?php

namespace App\Filament\Resources\TimeOff\HolidayResource\Pages;

use App\Filament\Resources\TimeOff\HolidayResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
