<?php

namespace App\Filament\Resources\TimeOff\HolidayResource\Pages;

use App\Filament\Resources\TimeOff\HolidayResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;
}
