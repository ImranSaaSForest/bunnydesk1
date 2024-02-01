<?php

namespace App\Filament\Resources\Onboarding\OnboardingListResource\Pages;

use App\Filament\Resources\Onboarding\OnboardingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOnboardingList extends EditRecord
{
    protected static string $resource = OnboardingListResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
