<?php

namespace App\Filament\Resources\Finance\ReportResource\Widgets;

use App\Models\Employee\Team;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class NoOfRequest extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'noOfRequest';

    /**
     * Widget Title
     *
     * @var string|null
     */

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected static ?string $heading = 'number of requests per team per day';
    public ?string $filter = 'week';
    protected function getOptions(): array
    {
        $activeFilter = $this->filter;

        if($activeFilter=='week'){
            $startDate = now()->subWeek();
        }
        if($activeFilter=='month'){
            $startDate = now()->subMonth();
        }
        $endDate = now();
        $requestCounts =  DB::table('reimbursement_requests')
        ->select('job_infos.team_id',DB::raw('DATE(reimbursement_requests.created_at) as request_date'), DB::raw('COUNT(*) as request_count'))
        ->join('users', 'reimbursement_requests.requested_by', '=', 'users.id')
        ->join('job_infos', 'users.id', '=', 'job_infos.user_id')
        ->whereBetween('reimbursement_requests.created_at',
        [$startDate, $endDate]
    )
        ->groupBy('job_infos.team_id', 'request_date')
        ->orderBy('request_date')
        ->get()->toArray();
        $data=[];
        $label=[];
        if($requestCounts){
        foreach ($requestCounts as $request) {
            array_push($data, $request->request_count);
            array_push($label, Team::where('id',$request->team_id)->pluck('name')[0].' '.$request->request_date);

        }}
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BasicBarChart',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $label,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#DF0334'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last week',
            'month' => 'Last month',
        ];
    }
}
