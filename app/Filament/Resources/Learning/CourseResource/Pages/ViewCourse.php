<?php

namespace App\Filament\Resources\Learning\CourseResource\Pages;

use App\Filament\Resources\Learning\CourseResource;
use App\Models\Learning\Course;
use App\Models\Learning\Video;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Locked;

class ViewCourse extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    protected static string $resource = CourseResource::class;

    protected static string $view = 'filament.resources.learning.course-resource.pages.view-course';
    #[Locked]
    public $record;
    public $videos;
    public $course;

    public function mount()
    {
        $this->course = Course::find($this->record);
        $this->videos = Video::where('course_id', $this->record)->with('videoProgress')->get();
    }
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

                                // TextEntry::make('createdBy.name')
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
