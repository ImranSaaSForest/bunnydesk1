<?php

namespace App\Filament\Resources\Reports\ReportResource\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReports extends ManageRecords
{
    protected static string $resource = ReportResource::class;
    protected static string $view = 'filament.pages.report';

    protected function getHeaderWidgets(): array
    {
        return [
            ReportResource\Widgets\DepartmentPerformance::class,
            // ReportResource\Widgets\Onboarding::class,
            ReportResource\Widgets\FemaleLeadership::class,
        ];
    }



    

    protected function getActions(): array
    {
        return [
        ];
    }
}
