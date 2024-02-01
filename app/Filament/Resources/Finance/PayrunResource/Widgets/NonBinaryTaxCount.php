<?php

namespace App\Filament\Resources\Finance\PayrunResource\Widgets;

use App\Models\Payroll\PayrollPolicy;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\PayrunEmployeePayment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class NonBinaryTaxCount extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'nonBinaryTaxCount';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Non Binary Tax Count';
    protected static ?string $pollingInterval = null;
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
        $data = [];
        $label = [];
        $payrunId = $this->payrun->id;
        $femaleCounts = PayrunEmployeePayment::whereHas('payrunEmployee.user.employee.gender', function ($query) {
            $query->where('name', 'Non-binary');
        })->whereHas('payrunEmployee.payrun', function ($payrun) use ($payrunId) {
            $payrun->where('id', $payrunId);
        })->get();
        if ($femaleCounts) {
            // get Tax slab value
            $PayrollPolicy = PayrollPolicy::where('id', Payrun::find($payrunId)->payroll_policy_id)->with('payrollPolicyAllowance.allowance', 'payrollPolicyDeduction.deduction', 'taxSlab.taxSlabValue')->get();
            if ($PayrollPolicy) {
                $taxSlabValues = $PayrollPolicy[0]->taxSlab->taxSlabValue;
                foreach ($taxSlabValues as $taxSlabValue) {
                    $currentCount = 0;
                    if ($taxSlabValue->cal_range == 'To') {
                        foreach ($femaleCounts as $femaleCount) {
                            if ($taxSlabValue->start < $femaleCount->taxable*12 && $taxSlabValue->end > $femaleCount->taxable*12) {
                                $currentCount += 1;
                            }
                        }
                    }
                    elseif($taxSlabValue->cal_range == 'And Above'){
                        foreach ($femaleCounts as $femaleCount) {
                            if ($taxSlabValue->start < $femaleCount->taxable*12 ) {
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
                    'name' => 'NonBinaryTaxCount',
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
}
