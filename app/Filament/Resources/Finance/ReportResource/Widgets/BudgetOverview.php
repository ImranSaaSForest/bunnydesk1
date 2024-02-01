<?php

namespace App\Filament\Resources\Finance\ReportResource\Widgets;

use App\Models\Employee\Team;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BudgetOverview extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'budgetOverview';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Budget Overview';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected static ?string $pollingInterval = '2s';
    protected static ?int $contentHeight = 400; //px
    protected function getOptions(): array
    {
        $data=[];
        $label=[];
        $budgetAmounts =  DB::table('reimbursement_requests')
        ->select('job_infos.team_id', DB::raw('SUM(amount) as request_amount'))
        ->join('users', 'reimbursement_requests.requested_by', '=', 'users.id')
        ->join('job_infos', 'users.id', '=', 'job_infos.user_id')
        ->groupBy('job_infos.team_id')
        ->get()->toArray();
        if($budgetAmounts){

        foreach ($budgetAmounts as $budgetAmount) {
            array_push($data, $budgetAmount->request_amount);
            array_push($label, Team::where('id',$budgetAmount->team_id)->pluck('name')[0]);


        }
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
