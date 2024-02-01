<?php

namespace App\Filament\Resources\Employee\EmploymentResource\Pages;

use App\Filament\Resources\Employee\EmploymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployment extends CreateRecord
{
    protected static string $resource = EmploymentResource::class;
}
