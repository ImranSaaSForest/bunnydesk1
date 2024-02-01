<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Onboarding\EmployeeOnboarding;
use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\Onboarding\OnboardingList;
use App\Models\Onboarding\OnboardingTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class OnboardingTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeOnboardingWork';

    protected static ?string $label = 'Assign Onboarding List';
    
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Assigned Onboarding List';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('onboarding_list_id')
                // ->relationship('onboardingList','title')
                // ->label(' Select OnboardingList'),
                // Forms\Components\Select::make('user_id')
                //                 ->relationship('user', 'name')
                //                 ->label(' Assigned To')
                //                 ->options(User::all()->pluck('name', 'id'))
                //                 ->searchable(),
                Forms\Components\Select::make('onboarding_list_id')
                ->relationship('onboardingList','title')
                ->options(function(){
                    $record = $this->getOwnerRecord();
                    // dd($record);
                    $random = OnboardingList::pluck('title','id');
                    return $random->toArray();
                })
                ->label('Select OnboardingList')
                ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        // ->modifyQueryUsing(function (Builder $query) {
        //     $query->with('empOnTask');
        // })
            ->columns([
                TextColumn::make('employeeOnboardingTask.user.jobInfo.designation.name')->label('Department'),
                TextColumn::make('employeeOnboardingTask.user.name')->label('Worked To'),
                TextColumn::make('employeeOnboardingTask.onboardingList.title')->label('Assignee'),
                Tables\Columns\TextColumn::make('onboardingTask.name')
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
                Tables\Actions\CreateAction::make()->visible(function(){
                    if(auth()->user()->hasPermissionTo('onboarding Permission')){
                        return true;
                    }
                })
                ->label('Assign Onboarding')
                ->using(function (array $data, RelationManager $livewire): Model {

                    // First create the employee_onboarding record with the user_id and onboarding_list_id set
                    $employeeOnboarding = EmployeeOnboarding::create([
                        'user_id' => $livewire->ownerRecord->id,
                        'onboarding_list_id' => $data['onboarding_list_id'],
                        'comment' => $data['comment'] ?? null,
                    ]);

                    // Fetch the onboarding tasks based on the selected onboarding list
                    $onboardingTasks = OnboardingTask::where('onboarding_list_id', $data['onboarding_list_id'])->get();


                    // Create an employee_onboarding_task record for each onboarding task
                    foreach ($onboardingTasks as $onboardingTask) {
                        EmployeeOnboardingTask::create([
                            'employee_onboarding_id' => $employeeOnboarding->id,
                            'onboarding_task_id' => $onboardingTask->id,
                            'comment' => null,
                        ]);
                        $recipient = User::where('id', $onboardingTask->user_id)->get();
                    Notification::make()
                        ->title('Onboarding')
                        ->body("Your onboarding has been started")
                        ->actions([
                            Action::make('view')
                                ->button()->url('/employees/'.$onboardingTask->user_id.'/edit?activeRelationManager=6')->close()
                        ])
                        ->sendToDatabase($recipient);
                    }
                    $recipient = User::where('id', $livewire->ownerRecord->id)->get();
                    Notification::make()
                        ->title('Review')
                        ->body("A new onboarding task has been assigned")
                        ->actions([
                            Action::make('view')
                                ->button()->url('/employees/'.$livewire->ownerRecord->id.'/edit?activeRelationManager=6')->close()
                        ])
                        ->sendToDatabase($recipient);
                    return $employeeOnboarding;


                }),
            ])
            
            ->actions([
                Tables\Actions\Action::make('Update Status') ->visible(function(){
                    if(auth()->user()->id == $this->getOwnerRecord()->id){
                        return true;
                    }

                })
                ->icon('heroicon-o-plus')
                ->mountUsing(fn (Forms\ComponentContainer $form, EmployeeOnboardingTask $record) => $form->fill([
                    'status' => $record->status ,
                    'comment' => $record->comment,
                ]))
                ->action(function (EmployeeOnboardingTask $record, array $data): void {
                    if (isset($data['status'])) {
                        if($data['status']=="completed"){
                            $record->status = $data['status'];

                            $recipient = User::where('id', $record->employeeOnboardingTask->user_id)->get();
                            Notification::make()
                                ->title('Onboarding')
                                ->body("Your onboarding has been Completed")
                                ->actions([
                                    Action::make('view')
                                        ->button()->url('/employees/'.$record->employeeOnboardingTask->user_id.'/edit?activeRelationManager=6')->close()
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
