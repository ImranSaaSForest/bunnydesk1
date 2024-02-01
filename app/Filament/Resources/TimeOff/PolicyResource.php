<?php

namespace App\Filament\Resources\TimeOff;

use App\Filament\Resources\TimeOff\PolicyResource\Pages;
use App\Filament\Resources\TimeOff\PolicyResource\RelationManagers;
use App\Models\Company\Company;
use App\Models\Employee\Team;
use App\Models\TimeOff\LeaveType;
use App\Models\TimeOff\Policy;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Closure;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'TimeOff Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->required(),

                            Forms\Components\DatePicker::make('start_date')
                            ->required(),

                        ]),

                        Forms\Components\Textarea::make('description')
                            ->required(),

                        Forms\Components\Grid::make(2)->schema([


                            Forms\Components\Select::make('work_week_id')
                                ->relationship('workWeek', 'name') ->required(),

                            Forms\Components\Select::make('holiday_id')
                                ->relationship('holiday', 'name') ->required(),
                        ]),
                        // c
                    ]),


                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('policyleaveTypes')
                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data,Get $get): array {
                            $startDate=$get('start_date');
                            $frequency =$data['frequency'];

                            if ($frequency == 'monthly') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addMonths(1);

                            }
                            if ($frequency == 'annually') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addYears(1);

                            }
                            if ($frequency == 'daily') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addDays(1);

                            }
                            if ($frequency == 'weekly') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addWeeks(1);

                            }

                            return $data;
                        })
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data,Get $get): array {
                            $startDate=$get('start_date');
                            $frequency =$data['frequency'];

                            if ($frequency == 'monthly') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addMonths(1);

                            }
                            if ($frequency == 'annually') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addYears(1);

                            }
                            if ($frequency == 'daily') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addDays(1);

                            }
                            if ($frequency == 'weekly') {

                                    $data['last_reset_date']=Carbon::create($startDate)->addWeeks(1);

                            }

                            return $data;
                        })
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('leave_type_id')
                                    ->relationship('leaveType', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set,  Get $get, $state) {
                                        // $state holds the selected leave_type_id
                                        if (!empty($state)) {
                                            // Fetch the LeaveType based on $state (leave_type_id)
                                            $leaveType = LeaveType::find($state);

                                            if ($leaveType) {
                                                // Set value for days_allowed
                                                $set('days_allowed', $leaveType->days_allowed);

                                                // Set value for policy_frequency_id
                                                $set('frequency', $leaveType->frequency);
                                            } else {
                                                // If no LeaveType was found, reset the fields
                                                $set('days_allowed', null);
                                                $set('frequency', null);
                                            }
                                        } else {
                                            // If no leave type was selected, reset the fields
                                            $set('days_allowed', null);
                                            $set('frequency', null);
                                        }
                                    })
                                    ->required(),

                                Forms\Components\TextInput::make('days_allowed')
                                    ->numeric()
                                    ->required(),

                                    Forms\Components\Toggle::make('is_paid')
                                    ->label('Enable paid')

                                    ->default(true),
                                    Forms\Components\Toggle::make('negative_leave_balance')
                                    ->label('Negative Leave Balance')

                                   ,
                                    Forms\Components\Select::make('frequency')
                                    ->options([
                                        'daily'=> 'Daily',
                                     'weekly'=> 'Weekly',
                                     'monthly'=> 'Monthly',
                                    'annually' => 'Annually']),

                            ])
                            ->columns(2)
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('workWeek.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('holiday.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Assign Leave Policy')
                    ->icon('heroicon-o-plus')
                    ->mountUsing(fn (Forms\ComponentContainer $form, Policy $record) => $form->fill([
                        'companies' => $record->companies->pluck('id')->toArray(),
                        'teams' => $record->teams->pluck('id')->toArray(),
                    ]))
                    ->action(function (Policy $record, array $data): void {
                        if (isset($data['companies'])) {
                            $record->companies()->sync($data['companies']);
                        }
                        if (isset($data['teams'])) {
                            $record->teams()->sync($data['teams']);
                        }
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Select::make('companies')
                            ->label('Assign to Company/Branch')
                            ->relationship('companies', 'name')
                            ->options(Company::all()->pluck('name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->required()
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, Closure $fail) {
                                        // Check if any selected company has an active policy.
                                        foreach ($value as $companyId) {
                                            $company = Company::find($companyId);
                                            // If no company was found with the given ID, fail the rule.
                                            if (is_null($company)) {
                                                $fail("No company with ID {$companyId} exists.");
                                                return; // Return early since the rule has already failed.
                                            }

                                            $hasActivePolicy = $company->policies()->where('is_active', true)->first();
                                            if ($hasActivePolicy) {
                                                $fail("The company {$company->name} already has an active policy.");
                                                return; // Return early since the rule has already failed.
                                            }
                                        }
                                    };
                                },
                            ]),

                        Forms\Components\Select::make('teams')
                            ->label('Assign to Teams')
                            ->relationship('teams', 'name')
                            ->options(Team::all()->pluck('name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, Closure $fail) {
                                        // Check if any selected team has an active policy.
                                        foreach ($value as $teamId) {
                                            $team = Team::find($teamId);
                                            // If no team was found with the given ID, fail the rule.
                                            if (is_null($team)) {
                                                $fail("No team with ID {$teamId} exists.");
                                                return; // Return early since the rule has already failed.
                                            }

                                            $hasActivePolicy = $team->policies()->where('is_active', true)->first();
                                            if ($hasActivePolicy) {
                                                $fail("The team {$team->name} already has an active policy.");
                                                return; // Return early since the rule has already failed.
                                            }
                                        }
                                    };
                                },
                            ]),
                    ]),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }
}
