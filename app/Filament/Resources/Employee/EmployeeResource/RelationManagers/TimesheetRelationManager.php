<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Employee\JobInfo;
use App\Models\Timesheet\Project;
use App\Models\Timesheet\Task;
use App\Models\Timesheet\Timesheet;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\Builder;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;


class TimesheetRelationManager extends RelationManager
{
    protected static string $relationship = 'timesheets';

    protected static ?string $recordTitleAttribute = 'task_id';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project')->label('Project Name')
                // ->relationship('task.project','name')->required()
                ->options(function () {

                    // $user=User::where('id',auth()->id());
                    $projectList = Project::where('team_id', User::where('id', auth()->id())->with('team')->get()[0]->team->id)->pluck('name', 'id');

                    return $projectList;
                })
                ->live(),

            Forms\Components\Select::make('task_id')->label('Task')
                // ->relationship('task', 'name')->required(),
                ->options(function (Get $get) {
                    // dd($get);
                    $taskList = Task::where('project_id', $get('project'))->pluck('name', 'id');
                    // dd($r);
                    return $taskList;
                }),


            Forms\Components\Select::make('user_id')->label('Assign to')
                ->options(function (Get $get) {
                    $teams = Project::where('id', $get('project'))->pluck('team_id', 'id')->toArray();
                    // dd($y);
                    $teamlist = JobInfo::where('team_id', $teams)->pluck('user_id', 'id');
                    $arr = [];
                    foreach ($teamlist as $v) {
                        $arr[$v] = User::find($v)->name;
                    }
                    return $arr;
                }),

            Forms\Components\DatePicker::make('start_date')->minDate(function (Get $get) {


                $timesheet = project::Find($get('project'));
                return $timesheet->start_date;
            })->label('Start date')->required(),

            Forms\Components\DatePicker::make('end_date')->maxDate(function (Get $get) {

                $timesheet = project::Find($get('project'));
                return $timesheet->end_date;
            })
                ->label('End date')->required(),

            Forms\Components\TimePicker::make('start_time')
                ->seconds(false)->required()
                ->withoutSeconds()
                ->displayFormat('h:i A')
                ->timezone(config('app.timezone')),
            Forms\Components\TimePicker::make('end_time')
                ->withoutSeconds()
                ->seconds(false)
                ->displayFormat('h:i A')
                ->timezone(config('app.timezone'))->required(),

            Forms\Components\Select::make('status')->required()
                ->default('draft')
                ->options([
                  
                    'assign To' => 'assign To',
                  
                ]),
            ]);
    }

    public  function table(Table $table): Table
    {

        return $table
        
        // ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id',auth()->id()))
        
            ->columns([
                Tables\Columns\TextColumn::make('task.project.name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('task.name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_time')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('end_time')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('status')->searchable()->sortable()->toggleable(),
            ])


            ->filters([
                //
            ])



          ->headerActions([
                // Tables\Actions\CreateAction::make()
                //     ->visible(function () {

                //         $emp = JobInfo::whereNotNull('report_to')->pluck('report_to')->toArray();
                //         // dd($emp);
                //         if (in_array(auth()->id(), $emp)) {
                //             // dd(auth()->id(),$emp);

                //             return true;
                //         }
                //     })

                  
            ])  ->actions([

                Tables\Actions\EditAction::make()
                    ->label('Update Status')
                    ->form([
                        Forms\Components\Select::make('status')->required()
                            ->default('draft')
                            ->options([
                                'submitted' => 'Submitted',
                            ])

                    ])->after(function ($record, $data): void {
                        $random = User::with('jobInfo')->find(auth()->id());
                        //  dd($random);
                        $report = User::find($random->jobInfo->report_to);
                        // dd($report);
                       
                        $test = Timesheet::Find($record->id);
                       
                        $test->update([
                            'status' => $data['status'],
                        ]);
                        Notification::make()
                        ->title('Your  Task Submitted')
                        ->actions([
                            ActionsAction::make('view')
                            ->button()->url('/timesheet/timesheets')
                            ->close()
                        ])
                        ->sendToDatabase($report);

                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                ->after(function (Timesheet $record) {

                    $recipient = User::where('id', $record->user_id)->get();
                    //   dd($recipient);
                    Notification::make()
                        ->title('You have been delete a task')
                        ->sendToDatabase($recipient);
                }),
            ]);
    }
}
