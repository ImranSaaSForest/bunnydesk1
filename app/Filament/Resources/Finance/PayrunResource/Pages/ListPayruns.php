<?php

namespace App\Filament\Resources\Finance\PayrunResource\Pages;

use App\Filament\Resources\Finance\PayrunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPayruns extends ListRecords
{
    protected static string $resource = PayrunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
