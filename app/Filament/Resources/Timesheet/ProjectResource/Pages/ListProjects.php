<?php

namespace App\Filament\Resources\Timesheet\ProjectResource\Pages;

use App\Filament\Resources\Timesheet\ProjectResource;
use App\Models\Employee\Team;
use App\Models\Timesheet\ProjectTeam;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function () {
                if (auth()->user()->hasRole('Supervisor')) {
                    return true;
                }
            })
                ->icon('heroicon-o-plus-circle')
                ->color(Color::Blue)
                ->slideOver()
                ->after(function ($record, $data) {
                    // dd($data['team_id']);
                    // $imr = ['1', '2', '4'];
                    foreach($data['team_id'] as $teamId){
                        ProjectTeam::create([
                        'project_id' => $record->id,
                        'team_id' => $teamId,
                    ]);
                    };
                }),
           
        ];
    }
}
