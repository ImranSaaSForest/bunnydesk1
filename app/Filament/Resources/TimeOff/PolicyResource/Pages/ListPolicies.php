<?php

namespace App\Filament\Resources\TimeOff\PolicyResource\Pages;

use App\Filament\Resources\TimeOff\PolicyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPolicies extends ListRecords
{
    protected static string $resource = PolicyResource::class;

    protected static string $view = 'filament::pages.timeoff-settings';


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
