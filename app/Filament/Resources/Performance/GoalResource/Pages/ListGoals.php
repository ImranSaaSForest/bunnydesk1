<?php

namespace App\Filament\Resources\Performance\GoalResource\Pages;

use App\Filament\Resources\Performance\GoalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoals extends ListRecords
{
    protected static string $resource = GoalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(
                function(){
                    if(auth()->user()->hasPermissionTo('Manage Performance Goals Create')){
                                return true;
                            }
                }
            ),
        ];
    }
}
