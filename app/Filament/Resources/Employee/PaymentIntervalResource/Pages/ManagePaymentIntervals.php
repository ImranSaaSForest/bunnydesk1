<?php

namespace App\Filament\Resources\Employee\PaymentIntervalResource\Pages;

use App\Filament\Resources\Employee\PaymentIntervalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentIntervals extends ManageRecords
{
    protected static string $resource = PaymentIntervalResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
