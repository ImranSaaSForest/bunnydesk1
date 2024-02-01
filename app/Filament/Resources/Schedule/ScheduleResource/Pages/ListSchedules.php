<?php

namespace App\Filament\Resources\Schedule\ScheduleResource\Pages;

use App\Filament\Resources\Schedule\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
// use App\Filament\Resources\ScheduleResource;
use App\Filament\Resources\Schedule\ScheduleResource\Widgets\CalendarWidget;
// use Filament\Actions;
// use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    // public static string $resource = CustomerResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
    protected function beforeCreate(): void
    {
        // dd('test');
    }
}
