<?php

namespace App\Filament\Resources\Timesheet\ProjectResource\RelationManagers;

use Filament\Forms;
use App\Models\Timesheet\Task;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $recordTitleAttribute = 'task_id';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)->disabled(function(){
                        if(!auth()->user()->hasRole('Supervisor')){
                            return true;
                        }
                    }),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull()->disabled(function(){
                        if(!auth()->user()->hasRole('Supervisor')){
                            return true;
                        }
                    }),
            ]);
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->visible(function(){
                    if(auth()->user()->hasRole('Supervisor')){
                        return true;
                    }
                }),
            //     ->after(function($record){
            //        return redirect()->to('/timesheet/projects');
            // }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function(){
                    if(auth()->user()->hasRole('Supervisor')){
                        return true;
                    }
                }),
                // ->url(fn (Task $record): string => route('filament.admin.resources.timesheet.tasks.edit', $record)),
                Tables\Actions\DeleteAction::make() ->visible(function(){
                    if(auth()->user()->hasRole('Supervisor')){
                        return true;
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make() ->visible(function(){
                    if(auth()->user()->hasRole('Supervisor')){
                        return true;
                    }
                }),
            ]);
    }
}
