<?php

namespace App\Livewire;

use App\Models\Asset\Asset;
use App\Models\Asset\EmployeeAsset;
use App\Models\Employee\CurrentAddress;
use App\Models\Offboarding\EmployeeOffboarding;
use App\Models\Offboarding\EmployeeOffboardingTask;
use App\Models\Offboarding\OffboardingList;
use App\Models\Offboarding\OffboardingTask;
use App\Models\Onboarding\EmployeeOnboarding;
use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\Onboarding\OnboardingList;
use App\Models\Onboarding\OnboardingTask;
use App\Models\User;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction as ActionsEditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\View\View;
class BoardingTable extends Component implements HasForms, HasTable
{

    use InteractsWithForms;
    use InteractsWithTable;

    public $modelName;

    public EmployeeAsset $category;

    public $user_id;
    // public function mount(){
    //     // dd($this->user_id);
    // }



    public function table(Table $table): Table
    {
        if ($this->modelName == 'onboarding') {
            return $table
            ->heading("Assign OnBoarding")
            ->query(EmployeeOnboardingTask::with('onboardingTask')
            ->whereHas('onboardingTask', function ($query) {
                $query->where('user_id', $this->user_id);
            })
             )
                ->columns([
                    TextColumn::make('onboardingTask.name')->label('Task Name')
                        ->searchable(),
                    TextColumn::make('onboardingTask.onboardingList.title')->label("On Boarding"),
                    TextColumn::make('onboardingTask.user.name')->label('Worked To'),
                    TextColumn::make('onboardingTask.onboardingList.title')->label('Name'),
                    TextColumn::make('onboardingTask.duration')->label("Duration")
                        ->searchable(),
                    TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'notstarted' => 'danger',
                        'inprogress' => 'warning',
                        'completed' => 'success',

                    })

                    // TextColumn::make('is_active')
                    //     ->searchable()
                    //     ->sortable()
                    //     ->toggleable(),
                ])
                ->filters([
                    // ...
                ])

