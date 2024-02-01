<?php

namespace App\Filament\Widgets;

use Filament\Widgets\DoughnutChartWidget;
use App\Models\TimeOff\Leave;
use App\Models\TimeOff\LeaveDate;
use App\Models\TimeOff\PolicyLeaveType;
use App\Models\TimeOff\LeaveType;
use Carbon\Carbon;

class LeaveTypeChart extends DoughnutChartWidget
{
    protected static ?string $heading = 'Timeoff by Timeoff Type';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $leaveTypes = LeaveType::all();
        $leaveCounts = [];

        foreach ($leaveTypes as $leaveType) {
            $leaveCount = Leave::where('policy_leave_type_id', $leaveType->id)
            ->whereHas('leaveDates', function ($query) {
                $query->whereYear('leave_date', Carbon::now()->year);
            })
            ->with('leaveDates') // Eager load leaveDates to avoid n+1 problem
            ->get() // Get all matching Leave objects
            ->sum(function ($leave) {
                // For each Leave, sum the day_parts (0.5 for 'half', 1 for 'full')
                return $leave->leaveDates->sum(function ($leaveDate) {
                    return $leaveDate->day_part === 'full' ? 1 : 0.5;
                });
            });


            array_push($leaveCounts, $leaveCount);
        }

        if(array_sum($leaveCounts) == 0) {
            return [
                'noDataMessage' => 'No leave data available for this year.'
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => $leaveCounts,
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'], // Adjust this as needed
                ],
            ],
            'labels' => $leaveTypes->pluck('name')->toArray(),
        ];
    }
}
