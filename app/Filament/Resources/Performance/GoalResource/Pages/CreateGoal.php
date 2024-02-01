<?php

namespace App\Filament\Resources\Performance\GoalResource\Pages;

use App\Filament\Resources\Performance\GoalResource;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGoal extends CreateRecord
{
    protected static string $resource = GoalResource::class;
    
    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $recipient = User::where('id', $record->user_id)->get();
                        Notification::make()
                            ->title('A new performance goal has been added')
                            ->body($record->title)
                            ->actions([
                                Action::make('view')
                                    ->button()->url('/performance/performance-goals')
                            ])
                            ->sendToDatabase($recipient);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
