<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyLearnings extends ListRecords
{
    protected static string $resource = MyLearningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
