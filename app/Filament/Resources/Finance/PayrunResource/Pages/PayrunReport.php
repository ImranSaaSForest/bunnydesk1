<?php

namespace App\Filament\Resources\Finance\PayrunResource\Pages;

use App\Filament\Resources\Finance\PayrunResource;
use App\Filament\Resources\Finance\PayrunResource\Widgets\DepartmentWiseNetSalary;
use App\Filament\Resources\Finance\PayrunResource\Widgets\FemaleTaxCount;
use App\Filament\Resources\Finance\PayrunResource\Widgets\MaleTaxCount;
use App\Filament\Resources\Finance\PayrunResource\Widgets\NetSalaryGenderWise;
use App\Filament\Resources\Finance\PayrunResource\Widgets\NonBinaryTaxCount;
use App\Filament\Resources\Finance\PayrunResource\Widgets\PayrunStatsOverview;
use App\Filament\Resources\Finance\PayrunResource\Widgets\PayrunTotalEmployee;
use App\Models\Employee\Gender;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\PayrunEmployee;
use App\Models\Payroll\PayrunEmployeePayment;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;

class PayrunReport extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    protected static string $resource = PayrunResource::class;

    protected static string $view = 'filament.resources.finance.payrun-resource.pages.payrun-report';
    public $payrun;
    public function mount($record)
    {
        $this->payrun = Payrun::find($record);
    }

    protected function getFooterWidgets(): array
    {
        return [
            PayrunTotalEmployee::make([
                'payrun'=>$this->payrun
            ]),
            DepartmentWiseNetSalary::make([
                'payrun'=>$this->payrun
            ]),
            NetSalaryGenderWise::make([
                'payrun'=>$this->payrun
            ]),
            MaleTaxCount::make([
                'payrun'=>$this->payrun
            ]),

            // FemaleTaxCount::make([
            //     'payrun'=>$this->payrun
            // ]),
            // NonBinaryTaxCount::make([
            //     'payrun'=>$this->payrun
            // ]),

        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [

            PayrunStatsOverview::make([
                'payrun'=>$this->payrun
            ]),
        ];
    }
}
