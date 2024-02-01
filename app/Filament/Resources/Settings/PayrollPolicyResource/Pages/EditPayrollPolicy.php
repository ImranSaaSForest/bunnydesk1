<?php

namespace App\Filament\Resources\Settings\PayrollPolicyResource\Pages;

use App\Filament\Resources\Settings\PayrollPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayrollPolicy extends EditRecord
{
    protected static string $resource = PayrollPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
