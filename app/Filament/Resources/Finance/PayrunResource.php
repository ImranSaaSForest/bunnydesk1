<?php

namespace App\Filament\Resources\Finance;

use App\Exports\ExportPayrun;
use App\Filament\Resources\Finance\PayrunResource\Pages;
use App\Filament\Resources\Finance\PayrunResource\Pages\ComparePayrun;
use App\Filament\Resources\Finance\PayrunResource\RelationManagers;
use App\Filament\Resources\Finance\PayunResource\Widgets\PayrunStatsOverview;
use App\Filament\Resources\Settings\PayslipResource\Pages\PayslipTemplate;
use App\Models\Company\Company;
use App\Models\Payroll\PayrollPayslipTemplate;
use App\Models\Payroll\PayrollPolicy;
use App\Models\Payroll\Payrun as PayrollPayrun;
use App\Models\Payroll\PayrunEmployee;
use App\Models\Payroll\PayrunEmployeePayment;
use App\Models\Payroll\UserPayrollPolicy;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Squire\Models\Currency;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use PragmaRX\Countries\Package\Countries;

class PayrunResource extends Resource
{
    protected static ?string $model = PayrollPayrun::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function pdfGenerator($empId){
        // $content=PayrollPayslipTemplate::where('id',4)->pluck('content')[0];
        // $employeeName=User::where('id',2)->pluck('name')[0];
        // $companyName=Company::where('id',1)->pluck('name')[0];
        // $placeholder = array(
        //     '{companyName}' => $companyName,
        //     '{employeeName}' => $employeeName,

        // );
        // $pdfContent= str_replace(array_keys($placeholder), $placeholder,  $content);
        // dd($empId);
        $employeeName='dinesh';
        $pdf = Pdf::loadView('payslip',compact('employeeName'));

        return $pdf->download();
    }
    public static function form(Form $form): Form
    {



        // self::pdfGenerator();
        $data = array();
        $currentDate = Carbon::now();
        if ($currentDate->lastOfMonth()->greaterThan($currentDate)) {

            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::today()->startOfMonth()->subMonth($i);
                $data[$month->toDateString()] = $month->monthName . '-' . $month->year;
            }
        } else {

            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::today()->subMonth(1)->startOfMonth()->subMonth($i);
                $data[$month->toDateString()] = $month->monthName . '-' . $month->year;
            }
        }
        $startDate = '';
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Payrun Name')
                            ->maxLength(255)
                            ,
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->reactive()
                            ->required(),
                        Select::make('payment_interval')
                            ->options(['weekly' => 'Weekly', 'biweekly' => 'Biweekly', 'monthly' => 'Monthly'])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get, $old, $state) {
                                if ($state == 'biweekly' && $get('start')) {
                                    $end = Carbon::create($get('start'))->addWeeks(2)->toDateString();
                                    $set('end', $end);
                                }
                                if ($state == 'weekly' && $get('start')) {
                                    $end = Carbon::create($get('start'))->addWeeks()->toDateString();
                                    $set('end', $end);
                                }
                                if ($old == 'monthly' || $state == 'monthly') {
                                    $set('start', '');
                                    $set('end', '');
                                }
                            }),
                        Select::make('payroll_policy_id')
                        ->label('Payroll policy')

                            ->options(fn (Get $get): Collection => PayrollPolicy::query()
                                ->where('company_id', $get('company_id'))
                                ->pluck('name', 'id'))
                            // ->relationship('payrollPolicy', 'name')

                            ->required(),

                        // START
                        DatePicker::make('start')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($get('payment_interval') == 'biweekly') {
                                    $end = Carbon::create($state)->addWeeks(2)->toDateString();
                                    $set('end', $end);
                                }
                                if ($get('payment_interval') == 'weekly') {
                                    $end = Carbon::create($state)->addWeeks()->toDateString();
                                    $set('end', $end);
                                }
                            })
                            ->visible(function (Get $get) {
                                if ($get('payment_interval') == 'weekly' || $get('payment_interval') == 'biweekly') {
                                    return true;
                                }
                            }),

                        //END
                        DatePicker::make('end')
                            ->required()
                            ->visible(function (Get $get) {
                                if ($get('payment_interval') == 'weekly' || $get('payment_interval') == 'biweekly') {
                                    return true;
                                }
                            })
                            ->disabled()
                            ->dehydrated(),

                        // MONTH
                        Select::make('month')
                            ->options($data)
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if ($state) {
                                    $start = $state;
                                    $end = Carbon::create($state)->endOfMonth()->toDateString();
                                    $set('start', $start);
                                    $set('end', $end);
                                }
                            })
                            ->visible(function (Get $get) {
                                if ($get('payment_interval') == 'monthly') {
                                    return true;
                                }
                            })
                            ->required(function (Get $get) {
                                if ($get('payment_interval') == 'monthly') {
                                    return true;
                                }
                            }),
                        Select::make('payroll_payslip_template_id')
                        ->label('Payslip template')
                            ->options(fn (Get $get): Collection => PayrollPayslipTemplate::query()
                                ->where('company_id', $get('company_id'))
                                ->pluck('name', 'id'))
                            // ->relationship('payrollPayslipTemplate', 'name')
                            ->required(),
                    ]),
                Select::make('status')
                    ->options([
                        'in progress' => 'in progress',
                        'cancelled' => 'cancelled',
                        'completed' => 'completed'
                    ])
                    ->default('in progress')
                    ->reactive()
                    ->required()
                    ->hiddenOn('create')

                    ,
                TextInput::make('payrun_id')

                    ->hiddenOn('create')
                    ->required()
                    ->disabled(),
                    Toggle::make('is_approved')
                    ->label('Approve')
                    ->inline(false)
                    ->default(true)
                    ->visible(function(Get $get){
                        if(auth()->user()->hasRole('Finance Admin') && $get('status')=='completed'){
                            return true;
                        }
                    }),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payrun_id'),
                TextColumn::make('name'),
                TextColumn::make('company.name'),
                TextColumn::make('payrollPolicy.name'),
                TextColumn::make('payrollPayslipTemplate.name')
                ->label('Payslip template'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in progress' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'success',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Send Payslip')
                ->color('success')
                ->action(function ($record) {
                    return PayslipTemplate::pdfGenerator($record->id);
                    // return redirect()->to('/pdf/'.$record->id);
                })
                ->icon('heroicon-s-paper-airplane')
                ->visible(function($record){
                    if($record->is_approved){
                    return true;
                    }
                    }),
                    Action::make('Export')
                    ->action(function ($record) {
                        return Excel::download(new ExportPayrun($record), 'payrun.xlsx');;
                        // return redirect()->to('/pdf/'.$record->id);
                    })
                    ->hidden(function(){
                        if(!auth()->user()->hasRole('Finance Admin')){
                            return true;
                        }
                    })
                    ->visible(function($record){
                        if($record->is_approved){
                        return true;
                        }
                        }),
               ViewAction::make('Report')
               ->label('Report')
               ->visible(function($record){
                if($record->is_approved){
                return true;
                }
                })
               ->url(fn (PayrollPayrun $record): string => 'payruns/payrunReport/'.$record->id),
                Tables\Actions\DeleteAction::make(),
                ])
                ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                        BulkAction::make('Compare')
                        ->action(function(Collection $records){
                            // $data=new ComparePayrun();
                            return redirect()->route('filament.admin.resources.finance.payruns.compare-payrun',['records'=>serialize($records->pluck('id')->toArray())]);
                        })
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PayrunEmployeeRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayruns::route('/'),
            'create' => Pages\CreatePayrun::route('/create'),
            'edit' => Pages\EditPayrun::route('/{record}/edit'),
            'payrun-report' => Pages\PayrunReport::route('/payrunReport/{record}'),
            'compare-payrun' => Pages\ComparePayrun::route('/comparePayrun'),
        ];
    }
}
