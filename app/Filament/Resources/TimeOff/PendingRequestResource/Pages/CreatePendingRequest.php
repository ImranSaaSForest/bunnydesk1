<?php

namespace App\Filament\Resources\TimeOff\PendingRequestResource\Pages;

use App\Filament\Resources\TimeOff\PendingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePendingRequest extends CreateRecord
{
    protected static string $resource = PendingRequestResource::class;
}
