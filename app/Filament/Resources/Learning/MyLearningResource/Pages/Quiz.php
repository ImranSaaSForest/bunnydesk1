<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use App\Models\Learning\Course;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\Quiz as LearningQuiz;
use App\Models\Learning\QuizOption;
use App\Models\Learning\QuizResponse;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

class Quiz extends Page
{
    protected static string $resource = MyLearningResource::class;

    protected static string $view = 'filament.resources.learning.my-learning-resource.pages.quiz';
    public $questions;
    public $record;
    #[Locked]
    public $quizTime;
    public $answerOptions = [];
    public $currentQuestion = 0;

    public function mount()
    {
        $this->questions = LearningQuiz::where('course_id', $this->record)->with('options')->get();
        if( $this->record){
            $course=Course::find( $this->record);
                        $this->quizTime=$course->quiz_time;
                    }
    }
    public function incrementQuestion()
    {
        $this->currentQuestion++;
        // dd(1);
    }

    public function decrementQuestion()
    {

        $this->currentQuestion--;
    }
    #[On('timeOver')]
    public function timeOver(){
        $this->submit();
    }
    public function submit()
    {
        // dd($this->answerOptions);
        foreach ($this->answerOptions as $questionId => $answerOption) {
            $quiz =  QuizResponse::create([
                'quiz_id' => $questionId,
                'quiz_option_id' => $answerOption,
                'user_id' => auth()->id()
            ]);
        }

        $courseId = $this->record;
        if($courseId){
        $course=Course::find($courseId);
            $this->quizTime=$course->quiz_time;
        }
        $correctAnswerCount = 0;
        $incorrectAnswerCount = 0;
        $questions = LearningQuiz::where('course_id', $this->record)->with('options')->get();
        $responses = QuizResponse::where('user_id', auth()->id())->whereHas('quiz', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();
        $totalQuestionCount = count($questions);
        if($responses){

        foreach ($responses  as $response) {
            $correctAnswer = QuizOption::where('quiz_id', $response->quiz_id)->where('is_correct', 1)->first();
            if ($correctAnswer) {
                if ($response->quiz_option_id == $correctAnswer->id) {
                    $correctAnswerCount += 1;
                } else {
                    $incorrectAnswerCount += 1;
                }
            }
        }
    }

        $scorePercentage = ($correctAnswerCount / $totalQuestionCount) * 100;

        $learningEmployee=LearningEmployee::where('course_id', $this->record)->where('user_id', auth()->id())->first();
        $learningEmployee->update([
        'quiz'=>round($scorePercentage,2)

        ]);
        $course = Course::findOrFail($this->record);
        $recipient = User::findOrFail($course->created_by);
        Notification::make()
            ->title('Quiz Completed')
            ->success()
            ->body($course->title)
            ->actions([
                Action::make('view')
                    ->button()
                    ->url(fn (): string => route('filament.admin.resources.learning.my-learnings.generate-result', ['record' => $course->id, 'user_id' => auth()->id()]))
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipient);

        return redirect()->route('filament.admin.resources.learning.my-learnings.result', ['record' => $this->record]);
    }
}
