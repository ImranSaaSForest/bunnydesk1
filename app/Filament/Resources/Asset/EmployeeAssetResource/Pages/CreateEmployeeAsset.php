<?php

namespace App\Filament\Resources\Asset\EmployeeAssetResource\Pages;

use App\Filament\Resources\Asset\EmployeeAssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeAsset extends CreateRecord
{
    protected static string $resource = EmployeeAssetResource::class;
}
