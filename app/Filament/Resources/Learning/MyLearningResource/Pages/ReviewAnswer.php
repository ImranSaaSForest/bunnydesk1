<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use App\Models\Learning\Quiz;
use App\Models\Learning\QuizOption;
use App\Models\Learning\QuizResponse;
use Filament\Actions\Action;

use Filament\Resources\Pages\Page;

class ReviewAnswer extends Page
{
    protected static string $resource = MyLearningResource::class;
    protected  ?string $heading = 'Incorrect Answer';

    protected static string $view = 'filament.resources.learning.my-learning-resource.pages.review-answer';
    public $record;
    public $incorrectQuestions=[];
    public function mount()
    {
        $courseId = $this->record;
        $responses = QuizResponse::where('user_id', auth()->id())->whereHas('quiz', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();
        foreach ($responses  as $response) {
            $quiz=Quiz::find($response->quiz_id);
            $correctAnswer = QuizOption::where('quiz_id', $response->quiz_id)->where('is_correct', 1)->first();
            if ($correctAnswer) {

                if ($response->quiz_option_id != $correctAnswer->id) {
                    $wrongQuestion['question']=$quiz->question;
                    $wrongQuestion['correctAnswer']=$correctAnswer->id;
                    $wrongQuestion['incorrectAnswer']=$response->quiz_option_id;
                    $wrongQuestion['options']=QuizOption::where('quiz_id', $response->quiz_id)->get()->toArray();
                    array_push($this->incorrectQuestions,$wrongQuestion);
                }
            }
        }
    // dd($this->incorrectQuestions);
    }
    protected function getHeaderActions(): array
{
    return [
        Action::make('Return to Course Overview')

        ->url(fn (): string => route('filament.admin.resources.learning.my-learnings.edit', ['record' => $this->record]))
    ];
}

}
