<?php

namespace App\Filament\Resources\Learning\CourseResource\Pages;

use App\Filament\Resources\Learning\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->hidden(function(){
                if(!auth()->user()->hasPermissionTo('Manage Course')){
                    return true;
                }
            }),
        ];
    }
}
