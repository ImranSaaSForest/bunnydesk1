<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Offboarding\EmployeeOffboarding;
use App\Models\Offboarding\EmployeeOffboardingTask;
use App\Models\Offboarding\OffboardingList;
use App\Models\Offboarding\OffboardingTask;
use Filament\Forms;
use Filament\Forms\Form;
// use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\User;
use Dompdf\FrameDecorator\Text;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;



class EmployeeOffboardingRelationManager extends RelationManager
{

    protected static string $relationship = 'employeeOffboardingWork';
    protected static ?string $label = 'Assign Offboarding List';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Offboarding Status ';

    // public static function canViewForRecord(Model $ownerRecord,string $pageClass): bool
    // {
    //     return $ownerRecord->id === auth()->id();
    // }

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('offboarding_list_id')
                    // ->relationship('offboardingList','title')
                    ->options(function(){
                    $record = $this->getOwnerRecord();
                    // dd($record);
                    // $random = OnboardingTask::Where('user_id',$record->id)->pluck('name','onboarding_list_id');
                    $random = OffboardingList::pluck('title','id');
                    return $random;
                })
                    ->label(' Select OffboardingList')->required(),
            ]);
    }

    public  function table(Table $table): Table
    {
        return $table
        // ->modifyQueryUsing(function (Builder $query) {
        //     dd($query->get());
        // })
            ->columns([
                TextColumn::make('EmployeeOffboardingTask.user.jobInfo.designation.name')->label('Department'),
                TextColumn::make('EmployeeOffboardingTask.user.name')->label('Worked To'),
                TextColumn::make('EmployeeOffboardingTask.offboardingList.title')->label('Assignee'),
                Tables\Columns\TextColumn::make('offboardingTask.name')
                    ->label('Task')
                    ->sortable()
                    ->searchable(),
                    TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inprogress' => 'warning',
                        'completed' => 'success',
                        'notstarted' => 'danger',
                    })

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()  ->visible(function(){
                    if(auth()->user()->hasPermissionTo('offboarding Permission')){
                        return true;
                    }
                })
                ->label('Assign Offboarding')
                ->using(function (array $data, RelationManager $livewire): Model {

                    // First create the employee_offboarding record with the user_id and offboarding_list_id set
                    $employeeOffboarding = EmployeeOffboarding::create([
                        'user_id' =>  $livewire->ownerRecord->id,
                        'offboarding_list_id' => $data['offboarding_list_id'],
                        'comment' => $data['comment'] ?? null,
                    ]);

                    // Fetch the offboarding tasks based on the selected offboarding list
                    $offboardingTasks = OffboardingTask::where('offboarding_list_id', $data['offboarding_list_id'])->get();

                    // Create an employee_offboarding_task record for each offboarding task
                    foreach ($offboardingTasks as $offboardingTask) {
                        EmployeeOffboardingTask::create([
                            'employee_offboarding_id' => $employeeOffboarding->id,
                            'offboarding_task_id' => $offboardingTask->id,
                            'comment' => null,
                        ]);
                        $recipient = User::where('id', $offboardingTask->user_id)->get();
                        Notification::make()
                            ->title('Offboarding')
                            ->body("Your offboarding has been started")
                            ->actions([
                                Action::make('view')
                                    ->button()->url('/employees/'.$offboardingTask->user_id.'/edit?activeRelationManager=7')->close()
                            ])
                            ->sendToDatabase($recipient);
                    }
                    $recipient = User::where('id', $livewire->ownerRecord->id)->get();
                    Notification::make()
                        ->title('Review')
                        ->body("A new offboarding task has been assigned")
                        ->actions([
                            Action::make('view')
                                ->button()->url('/employees/'.$livewire->ownerRecord->id.'/edit?activeRelationManager=7')->close()
                        ])
                        ->sendToDatabase($recipient);
                    return $employeeOffboarding;
                }),
            ])
            ->actions([
                Tables\Actions\Action::make('Update Status') ->visible(function(){
                    if(auth()->user()->id == $this->getOwnerRecord()->id){
                        return true;
                    }
                })
                ->icon('heroicon-o-plus')
                ->mountUsing(fn (Forms\ComponentContainer $form, EmployeeOffboardingTask $record) => $form->fill([
                    'status' => $record->status ,
                    'comment' => $record->comment,
                ]))
                ->action(function (EmployeeOffboardingTask $record, array $data): void {
                    if (isset($data['status'])) {
                        if($data['status']=="completed"){
                            $record->status = $data['status'];
                            $recipient = User::where('id', $record->EmployeeOffboardingTask->user_id)->get();
                            Notification::make()
                                ->title('Offboarding')
                                ->body("Your offboarding has been Completed")
                                ->actions([
                                    Action::make('view')
                                        ->button()->url('/employees/'.$record->EmployeeOffboardingTask->user_id.'/edit?activeRelationManager=7')->close()
                                ])
                                ->sendToDatabase($recipient);
                        }
                        else{
                            $record->status = $data['status'];
                        }
                    }

                    if (isset($data['comment'])) {
                        $record->comment = $data['comment'];
                    }
                    $record->save();
                })
                ->form([
                    Forms\Components\Select::make('status')
                        ->default('notstarted')
                        ->options([
                            'notstarted' => 'Notstarted',
                            'inprogress' => 'Inprogress',
                            'completed' => 'Completed',
                        ]),
                    Forms\Components\Textarea::make('comment')
                    ->label('comment')->hidden(true),

                ]),
            ])
            ->bulkActions([
            ]);
    }


}
