<?php

namespace App\Livewire;

use App\Models\Timesheet\ProjectDoc;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ProjectDocs extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $record;


    public function mount($record)
    {
        $this->record = $record;
    }

    public function table(Table $table): Table

    {
        return $table
            ->query(ProjectDoc::query())
            ->contentGrid([
                'mduse Illuminate\Auth\Events\Registered;' => 3,
                'xl' => 3,
            ])
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('title'),
                    ])
                ])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ActionsAction::make('Add New Docs & Files')
                    ->url(route('filament.admin.resources.timesheet.project-docs.create', ['projectId' => $this->record])),
            ])
            ->actions([
                ActionsAction::make('Click to view')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('warning')
                    ->modalIcon('heroicon-o-document-chart-bar')
                    ->modalWidth('md')
                    ->modalAlignment('center')
                    ->modalHeading(fn ($livewire) => __('Docs & Files'))
                    ->modalSubmitAction(false)
                    ->infolist([
                        TextEntry::make('title'),
                        TextEntry::make('description')
                            ->markdown()
                            ->columnspanFull()
                            ->hidden(function ($state) {
                                if (is_null($state)) {
                                    return true;
                                }
                            }),
                        TextEntry::make('original_file_name')
                            ->label('File')
                            ->hidden(function ($state) {
                                if (is_null($state)) {
                                    return true;
                                }
                            })
                            ->url(function (Model $record): string {
                                return '/storage/' . $record->file;
                            })->openUrlInNewTab(),
                        TextEntry::make('link_to_file')
                            ->hidden(function ($state) {
                                if (is_null($state)) {
                                    return true;
                                }
                            })
                            ->url(fn (string $state): string => $state)->openUrlInNewTab(),
                    ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.project-docs');
    }
}
