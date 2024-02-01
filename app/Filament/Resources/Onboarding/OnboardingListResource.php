<?php

namespace App\Filament\Resources\Onboarding;

use App\Filament\Resources\Onboarding\OnboardingListResource\Pages;
use App\Filament\Resources\Onboarding\OnboardingListResource\RelationManagers;
use App\Models\Onboarding\OnboardingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class OnboardingListResource extends Resource
{
    protected static ?string $model = OnboardingList::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Onboarding';


    protected static ?string $navigationGroup = 'Settings';

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
                                ->maxLength(65535),
                        ]),

                    ]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('tasks')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                ->required(),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
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
            'index' => Pages\ListOnboardingLists::route('/'),
            'create' => Pages\CreateOnboardingList::route('/create'),
            'edit' => Pages\EditOnboardingList::route('/{record}/edit'),
        ];
    }
}
