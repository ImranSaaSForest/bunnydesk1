<?php

namespace App\Filament\Resources\Learning;

use App\Filament\Resources\Learning\EnrollmentResource\Pages;
use App\Filament\Resources\Learning\EnrollmentResource\RelationManagers;
use App\Models\Learning\Course;
use App\Models\Learning\Enrollment;
use App\Models\Learning\LearningEmployee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationLabel = 'Enrollment';
    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    protected static ?string $modelLabel = 'Enrollment';

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

            ->contentGrid([

                'xl' => 3,
            ])
            ->columns([
                Split::make([
                    Stack::make([
                        ImageColumn::make('image')
                            ->circular(),
                        TextColumn::make('title'),
                        TextColumn::make('description')
                            ->color('gray'),
                    ])
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Enroll')
                    ->action(function ($record) {
                        $isEnroll = LearningEmployee::where('course_id', $record->id)->where('user_id', auth()->id())->first();
                        if (is_null($isEnroll)) {
                        LearningEmployee::create([
                            'course_id' => $record->id,
                            'user_id' => auth()->id(),
                        ]);
                    }

                        return redirect()->route('filament.admin.resources.learning.my-learnings.course', ['record' => $record,'from']);
                    })
                    ->label(function ($record) {
                        $isEnroll = LearningEmployee::where('course_id', $record->id)->where('user_id', auth()->id())->first();
                        if (!is_null($isEnroll)) {
                            return 'Enrolled';
                        }
                    })
                // ->url(fn (Course $record): string => route('filament.admin.resources.learning.enrollments.course', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $query->withWhereHas('enrollmentCourse', function ($employee) {
            $employee->where('user_id', auth()->id());
        });


        return $query;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\Enroll::route('/{record}/course'),
        ];
    }
}
