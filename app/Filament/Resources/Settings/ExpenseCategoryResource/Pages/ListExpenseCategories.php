<?php

namespace App\Filament\Resources\Settings\ExpenseCategoryResource\Pages;

use App\Filament\Resources\Settings\ExpenseCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseCategories extends ListRecords
{
    protected static string $resource = ExpenseCategoryResource::class;
    protected static string $view = 'filament::pages.budget-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
