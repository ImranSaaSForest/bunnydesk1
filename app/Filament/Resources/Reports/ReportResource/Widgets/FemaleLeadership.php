<?php

namespace App\Filament\Resources\Reports\ReportResource\Widgets;

use Filament\Widgets\PieChartWidget;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FemaleLeadership extends PieChartWidget
{
    protected static ?string $heading = 'Total Females in Leadership Roles';

    protected static ?int $sort = 3;


    protected function getData(): array
    {
        $femaleSupervisorsCount = User::whereHas('employee', function ($query) {
            $query->whereHas('gender', function ($subQuery) {
                $subQuery->where('name', 'female');
            });
        })->whereHas('supervisorJobInfo')->count();

        return [
            'datasets' => [[
                'data' => [$femaleSupervisorsCount],
                'backgroundColor' => ['#FF6384'],
            ]],
            'labels' => ['Female Leaders'],
        ];
    }
}
