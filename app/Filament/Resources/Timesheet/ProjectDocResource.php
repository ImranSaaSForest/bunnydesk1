<?php

namespace App\Filament\Resources\Timesheet;

use App\Filament\Resources\Timesheet\ProjectDocResource\Pages;
use App\Filament\Resources\Timesheet\ProjectDocResource\RelationManagers;
use App\Forms\Components\ProjectDocUpload;
use App\Models\Timesheet\ProjectDoc;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectDocResource extends Resource
{
    protected static ?string $model = ProjectDoc::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->maxLength(255)
                    ->columnSpan('full')
                    ->required(),
                RichEditor::make('description')
                    ->columnSpan('full'),
                ProjectDocUpload::make('file')
                    ->columnSpan('full'),
                TextInput::make('link_to_file')
                    ->maxLength(255)
                    ->columnSpan('full'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectDocs::route('/'),
            'create' => Pages\CreateProjectDoc::route('/create'),
            // 'edit' => Pages\EditProjectDoc::route('/{record}/edit'),
        ];
    }
}
