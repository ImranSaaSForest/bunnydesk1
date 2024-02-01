<?php

namespace App\Filament\Resources\Finance\ReportResource\Widgets;

use App\Models\Finance\BudgetExpense;
use App\Models\Finance\ExpenseType;
use App\Models\Finance\ReimbursementRequest;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BudgetValue extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'budgetValue';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Budget Value high to low based on expense';

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
        $expenses=ReimbursementRequest::all()->sortByDesc('amount')->groupBy('budget_expense_id');
        if($expenses){
            foreach($expenses as $expense){

         $budgetExpense=BudgetExpense::find($expense[0]->budget_expense_id);

         if($budgetExpense){
            $expenseType=ExpenseType::find($budgetExpense->expense_type_id);


            array_push($data,$expense->sum('amount'));
            array_push($label,$expenseType->name);
        }
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
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
        ];
    }
}
