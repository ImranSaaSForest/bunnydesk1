<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use App\Models\Learning\Course;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\Quiz;
use App\Models\Learning\QuizOption;
use App\Models\Learning\QuizResponse;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Livewire\Attributes\Locked;

class QuizFeedback extends Page
{
    protected static string $resource = MyLearningResource::class;
    #[Locked]
    public $record;
    public $incorrectQuestions = [];
    public $questions;
    public $responses;
    public $totalQuestionCount;
    public $correctAnswerCount = 0;
    public $incorrectAnswerCount = 0;
    public $scorePercentage;
    public $feedback;
    #[Locked]
    public $userId;
    public $course;
    public $completionDate;
    public $learningEmployee;

    protected static string $view = 'filament.resources.learning.my-learning-resource.pages.quiz-feedback';
    public function mount()
    {
        if (isset($_GET['user_id'])) {
            $this->userId = $_GET['user_id'];
            $this->form->fill();
            $this->course = Course::findOrFail($this->record);
            $this->learningEmployee = LearningEmployee::where('course_id', $this->record)->where('user_id', $this->userId)->first();

            $courseId = $this->record;
            $responses = QuizResponse::where('user_id', $this->userId)->whereHas('quiz', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->get();
            $this->completionDate = $responses->first() ? $responses->first()->created_at : '';
            foreach ($responses  as $response) {
                $quiz = Quiz::findOrFail($response->quiz_id);
                $correctAnswer = QuizOption::where('quiz_id', $response->quiz_id)->where('is_correct', 1)->first();
                if ($correctAnswer) {

                    // if ($response->quiz_option_id != $correctAnswer->id) {
                        $wrongQuestion['question'] = $quiz->question;
                        $wrongQuestion['correctAnswer'] = $correctAnswer->id;
                        $wrongQuestion['incorrectAnswer'] = $response->quiz_option_id;
                        $wrongQuestion['options'] = QuizOption::where('quiz_id', $response->quiz_id)->get()->toArray();
                        array_push($this->incorrectQuestions, $wrongQuestion);
                    // }
                }
            }
            $this->questions = Quiz::where('course_id', $this->record)->with('options')->get();
            $this->responses = QuizResponse::where('user_id', $this->userId)->whereHas('quiz', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->get();
            $this->totalQuestionCount = count($this->questions);
            foreach ($this->responses  as $response) {
                $correctAnswer = QuizOption::where('quiz_id', $response->quiz_id)->where('is_correct', 1)->first();
                if ($correctAnswer) {
                    if ($response->quiz_option_id == $correctAnswer->id) {
                        $this->correctAnswerCount += 1;
                    } else {
                        $this->incorrectAnswerCount += 1;
                    }
                }
            }
            $this->scorePercentage = ($this->correctAnswerCount / $this->totalQuestionCount) * 100;
        }
    }
    public function redirectToCourse()
    {
        return redirect()->route('filament.admin.resources.learning.my-learnings.edit', ['record' => $this->record]);
    }

    public function downloadCertificate($record)
    {
        $course = Course::findOrFail($record);
        $userId = $_GET['user_id'];
        $learningEmployee = LearningEmployee::where('course_id', $record)->where('user_id', $userId)->first();


        $responses = QuizResponse::where('user_id', $userId)->whereHas('quiz', function ($query) use ($record) {
            $query->where('course_id', $record);
        })->get();
        $completionDate = $responses->first() ? $responses->first()->created_at->format('d M Y') : '';

        $courseTitle = $course->title;
        $employeeName = User::findOrFail($userId)->name;
        $score = $learningEmployee->quiz;
$instructor=$course->instructor;
        $pdf = Pdf::loadView('certificateTemplate', compact(
            'courseTitle',
            'employeeName',
            'completionDate',
            'score',
            'instructor'
        ));
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->setPaper('a4', 'landscape');
        $pdf->set_paper(array(0,0,800,480));
        return $pdf->download();
    }
}
