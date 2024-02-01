<?php

namespace App\Filament\Resources\Learning\MyLearningResource\Pages;

use App\Filament\Resources\Learning\MyLearningResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyLearning extends CreateRecord
{
    protected static string $resource = MyLearningResource::class;
}
