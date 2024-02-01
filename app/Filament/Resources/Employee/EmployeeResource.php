<?php

namespace App\Filament\Resources\Employee;

use App\Forms\Components\AddressForm;
use App\Models\Role;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use App\Filament\Resources\Employee\EmployeeResource\Pages;
use App\Filament\Resources\Employee\EmployeeResource\Pages\ImportEmployee;
use App\Filament\Resources\Employee\EmployeeResource\RelationManagers;
use App\Filament\Resources\MyInfoResource\Pages\MyInfo;
use App\Models\Employee\CurrentAddress;
use App\Models\Onboarding\OnboardingList;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Resources\RelationManagers\RelationManager\BenefitRelationManager;
use Filament\Tables;
use Squire\Models\Currency;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;
use PragmaRX\Countries\Package\Countries;

use Filament\Forms\Get;
use Filament\Infolists\Components\ViewEntry;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

use Filament\Tables\Actions\ViewAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Employee';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $slug = 'employees';




    public static function form(Form $form): Form
    {

        $countries = new Countries();
        $currencies = $countries->currencies();
        $currencyList = [];
        foreach ($currencies as $key => $value) {
            $currencyList[$key] = $value->name;
        }
        ksort($currencyList);
        return $form

            ->schema([

                TextInput::make('name')
                    ->label('First Name')
                    ->required(),
                TextInput::make('last_name')
                    ->label('Last Name')
                    ->required(),
                TextInput::make('email')
                    ->unique(ignoreRecord: true)
                    ->reactive()
                    ->required(),

                Section::make('Personal Details')
                    ->relationship('employee')
                    ->schema([
                        Select::make('company_id')
                            ->relationship('company', 'name')->required(),
                        DatePicker::make('date_of_birth'),
                        Select::make('gender_id')
                            ->relationship('gender', 'name'),

                        Select::make('marital_status_id')
                            ->relationship('maritalStatus', 'name'),

                        TextInput::make('social_security_number')
                            ->maxLength(20)->label('SSN'),

                        TimezoneSelect::make('timezone')
                            ->searchable()
                            ->label('Time Zone')
                            ->required(),


                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('employee-images')
                            ->label('Profile Picture')->columnSpanFull(),
                    ])
                    ->columns(2),


                Section::make('Roles')->disabled(function () {

                    if (auth()->user() && auth()->user()->hasRole('Staff')) {
                        return true;
                    }
                })
                    ->schema([
                        Select::make('roles')->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->required()
                            ->multiple()->searchable(),
                    ]),


                // Section::make('Address')
                //     ->schema([
                //         AddressForm::make('current_address')
                //             ->columnSpan('full')
                //             ->relationship('currentAddress'),
                //     ]),
                Section::make('Address')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Select::make('country')
                                    ->searchable()
                                    ->options(function ($state) {
                                        $countries = new \League\ISO3166\ISO3166();
                                        $countryList = [];
                                        foreach ($countries->all() as $country) {
                                            $countryList[$country['alpha2']] = $country['name'];
                                        }
                                        return $countryList;
                                    }),
                                Forms\Components\TextInput::make('city'),
                                Forms\Components\TextInput::make('street')
                                    ->label('Street address'),
                                Forms\Components\TextInput::make('state')
                                    ->label('State / Province'),
                                Forms\Components\TextInput::make('zip')
                                    ->label('Zip / Postal code'),
                            ])->relationship('currentAddress'),
                    ]),

                Section::make('Contact Info')
                    ->relationship('contact')
                    ->schema([

                        TextInput::make('work_phone')
                            ->tel()
                            ->maxLength(20),
                        PhoneInput::make('mobile_phone'),
                        // ->inputNumberFormat(PhoneInputNumberType::NATIONAL),
                        // TextInput::make('mobile_phone')
                        //     ->tel()
                        //     ->maxLength(20),
                        TextInput::make('home_phone')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('home_email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Job Info')->disabled(function () {

                    if (auth()->user() && auth()->user()->hasRole('Staff')) {
                        return true;
                    }
                })
                    ->relationship('jobInfo')
                    ->schema([

                        Select::make('designation_id')
                            ->relationship('designation', 'name')
                            ->label('Designation')


                            ->createOptionForm([
                                Select::make('department_id')
                                    ->relationship('department', 'name'),
                                TextInput::make('name')
                                    ->label(' Name')
                                    ->required()
                                    ->maxLength(255),


                            ]),
                        Select::make('report_to')
                            ->relationship('supervisor', 'name')

                            ->label('Report To'),

                        Select::make('team_id')
                            ->relationship('team', 'name')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')->label('Team')->required()

                            ]),

                        Select::make('shift_id')
                            ->relationship('shift', 'name')
                            ->createOptionForm([
                                TextInput::make('name')->label('Shift')->required()
                            ])

                    ])
                    ->columns(2),


                Section::make('Compensation')->disabled(function () {

                    if (auth()->user() && auth()->user()->hasRole('Staff')) {
                        return true;
                    }
                })
                    ->relationship('salaryDetail')
                    ->schema([


                        Select::make('payment_interval_id')
                            ->label('payment interval')
                            ->relationship('paymentinterval', 'name')
                            ->required(),

                        Select::make('payment_method_id')
                            ->relationship('paymentMethod', 'name')
                            ->required(),
                        TextInput::make('amount')
                        ->numeric()
                        ->required(),
                        // Select::make('currency')
                        //     ->options(Currency::all()->pluck('name', 'id'))
                        //     ->searchable()
                        //     ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                        //     ->getOptionLabelUsing(fn ($value): ?string => Currency::find($value)?->getAttribute('name'))
                        //     ->required(),
                        Select::make('currency')
                            ->required()
                            ->options($currencyList)
                            ->searchable()

                    ])
                    ->columns(2),

                // Section::make('Policies')->disabled( function(){

                //      if(auth()->user()&&auth()->user()->hasRole('Staff')){
                //          return true;
                //      }
                //  })
                //     ->relationship('UserPayrollPolicy')
                //     ->schema([
                //         Select::make('payroll_policy_id')
                //             ->relationship('payrollPolicy', 'name')
                //             ->required()

                //     ]),
                Section::make('Bank Info')->disabled(function () {

                    if (auth()->user() && auth()->user()->hasRole('Staff')) {
                        return true;
                    }
                })
                    ->relationship('bankInfo')
                    ->schema([

                        TextInput::make('bank_name')
                            ->maxLength(250),
                        TextInput::make('name')
                            ->maxLength(100),
                        TextInput::make('ifsc')
                            ->maxLength(50),
                        TextInput::make('micr')
                            ->maxLength(50),
                        TextInput::make('account_number')
                            ->maxLength(30),
                        TextInput::make('branch_code')
                            ->maxLength(20),

                    ])
                    ->columns(2),



                Section::make('Employment')->disabled(function () {

                    if (auth()->user() && auth()->user()->hasRole('Staff')) {
                        return true;
                    }
                })
                    ->relationship('employment')
                    ->reactive()
                    ->schema([
                        Select::make('employee_type_id')
                            ->reactive()
                            ->relationship('employeeType', 'name'),

                        Select::make('employee_status_id')->required()
                            ->relationship('employeeStatus', 'name')->default(1),
                        TextInput::make('employment_id')
                            ->maxLength(50)->label('Employee ID'),
                        DatePicker::make('hired_on'),
                        DatePicker::make('effective_date')->columnSpan('full'),
                    ])
                    ->columns(2),

                Section::make('Contract')
                    ->relationship('contract')
                    ->visible(function (Get $get) {
                        return $get('employment.employee_type_id') == 3;
                    })
                    ->reactive()
                    ->schema([
                        DatePicker::make('start_date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->minDate(function (Get $get) {
                                $startDate = $get('start_date');
                                return $startDate ? Carbon::parse($startDate) : now();
                            })
                            ->required(),
                        Textarea::make('terms')->columnSpan('full'),
                    ])
                    ->columns(2),
                Section::make('Onboarding')->hiddenOn('edit')->disabled(function () {
                    if (auth()->user() && auth()->user()->hasRole('Staff')) {
                        return true;
                    }
                })
                    ->schema([

                        Select::make('Onboarding')->options(OnboardingList::pluck('title', 'id'))->required()

                    ]),




            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
        ->recordUrl(function($record){
            if(auth()->user()->hasRole('Super Admin')){
                return "employees/".$record->id."/edit";
            }
        }
        )
        ->contentGrid([
            'mduse Illuminate\Auth\Events\Registered;' => 3,
            'xl' => 4,
        ])
        ->columns([

            Split::make([
                Stack::make([
                    // ImageColumn::make('employee.profile_picture_url')
                    //     ->label('Image')->circular(),
                    // TextColumn::make('name')->searchable()->sortable()->toggleable()->weight('bold'),
                    // TextColumn::make('jobInfo.designation.name')->label('Gender'),
                    ViewColumn::make('name')->view('tables.columns.employee-card')->searchable(),
                ])
            ])
        ])
        ->filters([
            SelectFilter::make('filter'),
        ])
            ->actions([
                // Tables\Actions\EditAction::make()
                //     ->visible(function ($record) {
                //         $id = $record['id'];
                //         // dd($id);
                //         if (auth()->id() == $id && auth()->user()->hasRole('Staff') || auth()->user()->hasPermissionTo('Employee Profiles')) {
                //             return true;
                //         }
                //     }),

                Tables\Actions\Action::make('Contact Information')
                    ->slideOver()
                    ->modalHeading(fn ($livewire) => __(''))
                    ->model('fsdf')
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->infolist([
                        ViewEntry::make('')
                            ->view('infolists.components.employee-slider')
                    ])->hidden(function(){
                        if(auth()->user()->hasRole('Super Admin')){
                            return true;
                        }
                    })


            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(function () {
                        if (auth()->user()->hasPermissionTo('Employee Profiles')) {
                            return true;
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EmergencyContactRelationManager::class,
            RelationManagers\EducationRelationManager::class,
            RelationManagers\ExperienceRelationManager::class,
            RelationManagers\EmployeeAssetRelationManager::class,
            // RelationManagers\NoteRelationManager::class,
            RelationManagers\EmployeeBenefitRelationManager::class,
            RelationGroup::make('Performance', [
                RelationManagers\PerformanceGoalsRelationManager::class,
                // RelationManagers\AppraisalRelationManager::class,
            ]),
            RelationGroup::make('Onboarding', [
                RelationManagers\OnboardingTasksRelationManager::class,
                RelationManagers\EmployeeOnboardingRelationManager::class,
            ]),
            RelationGroup::make('Offboarding', [
                RelationManagers\EmployeeOffboardingRelationManager::class,
                RelationManagers\OffboardingTasksRelationManager::class,
            ]),
            RelationManagers\TimesheetRelationManager::class,

        ];
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            // 'edit'  =>Pages\EditEmployee::route('/{record}/edit'),
            'edit' => MyInfo::route('/{record}/edit'),
            'import' => ImportEmployee::route('/import'),
            // 'view' => Pages\ViewEmployee::route('/{record}'),
        ];
    }
}
