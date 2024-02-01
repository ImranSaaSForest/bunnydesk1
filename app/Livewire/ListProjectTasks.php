<?php

namespace App\Livewire;

use App\Models\Timesheet\Project;
use App\Models\Timesheet\Task;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListProjectTasks extends Component implements HasForms, HasTable

{
    use InteractsWithTable;
    use InteractsWithForms;

    public $record;

    public function mount($record)
    {
        // dd($record);
        $this->record = $record;
        // $this->project = Project::where('id', $record->id)->with('tasks')->get();
    }

    public function table(Table $table): Table
    {
        // dd(Task::where('project_id', $this->record->id)->get());
        return $table
            ->query(Task::where('project_id', $this->record->id))
            // ->relationship(fn (): HasMany => $this->project->tasks())
            // ->inverseRelationship('task')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('users.name'),
                TextColumn::make('start_date'),
                TextColumn::make('end_date'),
                TextColumn::make('description')
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make()->form([
                    Grid::make(2)->schema([
                        // TextInput::make('project_id')->disabled(),
                        TextInput::make('name')
                            ->required(),
                        Select::make('user_id')
                            ->relationship(name: 'users', titleAttribute: 'name')
                            ->required(),
                        DatePicker::make('start_date')
                            ->minDate(today())
                            ->native(false),
                        DatePicker::make('end_date')
                            ->minDate(today())
                            ->native(false),

                    ]),
                    Textarea::make('description')
                ])->mutateFormDataUsing(function (array $data): array {
                  
                    $data['project_id'] = $this->record->id;
                    // dd($data);
                    return $data;
                })
                    ->slideOver()
            ])
            ->actions([
                EditAction::make()->form([
                    Grid::make(2)->schema([
                        // TextInput::make('project_id')->disabled(),
                        TextInput::make('name')
                            ->required(),
                        Select::make('user_id')
                            ->relationship(name: 'users', titleAttribute: 'name')
                            ->required(),
                        DatePicker::make('start_date')
                            ->minDate(today())
                            ->native(false),
                        DatePicker::make('end_date')
                            ->minDate(today())
                            ->native(false),
                    ]),
                    Textarea::make('description')
                ])->mutateFormDataUsing(function (array $data): array {
                    $data['project_id'] = $this->record->id;
                    // dd($data);
                    return $data;
                })
                    ->slideOver(),

            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.list-project-tasks');
    }
}
