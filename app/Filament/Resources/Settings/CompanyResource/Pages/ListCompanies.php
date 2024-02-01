<?php

namespace App\Filament\Resources\Settings\CompanyResource\Pages;

use App\Filament\Resources\Settings\CompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected static string $view = 'filament::pages.company-settings';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Add New Company'),
        ];
    }
}
