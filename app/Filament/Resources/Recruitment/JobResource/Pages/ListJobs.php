<?php

namespace App\Filament\Resources\Recruitment\JobResource\Pages;

use App\Filament\Resources\Recruitment\JobResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('View Careers Website')->color('gray')->url('/open-positions')->openUrlInNewTab(),
            Actions\CreateAction::make()->visible(function(){
                if(auth()->user()->hasRole('HR')){
                    return true;
                }
            }),
        ];
    }
}
