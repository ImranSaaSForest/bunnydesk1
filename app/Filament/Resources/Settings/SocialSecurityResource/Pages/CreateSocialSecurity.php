<?php

namespace App\Filament\Resources\Settings\SocialSecurityResource\Pages;

use App\Filament\Resources\Settings\SocialSecurityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSocialSecurity extends CreateRecord
{
    protected static string $resource = SocialSecurityResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
