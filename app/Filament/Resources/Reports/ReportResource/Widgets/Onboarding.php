<?php

namespace App\Filament\Resources\Reports\ReportResource\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Onboarding\EmployeeOnboarding;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Onboarding extends LineChartWidget
{
    protected static ?string $heading = 'Monthly Onboarding Employees';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $onboardingCounts = [];
        $months = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $onboardingCount = EmployeeOnboarding::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->select(DB::raw('count(distinct user_id) as user_count'))
                                ->first()
                                ->user_count;

            array_push($onboardingCounts, $onboardingCount);
            array_push($months, $date->format('M'));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Onboardings',
                    'data' => array_reverse($onboardingCounts),
                ],
            ],
            'labels' => array_reverse($months),
        ];
    }
}
