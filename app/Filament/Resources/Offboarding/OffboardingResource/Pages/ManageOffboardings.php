<?php

namespace App\Filament\Resources\Offboarding\OffboardingResource\Pages;

use App\Filament\Resources\Offboarding\OffboardingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOffboardings extends ManageRecords
{
    protected static string $resource = OffboardingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Assign Offboarding')
        ];
    }
}
