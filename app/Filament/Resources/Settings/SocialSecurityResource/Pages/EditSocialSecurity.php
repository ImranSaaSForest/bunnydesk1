<?php

namespace App\Filament\Resources\Settings\SocialSecurityResource\Pages;

use App\Filament\Resources\Settings\SocialSecurityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialSecurity extends EditRecord
{
    protected static string $resource = SocialSecurityResource::class;

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
