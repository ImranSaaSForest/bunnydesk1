<?php

namespace App\Filament\Resources\Timesheet\ProjectDocResource\Pages;

use App\Filament\Resources\Timesheet\ProjectDocResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\Request;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class CreateProjectDoc extends CreateRecord
{
    protected static string $resource = ProjectDocResource::class;
    protected static bool $canCreateAnother = false;

    #[Url(keep: true)]
    public $projectId;
    protected function mutateFormDataBeforeCreate(array $data): array
    {


        $data['project_id'] = $this->projectId;
        if (array_key_exists('original_file_name', $this->data)) {

            $data['original_file_name'] = $this->data['original_file_name'];
        }

        return $data;
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
        $originalFileName = $file->hashName();
        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        $type = explode("-", $mime);
        $fileName = $this->createFilename($file) . $file->extension();
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
            'mime_type' => $mime,

        ]);
    }

    #[On('setFileName')]
    public function setFileName($filename, $originalname)
    {
        // dd($this->data['file']=$filename);
        $this->data['file'] = $filename;
        $this->data['original_file_name'] = $originalname;

    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
