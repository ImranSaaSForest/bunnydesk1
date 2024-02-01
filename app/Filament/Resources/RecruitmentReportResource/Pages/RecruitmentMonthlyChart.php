<?php

// namespace App\Filament\Widgets;
namespace App\Filament\Resources\RecruitmentReportResource\Pages;

use App\Models\Recruitment\Candidate;
use YourModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;

class RecruitmentMonthlyChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'recruitmentMonthlyChart';


    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Offer Overview for Last 6 Months';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    // protected static ?string $pollingInterval = '1s';

    public $company;
    public $status;
    public $months = [];

    #[On('post-created1')]
    public function updateCompanyList($companyId)
    {
        // dd($companyId);
        $this->company = $companyId;
    }
    #[On('post-created2')]
    public function updateStatusList($state)
    {
        $this->status = $state;
    }


    protected function getOptions(): array
    {
        $this->months = [];
        $now = Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $this->months[] = $now->copy()->subMonths($i)->format('M');
        }
        // dd($this->months);
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $candidateData = Candidate::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as count'),
        )
            ->where('company_id', $this->company)
            ->where('status', $this->status)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            // ->pluck('count')
            ->toArray();
        $data=[];
        // dd($candidateData);
            if (count($candidateData) > 0) {
                for ($i = 5; $i >= 0; $i--) {

                foreach ($candidateData as $monthCount) {

                    if ($now->copy()->subMonths($i)->format('n') == $monthCount['month']) {
                        $Countdata = $monthCount['count'];
                        break;
                    } else {
                        $Countdata = 0;

                    }
                }
                $data[]=$Countdata;
            }

        }

        return [

            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'width' => 500,

            ],
            'series' => [
                [
                    'name' => 'BasicBarChart',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $this->months,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'categories' => [0, 5, 10, 15, 20, 25, 30],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#66DA26'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 5,
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
