<?php

namespace App\Filament\Resources\Timesheet\ProjectResource\Pages;

use App\Filament\Resources\Timesheet\ProjectResource;
use App\Models\Timesheet\Project;
use App\Models\Timesheet\ProjectTeam;
use App\Models\Timesheet\Task;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.timesheet.project-resource.pages.sort-projects';

    public $tab = 'overview';

    public function changeTab($val){
        // $val = request('tab');
        $this->tab = $val;
        // dd($val);
    }

    public function updateRec()
    {
        // dd($this->data['team_id']);
        Project::where('id', $this->data['id'])->update([
            'name' => $this->data['name'],
            'description' => $this->data['description'],
            'start_date' => $this->data['start_date'],
            'end_date' => $this->data['end_date']
        ]);
        if (isset($this->data['team_id'])) {
            foreach ($this->data['team_id'] as $user) {
                $existingTeam = ProjectTeam::where('project_id', $this->data['id'])->pluck('team_id')->toArray();
                if (count($existingTeam) > 0) {
                    $usersToDelete = array_diff($existingTeam, $this->data['team_id']);
                    $usersToCreate = array_diff($this->data['team_id'], $existingTeam);
                    if (!empty($usersToDelete)) {
                        ProjectTeam::where('project_id', $this->data['id'])->whereIn('team_id', $usersToDelete)->delete();
                    }
                    if (!empty($usersToCreate)) {
                        foreach ($usersToCreate as $user) {
                            ProjectTeam::create([
                                'project_id' => $this->data['id'],
                                'team_id' => $user,
                            ]);

                            // $recipient = User::find($user);
                            // Notification::make()
                            //     ->title('New Course added')
                            //     ->actions([
                            //         Action::make('view')
                            //             ->button()
                            //             ->url(fn (): string => route('filament.admin.resources.learning.enrollments.index'))
                            //             ->markAsRead(),
                            //     ])
                            //     ->sendToDatabase($recipient);
                        }
                    }
                }
            }
        }
        $this->redirect('/timesheet/projects');
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $project = User::whereHas('jobInfo.team', function ($query) use ($record) {
            $query->where('id', $record->team_id);
        })->get();
        foreach ($project as $projects) {
            $recipient = User::where('id', $projects->id)->get();
            //   dd($recipient);
            Notification::make()
                ->title('New project is assigned to your team ')
                ->sendToDatabase($recipient);
        }
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['team_id'] = ProjectTeam::where('project_id', $data['id'])->pluck('team_id');
        return $data;
    }
}
