<?php

namespace App\Filament\Resources\Performance\PerformanceGoalResource\Pages;

use App\Filament\Resources\Performance\PerformanceGoalResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPerformanceGoal extends EditRecord
{
    protected static string $resource = PerformanceGoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    // protected function afterSave(): void
    // {
    //     $record = $this->getRecord();
    //     $recipient = User::where('id', $record->created_by)->get();
    //                     Notification::make()
    //                         ->title('The performance goal has been completed')
    //                         ->body($record->status)
    //                         ->sendToDatabase($recipient);
    // }
}
