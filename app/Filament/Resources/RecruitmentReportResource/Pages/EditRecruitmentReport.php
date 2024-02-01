<?php

namespace App\Filament\Resources\RecruitmentReportResource\Pages;

use App\Filament\Resources\RecruitmentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecruitmentReport extends EditRecord
{
    protected static string $resource = RecruitmentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
