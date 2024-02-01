<?php

namespace App\Filament\Resources\Employee\RelationResource\Pages;

use App\Filament\Resources\Employee\RelationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRelations extends ManageRecords
{
    protected static string $resource = RelationResource::class;

    protected static string $view = 'filament::pages.employee-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
