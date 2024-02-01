<?php

namespace App\Filament\Resources\Offboarding;

use App\Filament\Resources\Offboarding\OffboardingListResource\Pages;
use App\Filament\Resources\Offboarding\OffboardingListResource\RelationManagers;
use App\Models\Offboarding\OffboardingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OffboardingListResource extends Resource
{
    protected static ?string $model = OffboardingList::class;

    protected static ?string $modelLabel = 'Offboarding List';

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Offboarding';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Grid::make(2)->schema([

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')->inline(false)
                    ->label('Isactive'),
                ]),
                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                      ])
                ]),

                Forms\Components\Card::make()
                ->schema([
                Forms\Components\Repeater::make('offboardingTask')
                ->relationship()
                ->schema([
                Forms\Components\TextInput::make('name')
                ->required(),

                Forms\Components\Select::make('user_id')
                ->relationship('user','name')
                ->label('Employee')
                ->required(),
                Forms\Components\TextInput::make('description'),
                Forms\Components\TextInput::make('duration')->numeric(),
                ])
                ->columns(2)
                ])
             ]);


    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('description')
                ->sortable()
                ->searchable(),

                Tables\Columns\ToggleColumn::make('is_active')
                ->label('Status')
                ->sortable()
                ->searchable(),
            ])

            ->filters([
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOffboardingLists::route('/'),
            'create' => Pages\CreateOffboardingList::route('/create'),
            'edit' => Pages\EditOffboardingList::route('/{record}/edit'),
        ];
    }
}
