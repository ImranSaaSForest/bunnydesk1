<?php

namespace App\Filament\Resources\Settings\TaxSlabResource\Pages;

use App\Filament\Resources\Settings\TaxSlabResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaxSlab extends EditRecord
{
    protected static string $resource = TaxSlabResource::class;
    

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
