<?php

namespace App\Filament\Resources\Settings\OverTimeRateResource\Pages;

use App\Filament\Resources\Settings\OverTimeRateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOverTimeRate extends CreateRecord
{
    protected static string $resource = OverTimeRateResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
