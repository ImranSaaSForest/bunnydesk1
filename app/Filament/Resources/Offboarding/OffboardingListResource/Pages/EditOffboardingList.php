<?php

namespace App\Filament\Resources\Offboarding\OffboardingListResource\Pages;

use App\Filament\Resources\Offboarding\OffboardingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOffboardingList extends EditRecord
{
    protected static string $resource = OffboardingListResource::class;

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
