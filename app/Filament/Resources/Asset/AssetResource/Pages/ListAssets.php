<?php

namespace App\Filament\Resources\Asset\AssetResource\Pages;

use App\Filament\Resources\Asset\AssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(
                function(){
                    if(auth()->user()->hasPermissionTo('assets Create')){
                                return true;
                            }
                }
            ),
            
        ];
    }
}
