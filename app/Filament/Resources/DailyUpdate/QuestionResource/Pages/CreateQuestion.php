<?php

namespace App\Filament\Resources\DailyUpdate\QuestionResource\Pages;

use App\Filament\Resources\DailyUpdate\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;
}
