<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use App\Models\Learning\Course;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\Quiz;
use App\Models\Learning\QuizOption;
use App\Models\Learning\QuizResponse;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class GenerateResult extends Page implements HasForms,HasInfolists
{
    protected static string $resource = MyLearningResource::class;
    use InteractsWithForms;
    use InteractsWithInfolists;
    protected static string $view = 'filament.resources.learning.my-learning-resource.pages.generate-result';
    public $record;
    public $incorrectQuestions=[];
    public $questions;
    public $responses;
    public $totalQuestionCount;
    public $correctAnswerCount=0;
    public $incorrectAnswerCount=0;
    public $scorePercentage;
    public $feedback;
    public $userId;
    public $course;
    public $completionDate;
    public ?array $data = [];

    public function mount()
    {
        if(isset($_GET['user_id'])){
        $this->userId=$_GET['user_id'];
        $this->form->fill();
        $this->course=Course::findOrFail($this->record);

        $courseId = $this->record;
        $responses = QuizResponse::where('user_id', $this->userId)->whereHas('quiz', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();
        $this->completionDate=$responses->first()?$responses->first()->created_at:'';
        foreach ($responses  as $response) {
            $quiz=Quiz::findOrFail($response->quiz_id);
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
        $this->questions = Quiz::where('course_id', $this->record)->with('options')->get();
        $this->responses = QuizResponse::where('user_id', $this->userId)->whereHas('quiz',function ($query) use ($courseId) {
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
    // dd($this->incorrectQuestions);
    }

    public function getHeading(): string|Htmlable
    {
        $this->course=Course::findOrFail($this->record);
        return $this->course->title;
    }
    public function certificateInfolist(Infolist $infolist): Infolist
    {
        $score=LearningEmployee::where('course_id', $this->record)->where('user_id', $this->userId)->first();
// dd($score);
        return $infolist
        ->state([
            'Course Title' => $this->course->title,
            'Employee Name' => User::findOrFail($this->userId)->name,
            'Completion Date' => $this->completionDate,
            'Score' => $score->quiz,
            'Facilitator Name' => User::find($this->course->created_by)->name,
            // ...
        ])
        ->columns(2)
            ->schema([
              TextEntry::make('Course Title'),
              TextEntry::make('Employee Name'),
              TextEntry::make('Completion Date')
              ->date(),
              TextEntry::make('Score')
              ->suffix('%'),
              TextEntry::make('Facilitator Name')
            ]);
    }
public function generateCertificate(){
    // http://127.0.0.1:8000/learning/my-learnings/1/generate-result?user_id=3
    if($this->feedback){
        $employee=LearningEmployee::where('course_id', $this->record)->where('user_id', $this->userId)->first();
$employee->update([
    'feedback'=>$this->feedback
]);

    }
    $recipient = User::findOrFail($this->userId);
    Notification::make()
        ->title('Quiz Result & Download Certificate')
        ->success()
        ->body($this->course->title)
        ->actions([
            Action::make('view')
                ->button()
                ->url(fn (): string => route('filament.admin.resources.learning.my-learnings.quiz-feedback', ['record' => $this->course->id, 'user_id' => $this->userId]))
                ->markAsRead(),
        ])
        ->sendToDatabase($recipient);
        Notification::make()
        ->success()
    ->title('sent successfully')
    ->send();

}

}
