<?php

namespace App\Filament\Resources\Finance\PayrunResource\Widgets;

use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class NetSalaryGenderWise extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'netSalaryGenderWise';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Gender Wise Net Salary';
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
        $netSalaryByGenders = DB::table('genders')
        ->select('genders.name as gender_name', DB::raw('SUM(payrun_employee_payments.net_pay) as total_net_salary'))
        ->join('employees', 'genders.id', '=', 'employees.gender_id')
        ->join('users', 'employees.user_id', '=', 'users.id')
        ->join('payrun_employees', 'users.id', '=', 'payrun_employees.user_id')
        ->where('payrun_employees.payrun_id', $payrunId)
        ->join('payrun_employee_payments', 'payrun_employee_payments.payrun_employee_id', '=', 'payrun_employees.id')

        ->groupBy('genders.id', 'genders.name')
        ->get();
        foreach ($netSalaryByGenders as $netSalaryByGender) {
            array_push($data, $netSalaryByGender->total_net_salary);
            array_push($label, $netSalaryByGender->gender_name);

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
