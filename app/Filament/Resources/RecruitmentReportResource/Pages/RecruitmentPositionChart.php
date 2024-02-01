<?php

// namespace App\Filament\Widgets;
namespace App\Filament\Resources\RecruitmentReportResource\Pages;

use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;

class RecruitmentPositionChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'recruitmentPositionChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Candidates Status Reports';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    public ?string $filter = 'Choose Position';

    public $company;

    #[On('post-created1')]
    public function updatePositionList($companyId)
    {
        // dd($companyId);
        $this->company = $companyId;
    }

    protected function getFilters(): ?array
    {
        $jobs = Job::pluck('title', 'id')->toArray();
        array_unshift($jobs, "Select an Option");
        // $jobs = array_combine(range(1, count($jobs2)), array_values($jobs2));
        // dd($jobs);
        return
        // foreach($jobs as $job) {
            $jobs;
            // }
    }
    public $months;

    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
        $this->months=[];
        $now = Carbon::now();
        for ($i = 5; $i >= 0; $i--) {
            $this->months[] = $now->copy()->subMonths($i)->format('M');
        }
        // $months = array_reverse($this->months);
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $positionData = Candidate::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as count')
        )
            ->where('company_id', $this->company)
            ->where('job_id', $activeFilter)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            // ->pluck('count')
            ->toArray();
            $data = [];

            if (count($positionData) > 0) {
                for ($i = 5; $i >= 0; $i--) {

                foreach ($positionData as $monthCount) {

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
                'width'=> 1000,
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
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#66DA26'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
