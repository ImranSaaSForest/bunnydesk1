<?php

namespace App\Filament\Resources\Learning;

use App\Filament\Resources\Learning\EnrollmentResource\Pages\Enroll;
use App\Filament\Resources\Learning\MyLearningResource\Pages;
use App\Filament\Resources\Learning\MyLearningResource\Pages\GenerateResult;
use App\Filament\Resources\Learning\MyLearningResource\Pages\LearningDetail;
use App\Filament\Resources\Learning\MyLearningResource\Pages\Quiz;
use App\Filament\Resources\Learning\MyLearningResource\Pages\QuizFeedback;
use App\Filament\Resources\Learning\MyLearningResource\Pages\QuizResult;
use App\Filament\Resources\Learning\MyLearningResource\Pages\ReviewAnswer;
use App\Filament\Resources\Learning\MyLearningResource\RelationManagers;
use App\Models\Learning\Course;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\Quiz as LearningQuiz;
use App\Models\Learning\QuizResponse;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MyLearningResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'My Learning';
    protected static ?string $modelLabel = 'My Learning';

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
            ->recordUrl(
                fn (Model $record): string => route('filament.admin.resources.learning.my-learnings.course', ['record' => $record]),
            )
            ->contentGrid([

                'xl' => 3,
            ])
            ->columns([
                Split::make([
                    Stack::make([
                        ImageColumn::make('image')
                            ->circular()
                            ->columnSpanFull(),
                        TextColumn::make('title'),
                        TextColumn::make('description')

                            ->color('gray'),
                        ViewColumn::make('learningEmployee.progress')
                            ->default('100')
                            ->view('tables.columns.progress-bar')

                    ])
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                ActionsAction::make('Take Quiz')
                    ->button()
                    ->url(fn ($record): string => route('filament.admin.resources.learning.my-learnings.quiz', ['record' => $record]))

                    ->disabled(function ($record) {
                        $courseProgress = LearningEmployee::where('course_id', $record->id)->where('user_id', auth()->id())->first();


                        if ($courseProgress) {
                            if ($courseProgress->progress < 100) {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    })
                    ->hidden(function ($record) {
                        $quizCount = LearningQuiz::where('course_id', $record->id)->count();

                        $response = QuizResponse::where('user_id', auth()->id())->whereHas('quiz', function ($query) use ($record) {
                            $query->where('course_id', $record->id);
                        })->count();

                        if ($response > 0 || $quizCount <= 0) {
                            return true;
                        }
                    }),
                ActionsAction::make('Download Certificate')
                    ->button()
                    ->url(fn ($record): string => route('filament.admin.resources.learning.my-learnings.quiz-feedback', ['record' => $record->id, 'user_id' => auth()->id()]))
                // ->url(fn (): string => route('filament.admin.resources.learning.my-learnings.quiz-feedback', ['record' => $this->course->id, 'user_id' => $this->userId]))

                    ->hidden(function ($record) {
                        $learningEmployee = LearningEmployee::where('course_id', $record->id)->where('user_id', auth()->id())->first();
// dd($learningEmployee);
                        if ($learningEmployee->count() > 0) {
                            if (!$learningEmployee->feedback) {
                                return true;
                            }
                        }else{
                            return true;
                        }

                    })
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
        $query->withWhereHas('learningEmployee', function ($employee) {
            $employee->where('user_id', auth()->id());
        });


        return $query;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyLearnings::route('/'),
            'create' => Pages\CreateMyLearning::route('/create'),
            'edit' => LearningDetail::route('/{record}/edit'),
            'quiz' => Quiz::route('/{record}/quiz'),
            'result' => QuizResult::route('/{record}/result'),
            'review-answer' => ReviewAnswer::route('/{record}/review-answer'),
            'generate-result' => GenerateResult::route('/{record}/generate-result'),
            'quiz-feedback' => QuizFeedback::route('/{record}/quiz-feedback'),
            'course' => LearningDetail::route('/{record}/course'),

        ];
    }
}
