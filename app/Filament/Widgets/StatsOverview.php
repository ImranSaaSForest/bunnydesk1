<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Employee\Employee;
use App\Models\TimeOff\LeaveDate;
use App\Models\Asset\Asset;
use App\Models\HelpDesk\Ticket;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use DB;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $leaveDaysThisMonth = LeaveDate::whereMonth('leave_date', '=', date('m'))
            ->select('day_part', DB::raw('count(*) as total'))
            ->groupBy('day_part')
            ->pluck('total', 'day_part')
            ->all();

        $fullDays = $leaveDaysThisMonth['full'] ?? 0;
        $halfDays = (($leaveDaysThisMonth['morning'] ?? 0) + ($leaveDaysThisMonth['afternoon'] ?? 0)) / 2;

        $totalDays = $fullDays + $halfDays;

        return [
            Card::make('Employees', Employee::count())
                ->description('Total number of employees.'),

            Card::make('Time Off', $totalDays)
                ->description('Days taken this month'),

            Card::make('Assets', Asset::count())
                ->description('Total number of assets.'),

            Card::make('Tickets', Ticket::where('status', 'open')->count())
                ->description('Total number of open tickets.'),

            Card::make('Department', Department::count())
                ->description('Number of departments.'),

            Card::make('Designations', Designation::count())
                ->description('Number of different designations'),
        ];
    }
}
