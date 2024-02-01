<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecruitmentReportResource\Pages;
use App\Filament\Resources\RecruitmentReportResource\RelationManagers;
use App\Models\Recruitment\Job;
use App\Models\RecruitmentReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\Page\CustomReport;

class RecruitmentReportResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Reports';

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
                //
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
            'index' => Pages\CustomReport::route('/'),
            // 'index' => CustomReport::route('/sort'),
            'create' => Pages\CreateRecruitmentReport::route('/create'),
            'edit' => Pages\EditRecruitmentReport::route('/{record}/edit'),
        ];
    }
}
