<?php

namespace App\Filament\Resources\Settings\OverTimeRateResource\Pages;

use App\Filament\Resources\Settings\OverTimeRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOverTimeRates extends ListRecords
{
    protected static string $resource = OverTimeRateResource::class;

    protected static string $view = 'filament::pages.payroll-settings';



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
