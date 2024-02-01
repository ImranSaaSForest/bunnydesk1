<?php

namespace App\Filament\Resources\Settings\TaxSlabResource\Pages;

use App\Filament\Resources\Settings\TaxSlabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaxSlabs extends ListRecords
{
    protected static string $resource = TaxSlabResource::class;
    protected static string $view = 'filament::pages.payroll-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
