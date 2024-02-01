<?php

namespace App\Filament\Resources\Recruitment\CandidateResource\Pages;

use App\Filament\Resources\Recruitment\CandidateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCandidate extends ViewRecord
{
    protected static string $resource = CandidateResource::class;
    public function getHeading(): string
    {
        $name = $this->getRecord();
        return __($name->first_name . '' . $name->last_name);
    }
}
