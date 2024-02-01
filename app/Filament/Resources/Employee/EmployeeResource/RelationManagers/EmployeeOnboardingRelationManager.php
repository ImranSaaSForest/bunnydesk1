<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Onboarding\EmployeeOnboarding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\Onboarding\OnboardingList;
use App\Models\Onboarding\OnboardingTask;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeOnboardingTasks';
    protected static ?string $label = 'Assign Onboarding';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Onboarding Status';
    // public static function canViewForRecord(Model $ownerRecord,string $pageClass): bool
    // {
    //     return $ownerRecord->id === auth()->id();
    // }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('onboarding_list_id')
                // ->relationship('onboardingList','title')
                ->options(function(){
                    $record = $this->getOwnerRecord();
                    // dd($record);
                    // $random = OnboardingTask::Where('user_id',$record->id)->pluck('name','onboarding_list_id');
                    $random = OnboardingList::pluck('title','id');
                    return $random;
                })
                ->label(' Select OnboardingList')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        // ->modifyQueryUsing(function (Builder $query) {
           
        //     $query->where('user_id', $this->getOwnerRecord()->id)->with('emponboarding.onboardingTask.onboardingList');

        //     // foreach ($variable as  $value) {
        //     //     # code...
        //     // }
        // })
            ->columns([
                TextColumn::make('onboardingtask.name')->label('Assignee'),
                TextColumn::make('onboardingtask.user.name')->label('Worked By'),
                TextColumn::make('onboardingtask.onboardingList.title')->label('Task'),
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
                // Tables\Actions\CreateAction::make()->visible(function(){
                //     if(auth()->user()->hasPermissionTo('onboarding Permission')){
                //         return true;
                //     }
                // })
                // ->label('Assign Onboarding')
                // ->using(function (array $data, RelationManager $livewire): Model {

                //     // First create the employee_onboarding record with the user_id and onboarding_list_id set
                //     $employeeOnboarding = EmployeeOnboarding::create([
                //         'user_id' => $livewire->ownerRecord->id,
                //         'onboarding_list_id' => $data['onboarding_list_id'],
                //         'comment' => $data['comment'] ?? null,
                //     ]);

                //     // Fetch the onboarding tasks based on the selected onboarding list
                //     $onboardingTasks = OnboardingTask::where('onboarding_list_id', $data['onboarding_list_id'])->get();

                //     // Create an employee_onboarding_task record for each onboarding task
                //     foreach ($onboardingTasks as $onboardingTask) {
                //         EmployeeOnboardingTask::create([
                //             'employee_onboarding_id' => $employeeOnboarding->id,
                //             'onboarding_task_id' => $onboardingTask->id,
                //             'comment' => null,
                //         ]);
                //     }

                //     return $employeeOnboarding;
                // }),
            ])
            ->actions([
                // Tables\Actions\Action::make('viewtasks')->visible(function(){
                //     // if(auth()->user()->hasPermissionTo('Employee Profiles')){
                //     //     return true;
                //     // }
                // })
                // ->label('View Tasks')
                // ->icon('heroicon-o-plus')
                // ->mountUsing(fn (Forms\ComponentContainer $form, EmployeeOnboarding $record) => $form->fill([
                //     'tasks' => $record->onboardingList->tasks
                // ]))
                // ->action(function (EmployeeOnboarding $record, array $data): void {

                // })
                // ->form([
                //     Forms\Components\Repeater::make('tasks')
                //     ->schema([
                //         Forms\Components\TextInput::make('name'),
                //         Forms\Components\Select::make('user_id')
                //             ->relationship('user', 'name')
                //             ->label('Employee'),
                //         Forms\Components\TextInput::make('description'),
                //         Forms\Components\TextInput::make('duration'),
                //     ])
                //     ->disabled()
                //     ->disableItemCreation()
                //     ->disableItemDeletion()
                //     ->columns(2)

                // ]),
                Tables\Actions\DeleteAction::make()->visible(function(){
                    if(auth()->user()->hasPermissionTo('onboarding Permission')){
                        return true;
                    }
                }),
            ])
            ->bulkActions([
            ]);
    }
}
