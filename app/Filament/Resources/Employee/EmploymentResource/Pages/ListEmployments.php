<?php

namespace App\Filament\Resources\Employee\EmploymentResource\Pages;

use App\Filament\Resources\Employee\EmploymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployments extends ListRecords
{
    protected static string $resource = EmploymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
