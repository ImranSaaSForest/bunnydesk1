<?php

namespace App\Filament\Resources\Onboarding\OnboardingListResource\Pages;

use App\Filament\Resources\Onboarding\OnboardingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnboardingLists extends ListRecords
{
    protected static string $resource = OnboardingListResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
           
        ];
    }
}
