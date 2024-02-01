<?php

namespace App\Filament\Resources\Settings\ExpenseTypeResource\Pages;

use App\Filament\Resources\Settings\ExpenseTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseTypes extends ListRecords
{
    protected static string $resource = ExpenseTypeResource::class;
    protected static string $view = 'filament::pages.budget-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
