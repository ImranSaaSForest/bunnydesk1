<?php

namespace App\Filament\Resources\Finance\ReportResource\Widgets;

use App\Models\Finance\BudgetExpense;
use App\Models\Finance\ReimbursementRequest;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class HighestRequest extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'highestRequest';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Highest Budget Expense Request';

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
        $expenses=ReimbursementRequest::select('budget_expense_id', DB::raw('COUNT(*) as count'))
        ->groupBy('budget_expense_id')
        ->orderByDesc('count')
        ->get();
        if($expenses){

       foreach($expenses as $expense){
        array_push($data,$expense->count);
       $budgetExpense= BudgetExpense::with('expenseType')->find($expense->budget_expense_id);

        array_push($label,$budgetExpense?$budgetExpense->expenseType->name:'');
       }
    }

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
            'colors' => ['#097969'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
