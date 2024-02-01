<?php

namespace App\Filament\Resources\TimeOff;


use App\Filament\Resources\TimeOff\MyCompanyResource\Pages;
use App\Filament\Resources\TimeOff\MyCompanyResource\RelationManagers;
use App\Models\TimeOff\Leave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MyCompanyResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $modelLabel = 'My Company TimeOff';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'TIME OFF';

    protected static ?string $navigationLabel = 'My Company';

    protected static ?string $slug = 'Company';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('leaveType.leaveType.name')
                    ->label('Time Off Types')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('leaveDates.leave_date')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('days_taken')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMyCompanies::route('/'),
        ];
    }
}
