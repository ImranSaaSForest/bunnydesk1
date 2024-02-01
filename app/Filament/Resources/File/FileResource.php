<?php

namespace App\Filament\Resources\File;

use App\Filament\Resources\File\FileResource\Pages;
use App\Filament\Resources\File\FileResource\RelationManagers;
use App\Models\Company\Company;
use App\Models\File\File;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;


class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $label = 'Files';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('file_name')
                ->label(' File Name')
                ->required(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                    ->collection('files')
                    ->multiple()
                    ->maxFiles(5)
                    ->required()
                    ->enableReordering()
                    ->disableLabel(),
                Forms\Components\Select::make('company_id')
                ->label('Company Name')
                    ->options(Company::all()->pluck('name', 'id'))
                    ->required()

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')->relationship('company', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_file')
                ->label('View File')
                ->url(fn (Model $record): string => $record->file_url)
                ->icon('heroicon-o-eye')
                ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()->visible(
                    function(){
                        if(auth()->user()->hasPermissionTo('HR Toolkit Upload')){
                                    return true;
                                }
                    }
                ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->visible(
                    function(){
                        if(auth()->user()->hasPermissionTo('HR Toolkit Upload')){
                                    return true;
                                }
                    }
                ),
            ]);
    }


    public function viewFile($record): void
    {
        // ...
        // dd($record);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFiles::route('/'),
        ];
    }
}
