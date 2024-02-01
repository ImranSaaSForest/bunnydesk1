<?php

namespace App\Filament\Resources\TimeOff\PolicyFrequencyResource\Pages;

use App\Filament\Resources\TimeOff\PolicyFrequencyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePolicyFrequencies extends ManageRecords
{
    protected static string $resource = PolicyFrequencyResource::class;

    protected static string $view = 'filament::pages.timeoff-settings';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