                ->actions([
                    EditAction::make()->label('Update Status')
                    ->slideOver()
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->searchable()
                                    // ->columns(2)
                                    ->options([
                                        'notstarted' => 'Not Started',
                                        'inprogress' => 'In Progress',
                                        'completed'  => 'Completed',
                                    ]),

                            ]),
                    ])->modalContent(fn ( $record): View => view(
                        'filament.pages.onboardingslide',
                        ['record' => $record],
                    )),
                ])
                ->headerActions([
                    CreateAction::make()
                 //    ->visible(function(){
                 //         if(auth()->user()->hasPermissionTo('offboarding Permission')){
                 //             return true;
                 //         }
                 //     })

                     ->form([
                         Select::make('onboarding_list_id')
                         // ->relationship('onboardingList','title')
                         ->options(function(){
                             $random = OnboardingList::pluck('title','id');
                             return $random;
                         })
                         ->label('On Boarding List')->required(),
                         TextInput::make('comment')
                         ->label('Comment'),
                         ])
                         ->slideOver()
                     ->label('Assign Onboarding')
                     ->action(function (array $data) {

                        $alredy_exist=EmployeeOnboarding::where( 'onboarding_list_id',$data['onboarding_list_id'])->first();

                        if($alredy_exist){
                            Notification::make()
                            ->title('Already Assigned this OnBoarding')
                            ->icon('heroicon-o-exclamation-circle')
                            ->iconColor('danger')
                            ->send();
                            return '';
                        }else{
                         // First create the employee_offboarding record with the user_id and offboarding_list_id set
                         $employeeOnboarding = EmployeeOnboarding::create([
                             'user_id' => $this->user_id,
                             'onboarding_list_id' => $data['onboarding_list_id'],
                             'comment' => $data['comment'] ?? null,
                         ]);

                         // Fetch the offboarding tasks based on the selected offboarding list
                         $onboardingTasks = OnboardingTask::where('onboarding_list_id', $data['onboarding_list_id'])->get();

                         // Create an employee_offboarding_task record for each offboarding task
                         foreach ($onboardingTasks as $onboardingTask) {
                             EmployeeOnboardingTask::create([
                                 'employee_onboarding_id' => $employeeOnboarding->id,
                                 'onboarding_task_id' => $onboardingTask->id,
                                 'comment' => null,
                             ]);
                         //     $recipient = User::where('id', $offboardingTask->user_id)->get();
                         //     Notification::make()
                         //         ->title('Offboarding')
                         //         ->body("Your offboarding has been started")
                         //         ->actions([
                         //             Action::make('view')
                         //                 ->button()->url('/employees/'.$offboardingTask->user_id.'/edit?activeRelationManager=7')->close()
                         //         ])
                         //         ->sendToDatabase($recipient);
                         }
                         // $recipient = User::where('id', $livewire->ownerRecord->id)->get();
                         // Notification::make()
                         //     ->title('Review')
                         //     ->body("A new offboarding task has been assigned")
                         //     ->actions([
                         //         Action::make('view')
                         //             ->button()->url('/employees/'.$livewire->ownerRecord->id.'/edit?activeRelationManager=7')->close()
                         //     ])
                         //     ->sendToDatabase($recipient);
                         return $employeeOnboarding;

                        }
                     }),
                 ])
                ->bulkActions([
                    // ...
                ]);
        }

        elseif ($this->modelName == 'offboarding') {

            return $table
                ->query(EmployeeOffboardingTask::with('offboardingTask')
                    ->whereHas('offboardingTask', function ($query) {
                        $query->where('user_id', $this->user_id);
                    })
                )->heading("Assign OffBoarding")
                ->columns([
                    TextColumn::make('offboardingTask.name')->label('Task Name')
                        ->searchable(),
                    TextColumn::make('offboardingTask.offboardingList.title')->label("Off Boarding"),
                    TextColumn::make('offboardingTask.user.name')->label('Worked To'),
                    TextColumn::make('offboardingTask.offboardingList.title')->label('Name'),
                    TextColumn::make('offboardingTask.duration')->label("Duration")
                        ->searchable(),
                    TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'notstarted' => 'danger',
                        'inprogress' => 'warning',
                        'completed' => 'success',

                    })

                ])
                ->filters([
                    // ...
                ])
                ->headerActions([
                    CreateAction::make()
                 //    ->visible(function(){
                 //         if(auth()->user()->hasPermissionTo('offboarding Permission')){
                 //             return true;
                 //         }
                 //     })
                     ->form([
                         Select::make('offboarding_list_id')
                         // ->relationship('onboardingList','title')
                         ->options(function(){
                             $random = OffboardingList::pluck('title','id');
                             return $random;
                         })
                         ->label('On Boarding List')->required(),
                         TextInput::make('comment')
                         ->label('Comment'),
                         ])
                         ->slideOver()
                     ->action(function (array $data) {
                        $alredy_exist=EmployeeOnboarding::where('offboarding_list_id',$data['offboarding_list_id'])->first();

                        if($alredy_exist){
                            Notification::make()
                            ->title('Already Assigned this OffBoarding')
                            ->icon('heroicon-o-exclamation-circle')
                            ->iconColor('danger')
                            ->send();
                            return '';
                        }else{
                         // First create the employee_offboarding record with the user_id and offboarding_list_id set
                         $employeeOffboarding = EmployeeOffboarding::create([
                             'user_id' => $this->user_id,
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
                         //     $recipient = User::where('id', $offboardingTask->user_id)->get();
                         //     Notification::make()
                         //         ->title('Offboarding')
                         //         ->body("Your offboarding has been started")
                         //         ->actions([
                         //             Action::make('view')
                         //                 ->button()->url('/employees/'.$offboardingTask->user_id.'/edit?activeRelationManager=7')->close()
                         //         ])
                         //         ->sendToDatabase($recipient);
                         }
                         // $recipient = User::where('id', $livewire->ownerRecord->id)->get();
                         // Notification::make()
                         //     ->title('Review')
                         //     ->body("A new offboarding task has been assigned")
                         //     ->actions([
                         //         Action::make('view')
                         //             ->button()->url('/employees/'.$livewire->ownerRecord->id.'/edit?activeRelationManager=7')->close()
                         //     ])
                         //     ->sendToDatabase($recipient);
                         return $employeeOffboarding;
                        }
                     }),
                 ])
                ->actions([
                    EditAction::make()->label('Update Status')
                    ->slideOver()
                    ->form([
                        Grid::make(2)

                            ->schema([
                                TextInput::make('aame')->default('sadfas')->disabled(),
                                Select::make('status')
                                    ->label('Status')
                                    ->searchable()
                                    // ->columns(2)
                                    ->options([
                                        'notstarted' => 'Not Started',
                                        'inprogress' => 'In Progress',
                                        'completed'  => 'Completed',
                                    ]),

                            ]),
                    ])
                    ->modalContent(fn ( $record): View => view(
                        'filament.pages.offboardingslide',
                        ['record' => $record],
                    )),



                    // Tables\Actions\DeleteAction::make(),
                ])->headerActions([
                   CreateAction::make()
                //    ->visible(function(){
                //         if(auth()->user()->hasPermissionTo('offboarding Permission')){
                //             return true;
                //         }
                //     })

                    ->form([
                        Select::make('offboarding_list_id')
                        // ->relationship('onboardingList','title')
                        ->options(function(){
                            $random = OffboardingList::pluck('title','id');
                            return $random;
                        })
                        ->label('On Boarding List')->required(),
                        TextInput::make('comment')
                        ->label('Comment'),
                        ])
                        ->slideOver()
                    ->label('Assign Offboarding')
                    ->action(function (array $data) {

                        // First create the employee_offboarding record with the user_id and offboarding_list_id set
                        $employeeOffboarding = EmployeeOffboarding::create([
                            'user_id' => $this->user_id,
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
                        //     $recipient = User::where('id', $offboardingTask->user_id)->get();
                        //     Notification::make()
                        //         ->title('Offboarding')
                        //         ->body("Your offboarding has been started")
                        //         ->actions([
                        //             Action::make('view')
                        //                 ->button()->url('/employees/'.$offboardingTask->user_id.'/edit?activeRelationManager=7')->close()
                        //         ])
                        //         ->sendToDatabase($recipient);
                        }
                        // $recipient = User::where('id', $livewire->ownerRecord->id)->get();
                        // Notification::make()
                        //     ->title('Review')
                        //     ->body("A new offboarding task has been assigned")
                        //     ->actions([
                        //         Action::make('view')
                        //             ->button()->url('/employees/'.$livewire->ownerRecord->id.'/edit?activeRelationManager=7')->close()
                        //     ])
                        //     ->sendToDatabase($recipient);
                        return $employeeOffboarding;
                    }),
                ])
                // ->headerActions([
                    // CreateAction::make('Assign Offboarding')
                    // ->label('Assign')
                    // ->slideOver()
                    // ->modalHeading('Assign Onbording Task')
                    // ->modalCancelActionLabel('Cancel')
                    // ->modalSubmitActionLabel('Assign')
                    // ->createAnother(false)
                    // ->form([
                    //     Select::make('offboarding_list_id')
                    //     // ->relationship('onboardingList','title')
                    //     ->options(function(){
                    //         $random = OffboardingList::pluck('title','id');
                    //         return $random;
                    //     })
                    //     ->label('On Boarding List')->required(),
                    //     TextInput::make('comment')
                    //     ->label('Comment'),
                    //     ])
                    //     ->mutateFormDataUsing(function (array $data): array {
                    //         $data['user_id'] = $this->user_id;
                    //         // dd($data['onboarding_list_id']);
                    //         $employee_off_board=EmployeeOffboarding::create([
                    //             'user_id' => $data['user_id'],
                    //             'offboarding_list_id' => $data['offboarding_list_id'],
                    //         ]);

                            // $off_Task=OffboardingTask::where('offboarding_list_id',$data['offboarding_list_id'])->get();
                            // dd($off_Task);

                            // employee_offboarding_tasks

                        //    foreach($off_Task as $task){
                        //         EmployeeOffboardingTask::create([
                        //             'employee_offboarding_id' =>  $employee_off_board->id,
                        //             'offboarding_task_id'   => $task->id,
                        //         ]);
                        //     };
                        //      return
                        // })
                        // ->before(function($data){
                            // dd($data['offboarding_list_id']);
                            // employee_offboardings

                        // })
                // ])
                ->bulkActions([
                    // ...
                ]);
        }

        elseif ($this->modelName == 'on_ofboarding') {

            return $table
                ->heading("OffBoarding Status")
                ->query(OffboardingTask::with('empoffboardingTask')
                    ->whereHas('empoffboardingTask', function ($query) {
                        $query->with('employeeOffboardingTask')
                        ->whereHas('employeeOffboardingTask', function ($query) {
                            $query->where('user_id', $this->user_id);
                        });
                    }))
                ->columns([
                    TextColumn::make('name')->label('Task Name')
                        ->searchable(),
                    TextColumn::make('offboardingList.title')->label("Off Boarding"),
                    TextColumn::make('user.name')->label('Worked By'),
                    TextColumn::make('duration')->label("Duration")
                        ->searchable(),
                        TextColumn::make('empoffboardingTask.status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'notstarted' => 'danger',
                            'inprogress' => 'warning',
                            'completed' => 'success',

                        })

                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    DeleteAction::make(),

                ])

                ->bulkActions([
                    // ...
                ]);
        }
        elseif ($this->modelName == 'on_onboarding') {

            return $table
                ->heading("OnBoarding Status")
                ->query(OnboardingTask::with('empOnTask')
                    ->whereHas('empOnTask', function ($query) {
                        $query->with('employeeOnboardingTask')
                        ->whereHas('employeeOnboardingTask', function ($query) {
                            $query->where('user_id', $this->user_id);
                        });
                    }))
                ->columns([
                    TextColumn::make('name')->label('Task Name')
                        ->searchable(),
                    TextColumn::make('onboardingList.title')->label("On Boarding"),
                    TextColumn::make('user.name')->label('Worked By'),
                    TextColumn::make('duration')->label("Duration")
                        ->searchable(),
                    TextColumn::make('empOnTask.status')->label("Status")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'notstarted' => 'danger',
                        'inprogress' => 'warning',
                        'completed' => 'success',

                    })

                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    DeleteAction::make(),

                ])

                ->bulkActions([
                    // ...
                ]);
        }

        elseif ($this->modelName == 'asset') {

            return $table
                ->query(EmployeeAsset::where('user_id', $this->user_id))
                // ->relationship(fn (): BelongsToMany => $this->category->asset())
                // ->inverseRelationship('asset')
                ->columns([
                    TextColumn::make('asset.name')
                    ->label('Name'),
                    TextColumn::make('asset.description')
                    ->label('Description'),
                    TextColumn::make('asset.value')
                    ->label('Value'),
                    TextColumn::make('asset.company.name')
                    ->label('Company'),


                    // TextColumn::make('')
                    // ->label('Updated On')
                    //     ->searchable()
                    //     ->sortable(),
                ])
                ->filters([
                    // ...
                ])->headerActions([
                    CreateAction::make('Assign Asset')
                    ->slideOver()
                    ->createAnother(false)
                    ->form([
                        Select::make('asset_id')
                        // ->relationship('onboardingList','title')
                        ->options(function(){
                            $random = Asset::pluck('name','id');
                            return $random;
                        })
                        ->label('Asset')->required(),
                        ])
                        ->mutateFormDataUsing(function (array $data): array {
                            $data['user_id'] = $this->user_id;
                            return $data;
                        })
                ])
                ->actions([
                    // ViewAction::make(),
                    // Tables\Actions\EditAction::make(),
                    DeleteAction::make(),
                ])
                ->bulkActions([
                    // ...
                ]);
        }

    }

    public function render()
    {
        // dd(OnboardingList::all());
        return view('livewire.boarding-table');
    }
}
