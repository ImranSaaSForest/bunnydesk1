<?php

namespace App\Filament\Resources\Learning\EnrollmentResource\Pages;

use App\Filament\Resources\Learning\EnrollmentResource;
use App\Models\Learning\Course;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\Quiz;
use App\Models\Learning\QuizResponse;
use App\Models\Learning\Video;
use App\Models\Learning\VideoProgress;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

class Enroll extends Page implements HasForms, HasInfolists
{
    protected static string $resource = EnrollmentResource::class;

    protected static string $view = 'filament.resources.learning.enrollment-resource.pages.enroll';
    #[Locked]
    public $record;
    public $course;
    public $videos;
    public $courseProgress;
    protected static ?string $title = 'Course';

    public function mount()
    {
        $this->course = Course::findOrFail($this->record);
        self::$title=$this->course->title;

        $this->videos = Video::where('course_id', $this->record)->with(['videoProgress' => function ($query) {
            $query->where('user_id', auth()->id());
        }])->get();

        $this->courseProgress = LearningEmployee::where('course_id', $this->course->id)->where('user_id', auth()->id())->first();
    }
    // protected function getHeaderActions(): array
    // {
    //     $courseId = $this->record;
    //     return [
    //         Action::make('Quiz Overview')
    //             ->color('gray')
    //             ->url(fn (): string => route('filament.admin.resources.learning.my-learnings.result', ['record' => $this->course]))

    //             ->hidden(function () use ($courseId) {
    //                 $response = QuizResponse::where('user_id', auth()->id())->whereHas('quiz', function ($query) use ($courseId) {
    //                     $query->where('course_id', $courseId);
    //                 })->count();

    //                 if ($response <= 0) {
    //                     return true;
    //                 }
    //             }),
    //         Action::make('Take Quiz')
    //             ->url(fn (): string => route('filament.admin.resources.learning.my-learnings.quiz', ['record' => $this->course]))

    //             ->hidden(function () use ($courseId) {
    //                 $quizCount = Quiz::where('course_id', $courseId)->count();

    //                 if ($this->courseProgress) {
    //                     if ($this->courseProgress->progress < 100) {
    //                         return true;
    //                     }
    //                 } else {
    //                     return true;
    //                 }
    //                 $response = QuizResponse::where('user_id', auth()->id())->whereHas('quiz', function ($query) use ($courseId) {
    //                     $query->where('course_id', $courseId);
    //                 })->count();

    //                 if ($response > 0 || $quizCount <= 0) {
    //                     return true;
    //                 }
    //             })
    //         // ->url(route('posts.edit', ['post' => $this->post])),

    //     ];
    // }


    #[On('setWatchTime')]
    public function setWatchTime($percentage, $videoId)
    {
        if ($percentage && $videoId) {
            $videoProgress = VideoProgress::where('video_id', $videoId)->where('course_id', $this->course->id)->where('user_id', auth()->id())->get()->first();
            if (is_null($videoProgress)) {
                VideoProgress::create([
                    'course_id' => $this->course->id,
                    'user_id' => auth()->id(),
                    'video_id' => $videoId,
                    'progress' => $percentage
                ]);
            } else {
                $videoProgress->update([
                    'progress' => $percentage

                ]);
            }
            $courseProgress = VideoProgress::where('course_id', $this->course->id)->where('user_id', auth()->id())->get();
            $courseVideoCount = Video::where('course_id', $this->course->id)->count();
            if (!is_null($courseProgress)) {

                $totalProgress = $courseVideoCount <= 0 ? 0 : $courseProgress->sum('progress') / $courseVideoCount;
                $employee = LearningEmployee::where('course_id', $this->course->id)->where('user_id', auth()->id())->first();
                if ($employee) {
                    $employee->update([
                        'progress' => $totalProgress
                    ]);
                }
            }
        }
    }
    /**
     * List the Course Details
     *
     * @param  mixed $infolist
     * @return Infolist
     */
    public function courseInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->course)
            ->schema([


                Section::make()
                    ->schema([

                        Grid::make([
                            // 'default' => 1,
                            // 'sm' => 2,
                            // 'md' => 3,
                            // 'lg' => 4,
                            // 'xl' => 6,
                            // '2xl' => 8,
                        ])
                            ->columns(2)
                            ->schema([


                                TextEntry::make('title'),
                                TextEntry::make('duration'),

                                TextEntry::make('instructor'),

                                TextEntry::make('createdBy.name')
                                //     ->columnSpanFull()
                                //     ->label('Assigned By')

                            ]),


                        ImageEntry::make('image')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->grow(false),
                        TextEntry::make('description'),


                    ])


            ]);
    }
}
