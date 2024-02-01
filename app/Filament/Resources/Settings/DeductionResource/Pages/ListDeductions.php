<?php

namespace App\Filament\Resources\Settings\DeductionResource\Pages;

use App\Filament\Resources\Settings\DeductionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeductions extends ListRecords
{
    protected static string $resource = DeductionResource::class;
    protected static string $view = 'filament::pages.payroll-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
