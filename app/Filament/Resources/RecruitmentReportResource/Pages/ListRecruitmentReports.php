<?php

namespace App\Filament\Resources\RecruitmentReportResource\Pages;

use App\Filament\Resources\RecruitmentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecruitmentReports extends ListRecords
{
    protected static string $resource = RecruitmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
