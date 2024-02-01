<?php

namespace App\Filament\Resources\Performance\PerformanceGoalResource\Pages;

use App\Filament\Resources\Performance\PerformanceGoalResource;
use Filament\Actions;
use Filament\Actions\CreateAction as ActionsCreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;

class ListPerformanceGoals extends ListRecords
{
    protected static string $resource = PerformanceGoalResource::class;
    protected static ?string $title = 'Goal';
}
