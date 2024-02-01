<?php

namespace App\Filament\Resources\Settings\PayrollPolicyResource\Pages;

use App\Filament\Resources\Settings\PayrollPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayrollPolicies extends ListRecords
{
    protected static string $resource = PayrollPolicyResource::class;

    protected static string $view = 'filament::pages.payroll-settings';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
