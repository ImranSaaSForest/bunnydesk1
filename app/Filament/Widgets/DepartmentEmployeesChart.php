<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\User;
use App\Models\Company\Department;
use Carbon\Carbon;

class DepartmentEmployeesChart extends BarChartWidget
{
    protected static ?string $heading = 'New Employees by Department';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $departments = Department::all();
        $employeeCounts = [];

        foreach ($departments as $department) {
            $employeeCount = User::whereHas('jobInfo.designation', function($query) use ($department) {
                                $query->where('department_id', $department->id)
                                      ->whereYear('created_at', Carbon::now()->year);
                            })
                            ->count();

            array_push($employeeCounts, $employeeCount);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Employees',
                    'data' => $employeeCounts,
                ],
            ],
            'labels' => $departments->pluck('name')->toArray(),
        ];
    }
}
