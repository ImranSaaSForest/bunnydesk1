<?php

namespace App\Filament\Resources\DailyUpdate\DateResource\Pages;

use App\Filament\Resources\DailyUpdate\DateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDate extends CreateRecord
{
    protected static string $resource = DateResource::class;
}
