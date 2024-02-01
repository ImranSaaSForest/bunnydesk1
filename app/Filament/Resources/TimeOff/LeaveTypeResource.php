<?php

namespace App\Filament\Resources\TimeOff;

use App\Filament\Resources\TimeOff\LeaveTypeResource\Pages;
use App\Filament\Resources\TimeOff\LeaveTypeResource\RelationManagers;
use App\Models\TimeOff\LeaveType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveTypeResource extends Resource
{
    protected static ?string $model = LeaveType::class;

    protected static ?string $modelLabel = 'TimeOff Type';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Timeoff Types';

    protected static ?string $navigationGroup = 'TimeOff Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                    Forms\Components\Select::make('gender_id')
                    ->relationship('gender', 'name'),
                    Forms\Components\TextInput::make('days_allowed')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('frequency')
                ->options([
                 'weekly'=> 'Weekly',
                 'monthly'=> 'Monthly',
                'annually' => 'Annually']),


                Forms\Components\Toggle::make('auto_approve')
                    // ->inline(false)
                    ->required(),
                // Forms\Components\ColorPicker::make('color'),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('days_allowed')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('frequency')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('auto_approve')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                // Tables\Columns\TextColumn::make('color')
                //     ->sortable()
                //     ->searchable()
                //     ->toggleable(),
                Tables\Columns\TextColumn::make('gender.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLeaveTypes::route('/'),
        ];
    }
}
