<?php

namespace App\Filament\Resources\Onboarding\OnboardingListResource\Pages;

use App\Filament\Resources\Onboarding\OnboardingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOnboardingList extends CreateRecord
{
    protected static string $resource = OnboardingListResource::class;
   
   
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
