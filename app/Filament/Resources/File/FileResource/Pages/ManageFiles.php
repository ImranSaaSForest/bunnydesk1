<?php

namespace App\Filament\Resources\File\FileResource\Pages;

use App\Filament\Resources\File\FileResource;
use App\Models\Company\Company;
use App\Models\File\File;
use Filament\Pages\Actions;

use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;


class ManageFiles extends ManageRecords
{
    protected static string $resource = FileResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(
                function(){
                    if(auth()->user()->hasPermissionTo('HR Toolkit Upload')){
                                return true;
                            }
                }
            )
            ->label('Upload')
            ->using(function (array $data): Model {

                $file = File::create([
                    'file_name' => $data['file_name'],
                ]);

                // Fetch the company based on the selected company id
                $company = Company::find($data['company_id']);

                // Associate the file with the selected company
                $company->files()->attach($file);

                return $file;
            })->createAnother(false),
        ];
    }


}
