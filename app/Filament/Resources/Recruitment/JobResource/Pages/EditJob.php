<?php

namespace App\Filament\Resources\Recruitment\JobResource\Pages;

use App\Filament\Resources\Recruitment\JobResource;
use App\Models\Recruitment\JobJobAdditional;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class EditJob extends EditRecord
{
    public $number = [];
    public $sample = [];
    public $extra = [];
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // public function mount(int|string $record): void
    // {
    //    $this->number =  JobJobAdditional::where('job_id',$record)->get();
    // }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $data['user_id'] = auth()->id();
        $data['id'] =  JobJobAdditional::where('job_id', $data['id'])->get();
        foreach ($data['id'] as $datas) {
            array_push($this->number, $datas['job_additional_id']);
        }
        session(["filldata" => $this->number]);
        $data['technologies'] = $this->number;
        return $data;
    }
    protected function afterSave(): void
    {
        $record = $this->getRecord();
        if ($record->job_status == "Review") {
            $recipient = User::whereHas('roles', function ($q) {
                $q->where('name', 'Super Admin');
            })->get();
            Notification::make()
                ->title('Review')
                ->body($record->title . "is ready for review")
                ->actions([
                    Action::make('view')
                        ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
                ])
                ->sendToDatabase($recipient);
        }

        if (auth()->user()->hasRole('Super Admin')) {
            if ($record->job_status == "rejected") {
                $record->update([
                    'approved_by' => null
                ]);

                    $recipient = User::where('id', $record->hiring_lead_id)->get();
                    Notification::make()
                        ->title('Rejected')
                        ->body("A new job role ".$record->title." is rejected")
                        ->actions([
                            Action::make('view')
                                ->button()->url('/recruitment/jobs/sort?random=i%3A' . $record->id . '%3B')->close()
                        ])
                        ->sendToDatabase($recipient);

                    $create = User::where('id', $record->created_by)->get();
                        Notification::make()
                            ->title('Rejected')
                            ->body("A new job role ".$record->title." is rejected")
                            ->actions([
                                Action::make('view')
                                    ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
                            ])
                            ->sendToDatabase($create);
            }
            if ($record->job_status == "approved") {
                $record->update([
                    'approved_by' => auth()->user()->id
                ]);
                $recipient = User::where('id', $record->hiring_lead_id)->get();
                Notification::make()
                    ->title('approved')
                    ->body("A new job role ".$record->title." is approved")
                    ->actions([
                        Action::make('view')
                            ->button()->url('/recruitment/jobs/sort?random=i%3A' . $record->id . '%3B')->close()
                    ])
                    ->sendToDatabase($recipient);

                    $create = User::where('id', $record->created_by)->get();
                    Notification::make()
                        ->title('approved')
                        ->body("A new job role ".$record->title." is approved")
                        ->actions([
                            Action::make('view')
                                ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
                        ])
                        ->sendToDatabase($create);
            }
        }
        if (auth()->user()->hasRole('HR')) {
            $recipient = User::where('id', $record->hiring_lead_id)->get();
            Notification::make()
                ->title('Review')
                ->body("A new job role ".$record->title." has been created")
                ->actions([
                    Action::make('view')
                        ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
                ])
                ->sendToDatabase($recipient);
        }
        if ($record->hiring_lead_id == auth()->user()->id) {
            if ($record->job_status == "rejected") {
                $recipient = User::where('id', $record->created_by)->get();
                Notification::make()
                    ->title('Rejected')
                    ->body("A new job role ".$record->title." is rejected")
                    ->actions([
                        Action::make('view')
                            ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
                    ])
                    ->sendToDatabase($recipient);
                    }
                    if ($record->job_status == "forward") {
                 $Admin = User::whereHas('roles', function ($q) {
                                    $q->where('name', 'Super Admin');
                                })->first();
                                Notification::make()
                                    ->title('forward')
                                    ->body("A new job role ".$record->title." is forward")
                                    ->actions([
                                        Action::make('view')
                                            ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
                                    ])
                                    ->sendToDatabase($Admin);
                                    }
        }
        foreach($this->sample as $value){
            foreach($value as $values){
                JobJobAdditional::create([
                    'job_id' => $record->id,
                    'job_additional_id' => $values,
                    'required' => null,
                ]);
            }
        }

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // protected function mutateFormDataBeforeSave(array $data,): array
    // {
    //     $data['last_edited_by_id'] = auth()->id();
    //     dd($data);
    //     array_push($this->sample, $data['technologies']);
    //     return $data;
    // }

    // protected function beforeSave(): void
    // {
    //     $record = $this->getRecord();
    //     $this->extra = JobJobAdditional::where('job_id', $record->id)->delete();
    // }
}
