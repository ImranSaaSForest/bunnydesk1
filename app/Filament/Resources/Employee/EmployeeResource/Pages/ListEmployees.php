<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use App\Filament\Resources\Employee\EmployeeResource;
use App\Models\Company\Company;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeStatus;
use App\Models\Employee\EmployeeType;
use App\Models\Employee\Employment;
use App\Models\Employee\Gender;
use App\Models\Employee\JobInfo;
use App\Models\Employee\Team;
use App\Models\Onboarding\OnboardingList;
use App\Models\Role;
use App\Models\User;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListEmployees extends ListRecords
{

    protected static string $resource = EmployeeResource::class;

    protected static ?string $title = 'Employees';

    protected function getActions(): array
    {
        return [
            Action::make('import')
                ->hidden(!auth()->user()->hasPermissionTo('Employee Profiles'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(fn (): string => route('filament.admin.resources.employees.import')),

            Action::make('Add Employee')
                ->modalHeading('Add New Employee')
                ->icon('heroicon-o-plus-circle')
                ->color(Color::Blue)
                ->modalSubmitActionLabel('Create')
                ->form([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')->label('Name')
                                ->required(),
                            TextInput::make('last_name')->label('Last Name'),
                            TextInput::make('email')->label('Email')
                                ->unique(ignoreRecord: true)
                                ->required(),

                            //employee table
                            Select::make('gender_id')->label('Gender')
                                ->options(Gender::pluck('name', 'id'))
                                ->required(),
                            Select::make('company_id')->label('Company')
                                ->options(Company::all()->pluck('name', 'id'))
                                ->required(),
                            TextInput::make('social_security_number')->label('Number')->required(),

                            //employment table
                            TextInput::make('employment_id')
                                ->label('Employee ID')
                                ->required(),
                            // DatePicker::make('effective_date'),
                            // DatePicker::make('hired_on'),
                            // Select::make('employee_type_id')->label('Employee Type')
                            //     ->options(EmployeeType::pluck('name', 'id'))
                            //     ->required(),
                            Select::make('employee_status_id')->label('Employee Status')
                                ->options(EmployeeStatus::pluck('name', 'id'))
                                ->required(),

                            //Designation table
                            Select::make('designation_id')->label('Designation')
                                ->options(Designation::pluck('name', 'id'))
                                ->required(),

                            //jobinfo table
                            Select::make('report_to')->label('Report To')->options(User::pluck('name', 'id'))
                                ->required(),
                            Select::make('team_id')->label('Team')
                                ->options(Team::pluck('name', 'id')),


                                Select::make('roles')->label('Role')
                                ->options(Role::all()->pluck('name', 'id'))
                                ->required()
                                ->multiple()->searchable(),

                            // Select::make('Payroll Policy')
                            // ->required(),
                            // Select::make('Onboarding')
                            //     ->options(OnboardingList::pluck('title', 'id'))
                            // ->default('Select Onboarding')
                            // ->required(),
                        ])
                ])

                ->action(function ($data) {
                    $data['password'] = Hash::make('password');
                    $record = User::create($data);
                    $role_name=Role::find($data['roles']);
                    // dd($data['company_id']);
                    Employee::create([
                        'user_id' => $record->id,
                        'company_id' => $data['company_id'],
                        // 'date_of_birth'=>,
                        'gender_id' => $data['gender_id'],
                        // 'marital_status_id'=>,
                        'social_security_number' => $data['social_security_number'],
                        // 'timezone',
                    ]);
                    Employment::create([
                        'user_id' => $record->id,
                        'employment_id' => $data['employment_id'],
                        // 'hired_on' => $data['hired_on'],
                        //  'employee_type_id' => $data['employee_id'],
                        // 'effective_date' => $data['effective_date'],
                        'employee_status_id' => $data['employee_status_id'],
                    ]);

                    //  $designation=Designation::create([
                    //  'department_id' => department_id,
                    //  'name',
                    // ]);

                    JobInfo::create([
                        'user_id' => $record->id,
                        'designation_id' => $data['designation_id'],
                        'report_to' => $data['report_to'],
                        'team_id' => $data['team_id'],
                        // 'shift_id' => null,
                    ]);

                    $record->assignRole($role_name[0]->name);

                    Notification::make()
                        ->title('Create successfully')
                        ->success()
                        ->send();
                })
                ->slideOver(),
            // Actions\CreateAction::make()->label('New Employee'),
            //    ExcelImportAction::make()
            //    ->use(\App\Imports\EmployeeImport::class)
            //     ->color("primary")
            //    ,
        ];
    }


    protected function getTableContentGrid(): ?array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
