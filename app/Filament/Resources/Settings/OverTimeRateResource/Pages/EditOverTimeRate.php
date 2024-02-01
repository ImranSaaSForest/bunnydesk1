<?php

namespace App\Filament\Resources\Settings\OverTimeRateResource\Pages;

use App\Filament\Resources\Settings\OverTimeRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOverTimeRate extends EditRecord
{
    protected static string $resource = OverTimeRateResource::class;

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
