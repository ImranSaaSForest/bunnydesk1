<?php

namespace App\Filament\Resources\Finance\BudgetResource\Pages;

use App\Filament\Resources\Finance\BudgetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgets extends ListRecords
{
    protected static string $resource = BudgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement')),
            
        ];
    }
}
