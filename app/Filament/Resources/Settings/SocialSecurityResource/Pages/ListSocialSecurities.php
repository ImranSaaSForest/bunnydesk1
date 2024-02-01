<?php

namespace App\Filament\Resources\Settings\SocialSecurityResource\Pages;

use App\Filament\Resources\Settings\SocialSecurityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialSecurities extends ListRecords
{
    protected static string $resource = SocialSecurityResource::class;
    protected static string $view = 'filament::pages.payroll-settings';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
