<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\TimeOff\Leave;
use Carbon\Carbon;

class LeavesPerMonthChart extends LineChartWidget
{
    protected static ?string $heading = 'TimeOff Per Month';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $months = collect(range(1, 12));
        $leaveCounts = [];

        foreach ($months as $month) {
            $leaves = Leave::whereHas('leaveDates', function ($query) use ($month) {
                                $query->whereMonth('leave_date', $month)
                                      ->whereYear('leave_date', Carbon::now()->year);
                            })
                            ->get();

            $leaveCount = $leaves->sum(function ($leave) {
                return $leave->leaveDates->sum(function ($leaveDate) {
                    return $leaveDate->day_part === 'full' ? 1 : 0.5;
                });
            });

            array_push($leaveCounts, $leaveCount);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Leaves',
                    'data' => $leaveCounts,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Adjust as needed
                    'borderColor' => 'rgba(75, 192, 192, 1)', // Adjust as needed
                    'borderWidth' => 1,
                    'fill' => false,
                ],
            ],
            'labels' => $months->map(function ($month) {
                return Carbon::create()->month($month)->format('F');
            })->toArray(),
        ];
    }
}
