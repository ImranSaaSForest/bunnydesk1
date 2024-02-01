<?php

namespace App\Filament\Resources\Settings\PayslipResource\Pages;

use App\Filament\Resources\Settings\PayslipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayslips extends ListRecords
{
    protected static string $resource = PayslipResource::class;
    protected static string $view = 'filament::pages.payroll-settings';
    protected static ?string $title = 'Payslip Template';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Template'),
        ];
    }
}
