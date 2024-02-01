<?php

namespace App\Filament\Resources\DailyUpdate\QuestionResource\Pages;

use App\Filament\Resources\DailyUpdate\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add New Status')
            ->url(fn (): string => route('filament.admin.resources.daily-update.questions.question')),
        ];
    }
}
