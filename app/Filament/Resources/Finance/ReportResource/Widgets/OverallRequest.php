<?php

namespace App\Filament\Resources\Finance\ReportResource\Widgets;
use App\Models\Finance\ReimbursementRequest;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OverallRequest extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'overallRequest';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $pollingInterval = '2s';
    protected static ?int $contentHeight = 400; //px
    protected static ?string $heading = 'Overall Request';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    public ?string $filter = 'month';

    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
        if($activeFilter=='previous month'){
            $startDate = now()->subMonth();
        $endDate = now();

        }
        if($activeFilter=='month'){
            $startDate = now()->month();
        $endDate = now();

        }
        if($activeFilter=='next month'){
            $startDate = now()->addMonth();
        $endDate = now();

        }
        if($activeFilter=='past 3 month'){
            $startDate = now()->subMonth(3);
        $endDate = now();

        }
        if($activeFilter=='past 6 month'){
            $startDate = now()->subMonth(6);
        $endDate = now();

        }
        if($activeFilter=='year'){
            $startDate = now()->year();
        $endDate = now();

        }
        if($activeFilter=='last year'){
            $startDate = now()->subYear();
        $endDate = now();

        }
        $data = [];
        $label = [];
        $requests = ReimbursementRequest::whereBetween('reimbursement_requests.created_at',
        [$startDate, $endDate]
    )->get()->groupBy('status')->toArray();
    if($requests){
        foreach ($requests as $key=>$request)
        {

            array_push($data, count($request));
            array_push($label, $key);


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
    protected function getFilters(): ?array
    {
        return [
            'previous month' => 'Previous month',
            'month' => 'Month',
            'next month' => 'Next month',
            'past 3 month' => 'Past 3 month',
            'past 6 month' => 'Past 6 month',
            'year' => 'This year',
            'last year' => 'Last year',
        ];
    }
}
