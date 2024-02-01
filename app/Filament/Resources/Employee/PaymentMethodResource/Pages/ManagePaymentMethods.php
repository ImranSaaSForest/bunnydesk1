<?php

namespace App\Filament\Resources\Employee\PaymentMethodResource\Pages;

use App\Filament\Resources\Employee\PaymentMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentMethods extends ManageRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
