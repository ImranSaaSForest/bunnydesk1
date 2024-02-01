<?php

namespace App\Filament\Resources\DailyUpdate\DateResource\Pages;

use App\Filament\Resources\DailyUpdate\DateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDates extends ListRecords
{
    protected static string $resource = DateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
