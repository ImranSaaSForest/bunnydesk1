<?php

namespace App\Filament\Resources\Finance\PayrunResource\Widgets;

use App\Models\Employee\Team;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class DepartmentWiseNetSalary extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'departmentWiseNetSalary';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Department Wise Net Salary';
    protected static ?string $pollingInterval = '10s';
    protected static ?int $contentHeight = 400; //px
    public $payrun;

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data=[];
        $label=[];
        $payrunId=$this->payrun->id;

        $teamNetSalaries = DB::table('teams')
        ->select('teams.name as team_name', DB::raw('SUM(payrun_employee_payments.net_pay) as total_net_salary'))
        ->join('job_infos', 'teams.id', '=', 'job_infos.team_id')
        // ->join('employees', 'job_infos.employee_id', '=', 'employees.id')
        ->join('users', 'job_infos.user_id', '=', 'users.id')
        ->join('payrun_employees', 'users.id', '=', 'payrun_employees.user_id')
        ->where('payrun_employees.payrun_id', $payrunId)
        ->join('payrun_employee_payments', 'payrun_employee_payments.payrun_employee_id', '=', 'payrun_employees.id')
        ->groupBy('teams.id', 'teams.name')
        ->get();

        foreach ($teamNetSalaries as $teamNetSalary) {
            array_push($data, $teamNetSalary->total_net_salary);
            array_push($label, $teamNetSalary->team_name);

        }
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => $data,
            'labels' => $label,
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
