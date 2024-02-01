<?php

namespace App\Filament\Resources\DailyUpdate\DailyWorkResource\Pages;

use App\Filament\Resources\DailyUpdate\DailyWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyWork extends CreateRecord
{
    protected static string $resource = DailyWorkResource::class;
}
