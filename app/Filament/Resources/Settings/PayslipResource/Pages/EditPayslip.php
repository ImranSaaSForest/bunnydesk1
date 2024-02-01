<?php

namespace App\Filament\Resources\Settings\PayslipResource\Pages;

use App\Filament\Resources\Settings\PayslipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayslip extends EditRecord
{
    protected static string $resource = PayslipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
