<?php

namespace App\Filament\Resources\Offboarding\OffboardingListResource\Pages;

use App\Filament\Resources\Offboarding\OffboardingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOffboardingList extends CreateRecord
{
    protected static string $resource = OffboardingListResource::class;

   

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
