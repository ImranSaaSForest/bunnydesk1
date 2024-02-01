<?php

namespace App\Filament\Resources\Offboarding\OffboardingListResource\Pages;

use App\Filament\Resources\Offboarding\OffboardingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOffboardingLists extends ListRecords
{
    protected static string $resource = OffboardingListResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }
}
