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
use Exception;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\Input;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
    public $courseData;
    public $totalChunks;
    #[Rule('required')]
    public $videoPaths = [];


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function uploadChunk(Request $request)
    {

        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            return $this->saveFile($save->getFile());
        }

        // we are in chunk mode, lets send the current progress
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    // get total chunk size
    #[On('totalChunks')]
    public function setTotalChunks($total)
    {
        $this->totalChunks = $total;
    }

    // get video paths from client side and store it to variable and session
    #[On('setVideoPath')]
    public function setVideoPath($videoPath, $duration)
    {

        $array = [];
        $array['path'] = $videoPath;
        $array['duration'] = $duration;
        array_push($this->videoPaths, $array);
        session()->put('videos', $this->videoPaths);
    }
    protected function beforeFill(): void
    {
        session()->forget('videos');

        // Runs before the form fields are populated with their default values.
    }


    /**
     * create Filename
     *
     * @param  mixed $file
     * @return string  filename
     */
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }
    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->createFilename($file);

        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());

        // Group files by the date (week
        $dateFolder = date("Y-m-W");

        // Build the file path
        $filePath = "upload/{$mime}/{$dateFolder}";
        $finalPath = "storage/" . $filePath;

        // move the file name
        $file->move($finalPath, $fileName);

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $this->courseData = $data;
        return static::getModel()::create($data);
    }
    protected function afterCreate(): void
    {
        $course = $this->getRecord();
        $courseSeconds = 0;

        $data = $this->courseData;

        // Runs after the form fields are saved to the database.

        if ($data['user_id']) {
            foreach ($data['user_id'] as $user) {
                EnrollmentCourse::create([
                    'course_id' => $course->id,
                    'user_id' => $user,
                ]);

            }
        }
        try {

        if ($this->videoPaths) {
            foreach ($this->videoPaths as $video) {


                $path = Storage::path('public/' . $video['path']);

                $duration = $video['duration'];

                $courseSeconds += $duration;

                Video::create([
                    'course_id' => $course->id,

                    'video_path' =>  $video['path']
                ]);
            }
        }
        $seconds = round($courseSeconds);
        $output = sprintf('%02d:%02d:%02d', ($seconds / 3600), ($seconds / 60 % 60), $seconds % 60);
        $course->update([
            'duration' => $output
        ]);

            if ($data['Quiz Questions']) {
                foreach ($data['Quiz Questions'] as $index=>$quiz) {
                    $number=$index+1;
                    $quiz['question']='<p>'.strval($number).'.'.substr($quiz['question'],3);
                    $question = Quiz::create([
                        'course_id' => $course->id,
                        'question' => $quiz['question'],
                    ]);

                    if ($quiz['options']) {
                        foreach ($quiz['options'] as $option) {
                            QuizOption::create([
                                'quiz_id' => $question->id,
                                'option' => $option['option'],
                                'is_correct' => $option['is_correct']
                            ]);
                        }
                    }
                }
            }
            if ($data['user_id']) {
                foreach ($data['user_id'] as $user) {

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

        } catch (Exception $e) {

            $course->delete();
            Notification::make()
                ->title('Sorry, we encountered an error while creating the course. Please try again later.')
                ->danger()
                ->send();
        }
    }


    protected function getFormActions(): array
    {
        return [];
    }
}
