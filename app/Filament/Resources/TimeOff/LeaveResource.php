<?php

namespace App\Filament\Resources\TimeOff;

use App\Models\User;

use App\Filament\Resources\TimeOff\LeaveResource\Pages;
use App\Filament\Resources\TimeOff\LeaveResource\RelationManagers;
use App\Models\Employee\Employee;
use App\Models\TimeOff\Leave;
use App\Models\TimeOff\LeaveDate;
use App\Models\TimeOff\Policy;
use App\Models\TimeOff\PolicyLeaveType;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Closure;
use Carbon\CarbonPeriod;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $modelLabel = 'TimeOff';

    protected static ?string $navigationLabel = 'My Time Off';

    protected static ?string $navigationGroup = 'TIME OFF';


    protected static function getPolicyLeaveTypes(int $userId)
    {


        // Get user and associated team
        $user = User::where('id', $userId)->with(['team.policies.policyleaveTypes', 'employee.company.policies.policyleaveTypes'])->first();
        //    dd($user);
        // If no associated user or team, return empty array
        if (!$user) {
            return [];
        }

        $team = $user->team;
        // dd($team);
        // Check if team has policies
        $teamPolicies = $team->policies->where('is_active', true);
        if (!$teamPolicies->isEmpty()) {
            $policyStartDate = $teamPolicies->first()->start_date;
            // If team has active policies, get associated policy leave types
            $policyLeaveTypes = $teamPolicies->flatMap->policyleaveTypes;
        } else {
            // If team has no active policies, get company's active policies
            $companyPolicies = optional($user->employee->company)->policies->where('is_active', true);
            if ($companyPolicies->isEmpty()) {
                return [];
            }

            // Get policy leave types associated with the company's policies
            $policyLeaveTypes = $companyPolicies->flatMap->policyleaveTypes;
        }

        // Get user's gender
        $userGenderId = $user->employee->gender_id;

        // Prepare array for select options
        $options = [];
        foreach ($policyLeaveTypes as $policyLeaveType) {
            $policyFrequency = $policyLeaveType->frequency;
            $lastResetDate = $policyLeaveType->last_reset_date;
            if (Carbon::create($policyLeaveType->last_reset_date) < Carbon::now()) {
                if ($policyFrequency == 'monthly') {
                    PolicyLeaveType::find($policyLeaveType->id)
                        ->update([
                            'last_reset_date' => Carbon::create($lastResetDate)->addMonths(1)
                        ]);
                }
                if ($policyFrequency == 'annually') {

                    PolicyLeaveType::find($policyLeaveType->id)
                        ->update([
                            'last_reset_date' => Carbon::create($lastResetDate)->addYears(1)
                        ]);
                }
                if ($policyFrequency == 'weekly') {

                    PolicyLeaveType::find($policyLeaveType->id)
                        ->update([
                            'last_reset_date' => Carbon::create($lastResetDate)->addWeeks(1)
                        ]);
                }
                if ($policyFrequency == 'daily') {

                    PolicyLeaveType::find($policyLeaveType->id)
                        ->update([
                            'last_reset_date' => Carbon::create($lastResetDate)->addDays(1)
                        ]);
                }
            }
            // get start date
            if ($policyFrequency == 'monthly') {

                $startDate = Carbon::create($lastResetDate)->subMonths(1);
            }
            if ($policyFrequency == 'annually') {

                $startDate = Carbon::create($lastResetDate)->subYears(1);
            }
            if ($policyFrequency == 'weekly') {

                $startDate = Carbon::create($lastResetDate)->subWeeks(1);
            }
            if ($policyFrequency == 'daily') {

                $startDate = Carbon::create($lastResetDate)->subDays(1);
            }
            $policyLeaveTypeId = $policyLeaveType->id;
            $leaveDatesCount = LeaveDate::whereBetween(
                'leave_date',
                [
                    Carbon::create($startDate),
                    Carbon::create($lastResetDate)
                ]
            )->whereHas('leave', function ($query) use ($userId, $policyLeaveTypeId) {
                $query->where('policy_leave_type_id', $policyLeaveTypeId)->where('user_id', $userId)->where('status', 'approved');
            })->count();


            // Check if the leave type is applicable for the user's gender
            if (!$policyLeaveType->leaveType->gender || $policyLeaveType->leaveType->gender->id === $userGenderId) {
                $remainingCount = $policyLeaveType->days_allowed - $leaveDatesCount > 0 ? $policyLeaveType->days_allowed - $leaveDatesCount : 0;

                $options[$policyLeaveType->id] = $policyLeaveType->leaveType->name . ' (remaining leave ' . $remainingCount . ')';
            }
        }

        return $options;
    }


    public static function generateLeaveDates($startDate, $endDate)
    {
        $leaveStartDate = Carbon::parse($startDate);
        $leaveEndDate = Carbon::parse($endDate);

        $period = CarbonPeriod::create($leaveStartDate, $leaveEndDate);

        $dates = [];
        foreach ($period as $date) {
            $dates[] = ['leave_date' => $date->toDateString(), 'day_part' => 'full'];
        }
        return $dates;
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('apply_for')
                            ->label('Apply Leave For')
                            ->required()

                            ->options(
                                function () {
                                    if (auth()->user()->hasRole('Staff')) {
                                        return ['myself' => 'Myself'];
                                    } else {
                                        return [
                                            'myself' => 'Myself',
                                            'others' => 'Others',
                                        ];
                                    }
                                }
                            )
                            ->reactive()
                            ->afterStateUpdated(function (Set $set,  Get $get, $state) {
                                if ($state === 'myself') {
                                    $set('user_id', Auth::id());
                                    $policyLeaveTypes = self::getPolicyLeaveTypes(Auth::id());
                                    $set('policy_leave_type_options', $policyLeaveTypes);
                                }
                            }),

                        Forms\Components\Select::make('user_id')
                            ->label('Author')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disablePlaceholderSelection()
                            ->label('Employee')
                            ->reactive()
                            ->hidden(function (Get $get) {
                                return $get('apply_for') !== 'others';
                            })
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                // If apply_for is 'others', update policy leave types when user_id is updated.
                                if ($get('apply_for') === 'others') {
                                    $policyLeaveTypes = self::getPolicyLeaveTypes($state);
                                    $set('policy_leave_type_options', $policyLeaveTypes);
                                }
                            }),
                        Forms\Components\Select::make('policy_leave_type_id')
                            ->relationship('leaveType', 'id')
                            ->required()
                            ->options(function (Get $get) {
                                // Get the reactive options set in the user_id field afterStateUpdated callback.
                                return $get('policy_leave_type_options') ?? [];
                            })
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->leaveType->name}"),



                        Forms\Components\DatePicker::make('leave_start_date')
                            ->native(false)
                            ->label('Leave start date')
                            ->required()
                            ->reactive()
                            ->minDate(Carbon::now()->toDateString())
                            ->maxDate(Carbon::now()->addYear(1)) // allows to select date up to 1 year from now
                            ->suffixIcon('heroicon-m-calendar')
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                $leaveEndDate = $get('leave_end_date');
                                if (!$leaveEndDate || !$state) {
                                    return;
                                }
                                if ($leaveEndDate && Carbon::parse($state)->greaterThan(Carbon::parse($leaveEndDate))) {
                                    $set('leave_end_date', $state);
                                    $leaveEndDate = $state;
                                    $set('days_taken', 1);
                                } elseif ($leaveEndDate) {
                                    $daysTaken = Carbon::parse($state)->diffInDays(Carbon::parse($leaveEndDate)) + 1;
                                    $set('days_taken', $daysTaken);
                                }
                                if ($leaveEndDate) {
                                    $set('leaveDates', self::generateLeaveDates($state, $leaveEndDate));
                                }
                            }),

                        Forms\Components\DatePicker::make('leave_end_date')
                            ->native(false)
                            ->label('Leave end date')
                            ->required()
                            ->suffixIcon('heroicon-m-calendar')
                            ->live(onBlur: true)
                            ->minDate(function (Get $get) {
                                $leaveStartDate = $get('leave_start_date');
                                return $leaveStartDate ? Carbon::parse($leaveStartDate) : now();
                            })
                            ->maxDate(Carbon::now()->addYear(1)) // allows to select date up to 1 year from now
                            ->hidden(function (Get $get) {
                                $leaveStartDate = $get('leave_start_date');
                                if (!$leaveStartDate) {
                                    return true;
                                }
                                return false;
                            })
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                $leaveStartDate = $get('leave_start_date');

                                if (!$leaveStartDate || !$state) {
                                    return;
                                }
                                $daysTaken = Carbon::parse($leaveStartDate)->diffInDays(Carbon::parse($state)) + 1;
                                $set('leaveDates', self::generateLeaveDates($leaveStartDate, $state));
                                $set('days_taken', $daysTaken);
                            })
                            ->rules([
                                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    if ($get('user_id') && $get('leave_start_date') && $get('leave_end_date') && $get('policy_leave_type_id')) {
                                        $policyId = $get('policy_leave_type_id');
                                        $userId = $get('user_id');
                                        $period = CarbonPeriod::create($get('leave_start_date'), $get('leave_end_date'));


                                        // / Get user and associated team
                                        $user = User::where('id', $userId)->with(['team.policies.policyleaveTypes', 'employee.company.policies.policyleaveTypes'])->first();
                                        //    dd($user);
                                        // If no associated user or team, return empty array
                                        if (!$user) {
                                            return [];
                                        }

                                        $team = $user->team;
                                        // dd($team);
                                        // Check if team has policies
                                        $teamPolicies = $team->policies->where('is_active', true);
                                        if (!$teamPolicies->isEmpty()) {
                                            $policyStartDate = $teamPolicies->first()->start_date;
                                            // If team has active policies, get associated policy leave types
                                            $policyLeaveTypes = $teamPolicies->flatMap->policyleaveTypes;
                                        } else {
                                            // If team has no active policies, get company's active policies
                                            $companyPolicies = optional($user->employee->company)->policies->where('is_active', true);
                                            if ($companyPolicies->isEmpty()) {
                                                return [];
                                            }

                                            // Get policy leave types associated with the company's policies
                                            $policyLeaveTypes = $companyPolicies->flatMap->policyleaveTypes;
                                        }
                                        // Get user's gender
                                        $userGenderId = $user->employee->gender_id;

                                        // Prepare array for select options
                                        $options = [];
                                        foreach ($policyLeaveTypes->where('id', $policyId) as $policyLeaveType) {

                                            $policyFrequency = $policyLeaveType->frequency;
                                            $lastResetDate = $policyLeaveType->last_reset_date;

                                            // get start date
                                            if ($policyFrequency == 'monthly') {

                                                $startDate = Carbon::create($lastResetDate)->subMonths(1);
                                            }
                                            if ($policyFrequency == 'annually') {

                                                $startDate = Carbon::create($lastResetDate)->subYears(1);
                                            }
                                            if ($policyFrequency == 'weekly') {

                                                $startDate = Carbon::create($lastResetDate)->subWeeks(1);
                                            }
                                            if ($policyFrequency == 'daily') {

                                                $startDate = Carbon::create($lastResetDate)->subDays(1);
                                            }
                                            $leaveDatesCount = LeaveDate::whereBetween(
                                                'leave_date',
                                                [
                                                    Carbon::create($startDate),
                                                    Carbon::create($lastResetDate)
                                                ]
                                            )->whereHas('leave', function ($query) use ($userId, $policyId) {
                                                $query->where('policy_leave_type_id', $policyId)->where('user_id', $userId)->where('status', 'approved');
                                            })->count();
                                            // Check if the leave type is applicable for the user's gender
                                            if (!$policyLeaveType->leaveType->gender || $policyLeaveType->leaveType->gender->id === $userGenderId) {
                                                $remainingCount = $policyLeaveType->days_allowed - $leaveDatesCount > 0 ? $policyLeaveType->days_allowed - $leaveDatesCount : 0;
                                                $currenctCount = 0;
                                                // =$period->dateBetween(
                                                //     'leave_date',
                                                //     [
                                                //         Carbon::create($startDate),
                                                //         Carbon::create($lastResetDate)
                                                //     ]
                                                //     )->count();
                                                if (!$policyLeaveType->negative_leave_balance) {
                                                    foreach ($period as $date) {
                                                        if (Carbon::create($startDate) <= $date && Carbon::create($lastResetDate) >= $date) {
                                                            $currenctCount += 1;
                                                        }
                                                    }
                                                    if ($currenctCount > $remainingCount) {
                                                        $fail("Your remaining leave is " . $remainingCount);
                                                    }
                                                    $nextLeaveCount = 0;
                                                    foreach ($period as $date) {
                                                        if (Carbon::create($lastResetDate) < $date) {
                                                            $nextLeaveCount += 1;
                                                        }
                                                    }
                                                    if ($nextLeaveCount > $policyLeaveType->days_allowed) {
                                                        $fail("Allowed leave dates count is " . $policyLeaveType->days_allowed);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                },
                            ]),

                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('days_taken')
                                    ->reactive()
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->hidden(function (Get $get) {
                                        $leaveStartDate = $get('leave_start_date');
                                        $leaveEndDate = $get('leave_end_date');

                                        if (!$leaveStartDate || !$leaveEndDate) {
                                            return true;
                                        }

                                        return false;
                                    }),

                                // Forms\Components\Placeholder::make('days_taken')
                                // ->content(function (Closure $get) {
                                //     // Fetch the current state of the leaveDates repeater
                                //     $leaveDates = $get('leaveDates');

                                //     // Initialize the total days taken to zero
                                //     $totalDaysTaken = 0;

                                //     // Loop through all the items in the leaveDates repeater
                                //     foreach ($leaveDates as $leaveDate) {
                                //         // Add either 1 or 0.5 to the total days taken depending on the day_part
                                //         $totalDaysTaken += $leaveDate['day_part'] === 'full' ? 1 : 0.5;
                                //     }

                                //     // Return the total days taken
                                //     return $totalDaysTaken;
                                // }),

                                Forms\Components\Repeater::make('leaveDates')
                                    ->relationship()
                                    ->required()
                                    ->hidden(function (Get $get) {
                                        $leaveStartDate = $get('leave_start_date');
                                        $leaveEndDate = $get('leave_end_date');

                                        if (!$leaveStartDate || !$leaveEndDate) {
                                            return true;
                                        }

                                        return false;
                                    })
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('leave_date'),

                                                Forms\Components\TextInput::make('day_part')->label('Day')->disabled()
                                                    // ->options([
                                                    //     'full' => 'Full Day',
                                                    // ])
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire) {
                                                        // Initialize the total days taken to zero
                                                        $totalDaysTaken = 0;

                                                        // Fetch the current state of the leaveDates repeater
                                                        $leaveDates = $get('../../leaveDates');

                                                        // Loop through all the items in the leaveDates repeater
                                                        foreach ($leaveDates as $leaveDate) {
                                                            // Add either 1 or 0.5 to the total days taken depending on the day_part
                                                            if ($leaveDate['day_part'] === 'full') {
                                                                $totalDaysTaken += 1;
                                                            } else if ($leaveDate['day_part'] === 'half') {
                                                                $totalDaysTaken += 0.5;
                                                            }
                                                        }

                                                        // Set the total days taken as the new value for days_taken
                                                        $set('../../pas
                                                        days_taken', $totalDaysTaken);
                                                    })
                                            ])
                                            ->reactive(),
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false),
                                Forms\Components\Textarea::make('reason')
                                    ->required(),


                            ])
                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leaveType.leaveType.name')
                    ->label('Time Off Types')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('leaveDates.leave_date')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('days_taken')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $query->where('user_id', auth()->user()->id);

        return $query;
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
        ];
    }
}
