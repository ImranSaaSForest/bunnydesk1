<?php

namespace App\Filament\Resources\Finance\PayrunEmployeeResource\Pages;

use App\Filament\Resources\Finance\PayrunEmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayrunEmployee extends EditRecord
{
    protected static string $resource = PayrunEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
