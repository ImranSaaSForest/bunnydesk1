<?php

namespace App\Filament\Resources\Employee\ExperienceResource\Pages;

use App\Filament\Resources\Employee\ExperienceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExperiences extends ListRecords
{
    protected static string $resource = ExperienceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
