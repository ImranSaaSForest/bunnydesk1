<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use App\Models\Learning\Quiz;
use App\Models\Learning\QuizOption;
use App\Models\Learning\QuizResponse;
use Filament\Resources\Pages\Page;

class QuizResult extends Page
{
    protected static string $resource = MyLearningResource::class;
    protected  ?string $heading = 'Result';
    protected static string $view = 'filament.resources.learning.my-learning-resource.pages.quiz-result';
    public $questions;
    public $responses;
    public $record;
    public $totalQuestionCount;
    public $correctAnswerCount=0;
    public $incorrectAnswerCount=0;
    public $scorePercentage;
    public function mount()
    {

        $courseId = $this->record;
        $this->questions = Quiz::where('course_id', $this->record)->with('options')->get();
        $this->responses = QuizResponse::where('user_id', auth()->id())->whereHas('quiz',function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();
        $this->totalQuestionCount=count($this->questions);
        foreach( $this->responses  as $response){
                $correctAnswer=QuizOption::where('quiz_id',$response->quiz_id)->where('is_correct', 1)->first();
                if($correctAnswer){
                    if($response->quiz_option_id==$correctAnswer->id){
                       $this->correctAnswerCount+=1;
                    }else{
                        $this->incorrectAnswerCount+=1;
                    }
                }
        }
        $this->scorePercentage=($this->correctAnswerCount/$this->totalQuestionCount)*100;


    }
    public function redirectToReviewAnswer(){
        return redirect()->route('filament.admin.resources.learning.my-learnings.review-answer', ['record' => $this->record]);
    }

    public function redirectToCourse(){
        return redirect()->route('filament.admin.resources.learning.my-learnings.edit', ['record' => $this->record]);
    }
}
