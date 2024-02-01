<?php

namespace App\Filament\Resources\Settings\BudgetResource\Pages;

use App\Filament\Resources\Settings\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgets extends ListRecords
{
    protected static string $resource = BudgetResource::class;
    protected static string $view = 'filament::pages.budget-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
