<?php

namespace App\Filament\Resources\Timesheet\ProjectDocResource\Pages;

use App\Filament\Resources\Timesheet\ProjectDocResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectDocs extends ListRecords
{
    protected static string $resource = ProjectDocResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
