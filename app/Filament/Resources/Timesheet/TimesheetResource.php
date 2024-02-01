<?php

namespace App\Filament\Resources\Timesheet;

use App\Filament\Resources\Timesheet\TimesheetResource\Pages;
use App\Filament\Resources\Timesheet\TimesheetResource\RelationManagers;
use App\Models\Employee\JobInfo;
use App\Models\Timesheet\Project;
use App\Models\Timesheet\Task;
use App\Models\Timesheet\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Notifications\Actions\Action as ActionsAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Assign;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static ?string $modelLabel = 'Timesheet Review';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project')->label('Project Name')->required()
                    // ->relationship('task.project','name')->required()
                    ->options(function () {

                        // $user=User::where('id',auth()->id());
                        $projectList = Project::where('team_id', User::where('id', auth()->id())->with('team')->get()[0]->team->id)->pluck('name', 'id');

                        return $projectList;
                    })
                    ->live(),

                Forms\Components\Select::make('task_id')->label('Task')->required()
                    // ->relationship('task', 'name')->required(),
                    ->options(function (Get $get) {
                        // dd($get);
                        $taskList = Task::where('project_id', $get('project'))->pluck('name', 'id');
                        // dd($r);
                        return $taskList;
                    })->required(),


                Forms\Components\Select::make('user_id')->label('Assign to')->required()
                    ->options(function (Get $get) {
                        $teams = Project::where('id', $get('project'))->pluck('team_id', 'id')->toArray();
                        // dd($y);
                        $teamlist = JobInfo::where('team_id', $teams)->pluck('user_id', 'id');
                        $arr = [];
                        foreach ($teamlist as $v) {
                            $arr[$v] = User::find($v)->name;
                        }
                        return $arr;
                    })->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->suffixIcon('heroicon-m-calendar')
                    ->minDate(function (Get $get) {

                        $timesheet = project::Find($get('project'));
                        if ($get('project')) {

                            if (Carbon::now()->toDateString() >= $timesheet->start_date) {
                                return Carbon::now()->toDateString();
                            } else {
                                return $timesheet->start_date;
                            }
                        }
                    })->live()->maxDate(function (Get $get) {

                        $timesheet = project::Find($get('project'));
                        if ($timesheet != null) {
                            return $timesheet->end_date;
                        }
                    })->label('Start date')->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->native(false)
                    ->suffixIcon('heroicon-m-calendar')
                    ->minDate(function (Get $get) {

                        $StartDate = $get('start_date');
                        if ($StartDate != null) {
                            return $StartDate ? Carbon::parse($StartDate) : now();
                        } else {
                            $timesheet = project::Find($get('project'));
                            if ($timesheet != null) {
                                return $timesheet->start_date;
                            }
                        }
                    })
                    ->maxDate(function (Get $get) {
                        $timesheet = project::Find($get('project'));
                        if ($timesheet != null) {
                            return $timesheet->end_date;
                        }
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

                        'assigned' => 'Assigned',
                        // 'inprogress' => 'In Progress',
                        // 'submitted' => 'Submitted'

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->modifyQueryUsing(fn (Builder $query) => $query->orWhere('user_id', auth()->id())->orWhere('created_by', auth()->id()))

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
                Tables\Actions\CreateAction::make()
                    ->visible(function () {

                        $emp = JobInfo::whereNotNull('report_to')->pluck('report_to')->toArray();
                        // dd($emp);
                        if (in_array(auth()->id(), $emp)) {
                            // dd(auth()->id(),$emp);

                            return true;
                        }
                    })


            ])


            ->actions([

                Tables\Actions\EditAction::make()

                    ->label('Update Status')
                    ->visible(function ($record) {
                        // dd($record->created_by);
                        // dd(auth()->id());

                        // $test=Timesheet::where()->get();
                        // dd($record->user_id);

                        if ($record->user_id == auth()->id()) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->form([
                        Forms\Components\Select::make('status')->required()
                            ->default('draft')
                            ->options([
                                'inprogress' => 'In Progress',
                                'submitted' => 'Submitted',
                            ])

                    ])->after(function ($record, $data): void {
                        $random = User::with('jobInfo')->find(auth()->id());
                        //  dd($random->jobInfo->report_to);
                        $report = User::find($random->jobInfo->report_to);
                        // dd($report);

                        $test = Timesheet::Find($record->id);

                        $test->update([
                            'status' => $data['status'],
                        ]);
                        if ($data['status'] == 'inprogress') {
                            if ($random->jobInfo->report_to != null) {
                                Notification::make()
                                    ->title('The Task is In Progress')
                                    ->actions([
                                        ActionsAction::make('view')
                                            ->button()->url('/timesheet/timesheets')
                                            ->close()
                                    ])
                                    ->sendToDatabase($report);
                            }
                        }
                        if ($data['status'] == 'submitted') {
                            if ($random->jobInfo->report_to != null) {
                                Notification::make()
                                    ->title('The Task is In submitted')
                                    ->actions([
                                        ActionsAction::make('view')
                                            ->button()->url('/timesheet/timesheets')
                                            ->close()
                                    ])
                                    ->sendToDatabase($report);
                            }
                        }
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery();
    //     // dd($query);

    //     if (auth()->user()->hasRole('Supervisor')) {
    //         // Show submitted timesheets awaiting approval for Supervisors
    //         $query->where('status', 'submitted');
    //     }

    //     return $query;
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheets::route('/'),
            'create' => Pages\CreateTimesheet::route('/create'),
            // 'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}
