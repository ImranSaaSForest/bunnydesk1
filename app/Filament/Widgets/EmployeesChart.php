<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\User;
use Carbon\Carbon;

class EmployeesChart extends LineChartWidget
{
    protected static ?string $heading = 'Total employees';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $employeeCounts = [];
        $months = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $employeeCount = User::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count();

            array_push($employeeCounts, $employeeCount);
            array_push($months, $date->format('M'));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Employees',
                    'data' => array_reverse($employeeCounts),
                ],
            ],
            'labels' => array_reverse($months),
        ];
    }
}
