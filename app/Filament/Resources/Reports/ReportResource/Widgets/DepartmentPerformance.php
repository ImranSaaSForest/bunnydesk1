<?php

namespace App\Filament\Resources\Reports\ReportResource\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\User;
use App\Models\Company\Department;
use Carbon\Carbon;

class DepartmentPerformance extends BarChartWidget
{
    protected static ?string $heading = 'Performance Score by Department';

    protected int | string | array $columnSpan = 'full';


    protected static ?int $sort = 1;
    private function randomColor()
    {
        return 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.6)';
    }
    protected function getData(): array
    {
        $departments = Department::with(['designations.jobInfo.user.performanceGoals'])->get();

        $data = $departments->map(function ($department) {
            $totalScore = 0;

            foreach ($department->designations as $designation) {
                foreach ($designation->jobInfo as $jobInfo) {
                    $user = $jobInfo->user;
                    // Filter out null rating_scores before summing
                    $totalScore += collect($user->performanceGoals)->filter(function ($performanceGoal) {
                        return !is_null($performanceGoal['rating_score']);
                    })->sum('rating_score');
                }
            }

            return [
                'label' => $department->name,
                'data' => [$totalScore],
            ];
        })->toArray();

        return [
            'datasets' => collect($data)->map(function ($item) {
                return [
                    'label' => $item['label'],
                    'data' => $item['data'],
                    'backgroundColor' => $this->randomColor(),
                ];
            }),
            'labels' => ['Performance Score'],
        ];
    }


}
