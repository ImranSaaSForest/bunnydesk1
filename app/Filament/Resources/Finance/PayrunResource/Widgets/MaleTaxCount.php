<?php

namespace App\Filament\Resources\Finance\PayrunResource\Widgets;

use App\Models\Employee\Gender;
use App\Models\Payroll\PayrollPolicy;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\PayrunEmployeePayment;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class MaleTaxCount extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'maleTaxCount';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Tax Slab Gender Count';
    protected static ?string $pollingInterval = '2s';
    protected static ?int $contentHeight = 400; //px

    public $payrun;
    public ?string $filter = 'Male';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
// dd($activeFilter);
        $data = [];
        $label = [];
        $payrunId = $this->payrun->id;
        $maleCounts = PayrunEmployeePayment::whereHas('payrunEmployee.user.employee.gender', function ($query) use ($activeFilter){
            $query->where('name', $activeFilter);
        })->whereHas('payrunEmployee.payrun', function ($payrun) use ($payrunId) {
            $payrun->where('id', $payrunId);
        })->get();
        if ($maleCounts) {
            // get Tax slab value
            $PayrollPolicy = PayrollPolicy::where('id', Payrun::find($payrunId)->payroll_policy_id)->with('payrollPolicyAllowance.allowance', 'payrollPolicyDeduction.deduction', 'taxSlab.taxSlabValue')->get();
            if ($PayrollPolicy) {
                $taxSlabValues = $PayrollPolicy[0]->taxSlab->taxSlabValue;
                foreach ($taxSlabValues as $taxSlabValue) {
                    $currentCount = 0;
                    if ($taxSlabValue->cal_range == 'To') {
                        foreach ($maleCounts as $maleCount) {
                            if ($taxSlabValue->start < $maleCount->taxable*12 && $taxSlabValue->end > $maleCount->taxable*12) {
                                $currentCount += 1;
                            }
                        }
                    }
                    elseif($taxSlabValue->cal_range == 'And Above'){
                        foreach ($maleCounts as $maleCount) {
                            if ($taxSlabValue->start < $maleCount->taxable*12 ) {
                                $currentCount += 1;
                            }
                        }
                    }
                    array_push($data, $currentCount);
                    array_push($label, $taxSlabValue->start.' '.$taxSlabValue->cal_range.' '.$taxSlabValue->end);
                }

            }
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'MaleTaxCount',
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
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }
    protected function getFilters(): ?array
    {
        $genders=Gender::all()->pluck('name','name')->toArray();

        return $genders;
        // return [
        //     'previous month' => 'Previous month',
        //     'month' => 'Month',
        //     'next month' => 'Next month',
        //     'past 3 month' => 'Past 3 month',
        //     'past 6 month' => 'Past 6 month',
        //     'year' => 'This year',
        //     'last year' => 'Last year',
        // ];
    }
}
