<?php

namespace App\Filament\Resources\Settings\AllowanceResource\Pages;

use App\Filament\Resources\Settings\AllowanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAllowance extends CreateRecord
{
    protected static string $resource = AllowanceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
