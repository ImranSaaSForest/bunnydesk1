<?php

namespace App\Filament\Resources\Finance\PendingReimbursementRequestResource\Pages;

use App\Filament\Resources\Finance\PendingReimbursementRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePendingReimbursementRequest extends CreateRecord
{
    protected static string $resource = PendingReimbursementRequestResource::class;
}
