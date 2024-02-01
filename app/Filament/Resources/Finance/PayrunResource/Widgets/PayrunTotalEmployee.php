<?php

namespace App\Filament\Resources\Finance\PayrunResource\Widgets;

use App\Models\Employee\Gender;
use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PayrunTotalEmployee extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'payrunTotalEmployee';

    /**
     * Widget Title
     *
     * @var string|null
     */
    public $payrun;

    protected static ?string $heading = 'Payrun Total Employee';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected static ?string $pollingInterval = '10s';
    protected static ?int $contentHeight = 400; //px
    protected function getOptions(): array
    {
        $data=[];
        $label=[];
        $payrunId=$this->payrun->id;

        $genderCounts = Gender::withCount(['employee' => function ($query) use ($payrunId) {
            $query->whereHas('user.payrunEmployee', function ($query) use ($payrunId) {
                $query->where('payrun_id', $payrunId);
            });
        }])->get();
        foreach ($genderCounts as $genderCount) {
            array_push($data, $genderCount->employee_count);
            array_push($label, $genderCount->name);

        }
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => $data,
            'labels' =>$label,
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
