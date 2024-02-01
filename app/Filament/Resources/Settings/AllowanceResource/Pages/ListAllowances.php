<?php

namespace App\Filament\Resources\Settings\AllowanceResource\Pages;

use App\Filament\Resources\Settings\AllowanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllowances extends ListRecords
{
    protected static string $resource = AllowanceResource::class;
    protected static string $view = 'filament::pages.payroll-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
