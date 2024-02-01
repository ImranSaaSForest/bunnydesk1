<?php

namespace App\Filament\Resources\TimeOff\MyCompanyResource\Pages;

use App\Filament\Resources\TimeOff\MyCompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMyCompanies extends ManageRecords
{
    protected static string $resource = MyCompanyResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
