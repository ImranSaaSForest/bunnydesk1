<?php

namespace App\Filament\Resources\Timesheet;

use App\Filament\Resources\Timesheet\TaskResource\Pages;
use App\Filament\Resources\Timesheet\TaskResource\RelationManagers;
use App\Models\Timesheet\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            // ->schema([
            //     Forms\Components\TextInput::make('name')
            //     ->required()
            //     ->maxLength(255)->disabled(function(Get $get){
            //         $id=$get('user_id');
            //         if($id){
            //         if(auth()->id()!=$id)
            //         {
            //             return true;

            //         }}
            //         }),
            // Forms\Components\Textarea::make('description')
            //     ->required()
            //     ->columnSpanFull()->disabled(function(Get $get){
            //         $id=$get('user_id');
            //         if($id){
            //         if(auth()->id()!=$id)
            //         {
            //             return true;

            //         }}
            //         }),
            // ])
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('name'),
                // Tables\Columns\TextColumn::make('users.name'),
                // Tables\Columns\TextColumn::make('start_date'),
                // Tables\Columns\TextColumn::make('end_date'),
                // Tables\Columns\TextColumn::make('description'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->disabled(function(Get $get){
                    $id=$get('user_id');
                    if($id){
                    if(auth()->id()!=$id)
                    {
                        return true;

                    }}
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->disabled(function(Get $get){
                        $id=$get('user_id');
                        if($id){
                        if(auth()->id()!=$id)
                        {
                            return true;

                        }}
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\TimesheetRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
