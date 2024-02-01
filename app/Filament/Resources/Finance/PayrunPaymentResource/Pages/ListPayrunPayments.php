<?php

namespace App\Filament\Resources\Finance\PayrunPaymentResource\Pages;

use App\Filament\Resources\Finance\PayrunPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayrunPayments extends ListRecords
{
    protected static string $resource = PayrunPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
