<?php

namespace App\Filament\Resources\Timesheet\TaskResource\Pages;

use App\Filament\Resources\Timesheet\TaskResource;
use Filament\Actions;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;


class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

   
}
