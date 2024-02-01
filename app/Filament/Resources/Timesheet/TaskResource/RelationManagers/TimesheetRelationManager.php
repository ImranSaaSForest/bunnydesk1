<?php

namespace App\Filament\Resources\Timesheet\TaskResource\RelationManagers;

use App\Models\Timesheet\Project;
use App\Models\Timesheet\Timesheet;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Livewire\before;

class TimesheetRelationManager extends RelationManager
{
    protected static string $relationship = 'timesheets';

    protected static ?string $recordTitleAttribute = 'task_id';

    public  function form(Form $form): Form
    {



        return $form
            ->schema([
                
                Forms\Components\Select::make('task_id')
                ->relationship('task', 'name')->required(),
                Forms\Components\Select::make('user_id')
                ->relationship('users', 'name')
                ,  // ->minDate(function (Get $get) {
                //     $leaveStartDate = $get('leave_start_date');
                //     return $leaveStartDate ? Carbon::parse($leaveStartDate) : now();
                // })
              
                Forms\Components\DatePicker::make('start_date')->minDate(function(){
               
                         $timesheet=$this->getOwnerRecord();
                         $StartDate=Project::Find($timesheet->project_id);
                        //  dd($StartDate->start_date);
                        return $StartDate->start_date;

                })
                
                
                ->label('Start date')->required(),
                Forms\Components\DatePicker::make('end_date')->maxDate(function(){
               
                    $timesheet=$this->getOwnerRecord();
                    $EndDate=Project::Find($timesheet->project_id);
                    // dd($StartDate->end_date);
                   return $EndDate->end_date;

           })
           
                ->label('End date')->required(),
                Forms\Components\TimePicker::make('start_time')
                ->seconds(false)
                ->withoutSeconds()
                ->displayFormat('h:i A')
                ->timezone(config('app.timezone'))->required()->minDate(now()),
                Forms\Components\TimePicker::make('end_time')->required()
                ->seconds(false)
                ->withoutSeconds()
                ->displayFormat('h:i A')
                ->timezone(config('app.timezone')),
                Forms\Components\Select::make('status')
                ->default('draft')
                ->options([

                    'Assign To' => 'Assign To',
                ])
            ]);
    }
    

    public  function table(Table $table): Table
    {
        return $table
        
            ->columns([
                Tables\Columns\TextColumn::make('task.name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('users.name')->searchable()->sortable()->toggleable()->label('Employee'),
                Tables\Columns\TextColumn::make('start_date')
                ->sortable()
                ->searchable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                ->sortable()
                ->searchable()
                ->toggleable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'assign To' => 'assign To',
                        'submitted' => 'Submitted',
                    ])->searchable()->sortable()->toggleable(),
                    
              
            ])
          
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make()
                ->after(function(Timesheet $record)
                    {
                   
                       $recipient = User::where('id', $record->user_id)->get();
                        //   dd($recipient);
                       Notification::make()
                           ->title('You have been assigned a task')
                           ->sendToDatabase($recipient);

                           

                    }),
              
                
            ])
            ->actions([
              
                Tables\Actions\DeleteAction::make()->after(function(Timesheet $record)
                {
               
                   $recipient = User::where('id', $record->user_id)->get();
                    //   dd($recipient);
                   Notification::make()
                       ->title('Your Task has been deleted ')
                       ->sendToDatabase($recipient);

                }),
          
              
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->after(function(Timesheet $record)
                {
               
                   $recipient = User::where('id', $record->user_id)->get();
                    //   dd($recipient);
                   Notification::make()
                       ->title('Your Task has been deleted')
                       ->sendToDatabase($recipient);

                }),
                
            ]);



            
    }
}
