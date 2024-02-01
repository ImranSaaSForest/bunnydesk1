<?php

namespace App\Filament\Resources\Finance\ReportResource\Pages;

use App\Filament\Resources\Finance\ReportResource;
use App\Filament\Resources\Finance\ReportResource\Widgets\BudgetOverview as WidgetsBudgetOverviews;
use App\Filament\Resources\Finance\ReportResource\Widgets\HighestRequest;
use App\Filament\Resources\Finance\ReportResource\Widgets\BudgetValue;
use App\Filament\Resources\Finance\ReportResource\Widgets\OverallRequest;
use App\Filament\Resources\Finance\ReportResource\Widgets\ExpenseCount;
use App\Filament\Resources\Finance\ReportResource\Widgets\NoOfRequest;

use Filament\Resources\Pages\Page;

class BudgetOverview extends Page
{
    protected static string $resource = ReportResource::class;

    protected static string $view = 'filament.resources.finance.report-resource.pages.budget-overview';
    protected function getHeaderWidgets(): array    {
        return [
        WidgetsBudgetOverviews::class,
        HighestRequest::class,
        BudgetValue::class,
           OverallRequest::class,
           ExpenseCount::class,
           NoOfRequest::class
        ];
    }

}
