<?php

namespace App\Filament\Resources\Finance\ReportResource\Widgets;

use App\Models\Finance\Budget;
use App\Models\Finance\BudgetExpense;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ExpenseCount extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'expenseCount';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Expense Count';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data=[];
        $expenses=BudgetExpense::all()->groupBy('budget_id');
        if($expenses){

        foreach($expenses as $expense){
            array_push($data,count($expense));
        }
        $label=Budget::pluck('name')->toArray();
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
