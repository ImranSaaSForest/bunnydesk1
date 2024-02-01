<?php

namespace App\Filament\Resources\Learning\CourseResource\Pages;

use App\Filament\Resources\Learning\CourseResource;
use App\Models\Learning\Course;
use App\Models\Learning\EnrollmentCourse;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\Quiz;
use App\Models\Learning\QuizOption;
use App\Models\Learning\Video;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function afterFill(): void
    {
        $record=$this->getRecord();
    $videos = Video::where('course_id', $record->id)->with('videoProgress')->pluck('video_path');
    session()->put('videosList',$videos);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $employee = EnrollmentCourse::where('course_id', $data['id'])->get()->pluck('user_id')->toArray();
        if (count($employee) > 0) {
            $data['user_id'] = $employee;
        }
        $videos = Video::where('course_id', $data['id'])->get()->pluck('video_path')->toArray();
        if (count($videos) > 0) {
            $data['videos'] = $videos;
        }
        $quiz = Quiz::where('course_id', $data['id'])->with('options')->get();
        $data['Quiz Questions'] = $quiz;

        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'title' => $data['title'],
            'description' => $data['description'],

            'image' => $data['image'],
            'instructor' => $data['instructor'],
        ]);
        $course = $record;
        if (isset($data['user_id'])) {
            foreach ($data['user_id'] as $user) {
                $existingEmployees = EnrollmentCourse::where('course_id', $course->id)->pluck('user_id')->toArray();
                if (count($existingEmployees) > 0) {
                    $usersToDelete = array_diff($existingEmployees, $data['user_id']);
                    $usersToCreate = array_diff($data['user_id'], $existingEmployees);


                    if (!empty($usersToDelete)) {
                        EnrollmentCourse::where('course_id', $course->id)->whereIn('user_id', $usersToDelete)->delete();
                    }
                    if (!empty($usersToCreate)) {
                        foreach($usersToCreate as $user){
                            EnrollmentCourse::create([
                                'course_id' => $course->id,
                                'user_id' => $user,
                            ]);
                            $recipient = User::find($user);

                    Notification::make()
                        ->title('New Course added')
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url(fn (): string => route('filament.admin.resources.learning.enrollments.index'))
                                ->markAsRead(),
                        ])
                        ->sendToDatabase($recipient);
                        }
                    }

            }
        }
    }
        return $record;
    }
}
