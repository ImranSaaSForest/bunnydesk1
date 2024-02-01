<?php

namespace App\Filament\Resources\Timesheet\ProjectDocResource\Pages;

use App\Filament\Resources\Timesheet\ProjectDocResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectDoc extends EditRecord
{
    protected static string $resource = ProjectDocResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
