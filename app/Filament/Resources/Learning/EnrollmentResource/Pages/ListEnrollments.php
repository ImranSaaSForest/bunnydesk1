<?php

namespace App\Filament\Resources\Learning\EnrollmentResource\Pages;

use App\Filament\Resources\Learning\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;
    protected  ?string $heading = 'Enrollment';
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
