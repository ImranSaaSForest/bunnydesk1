<?php

namespace App\Filament\Resources\Settings\RecruitmentResource\Pages;

use App\Filament\Resources\Settings\RecruitmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecruitments extends ListRecords
{
    protected static string $resource = RecruitmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
