<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Offboarding\EmployeeOffboarding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\Offboarding\EmployeeOffboardingTask;
use App\Models\Offboarding\OffboardingList;
use App\Models\Offboarding\OffboardingTask;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class OffboardingTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeOffboardingTasks';
    protected static ?string $label = 'Assign Offboarding';
    protected static ?string $title = 'Assigned Offboarding List';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('offboardingtask.name')->label('Task'),
                TextColumn::make('offboardingtask.user.name')->label('Worked By'),
                TextColumn::make('offboardingtask.offboardingList.title')->label('Assignee'),
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
                
            ])
            ->actions([
                // Tables\Actions\Action::make('viewtasks')
                // ->label('View Tasks')->visible(function(){
                //     if(auth()->user()->hasPermissionTo('Employee Profiles')){
                //         return true;
                //     }
                // })
                // ->icon('heroicon-o-plus')
                // ->mountUsing(fn (Forms\ComponentContainer $form, EmployeeOffboarding $record) => $form->fill([
                //     'tasks' => $record->offboardingList->tasks
                // ]))
                // ->action(function (EmployeeOffboarding $record, array $data): void {

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
                    if(auth()->user()->hasPermissionTo('offboarding Permission')){
                        return true;
                    }
                }),
            ])
            ->bulkActions([
            ]);
    }
}
