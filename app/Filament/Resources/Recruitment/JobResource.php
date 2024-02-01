<?php

namespace App\Filament\Resources\Recruitment;

use App\Filament\Resources\Recruitment\JobResource\Pages;
use App\Filament\Resources\Recruitment\JobResource\Pages\JobView;
use App\Filament\Resources\Recruitment\JobResource\RelationManagers;
use App\Filament\Resources\Recruitment\JobResource\RelationManagers\CandidateRelationManager;
use App\Forms\Components\Required;
use App\Forms\Components\RequiredCheckbox;
use App\Models\Recruitment\Job;
use App\Models\Recruitment\JobAdditional;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Package\Countries;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Tables\Columns\ViewColumn;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $modelLabel = 'Job Openings';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        $countries = new Countries();
        $all = $countries
            ->all()
            ->map(function ($country) {
                $commonName = $country->name->common;

                $languages = $country->languages ?? collect();

                $language = $languages->keys()->first() ?? null;

                $nativeNames = $country->name->native ?? null;

                if (
                    filled($language) &&
                    filled($nativeNames) &&
                    filled($nativeNames[$language]) ?? null
                ) {
                    $native = $nativeNames[$language]['common'] ?? null;
                }

                if (blank($native ?? null) && filled($nativeNames)) {
                    $native = $nativeNames->first()['common'] ?? null;
                }

                $native = $native ?? $commonName;

                if ($native !== $commonName && filled($native)) {
                    $native = "$native ($commonName)";
                }

                return [$country->cca3 => $native];
            })
            ->values()
            ->toArray();;
        $countryList = [];
        foreach ($all as $alls) {
            foreach ($alls as $key => $value) {
                $countryList[$key] = $value;
            }
        }
        session(["datas" => JobAdditional::pluck('name', 'id')]);
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')->label('Job Title')->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        })
                       ,
                        // Select::make('onboarding_list_id')->relationship('onboardingIist', 'title')->label('Assign Onboarding List')->required()->disabled(function($record){
                        //     if(!is_null($record)){
                        //     if (auth()->user()->hasRole('HR')) {
                        //         // dd($record->job_status == "approved");
                        //         if($record->job_status == "approved"){

                        //             return true;
                        //         }
                        //         else{

                        //             return false;
                        //         }
                        //     }
                        //     else{
                        //         return true;
                        //     }}
                        // }),

                        Select::make('hiring_lead_id')->label('Hiring Lead')->required()->options(function(){
                            $user = User::whereHas('roles', function ($q) {
                                $q->where('name', 'Supervisor');
                            });
                            return $user->pluck('name', 'id');
                        })->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),

                        Select::make('designation_id')->relationship('Designation', 'name')->label('Position')->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        Select::make('employee_type_id')->relationship('employeeType', 'name')->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        Select::make('company_id')->relationship('Company', 'name')->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        Select::make('job_status')->options([
                            'draft' => 'Draft',
                            'open' => 'open',
                            'closed' => 'closed',
                            'forward' => 'forward',
                            'approved' => 'approved',
                            'rejected' => 'rejected',
                        ])->disableOptionWhen(function (string $value,$record): bool {
                            if (auth()->user()->hasRole('HR')) {
                                return $value === 'approved' || $value === 'forward' || $value === 'rejected';
                            }
                            if (auth()->user()->hasRole('Super Admin')) {
                                return $value === 'forward' ||  $value === 'draft' || $value === 'open'|| $value === 'closed';
                            }
                            if ($record->hiring_lead_id == auth()->user()->id) {
                                return $value === 'approved' ||  $value === 'draft' || $value === 'open'|| $value === 'closed';
                            }
                        })->required(),
                        Select::make('country')->options($countryList)->required()->searchable()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        TextInput::make('province')->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        TextInput::make('city')->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        TextInput::make('postal_code')->numeric()->minValue(0)->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        TextInput::make('salary')->numeric()->required()->minValue(0)->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        DatePicker::make('interview_date')->minDate(now())->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                        Textarea::make('description')->label('Job Description')->columnSpanFull()->required()->disabled(function($record){
                            if(!is_null($record)){
                            if (auth()->user()->hasRole('HR')) {
                                // dd($record->job_status == "approved");
                                if($record->job_status == "approved"){

                                    return true;
                                }
                                else{

                                    return false;
                                }
                            }
                            else{
                                return true;
                            }}
                        }),
                    ])->columns(2),
                        Section::make('Application Questions')
                        ->hiddenOn('edit')
                        ->schema([
                            // CheckboxList::make('technologies')->label('')
                            //     ->options(JobAdditional::pluck('name', 'id'))
                            //     ->columns(4)
                            // ViewField::make('technologies')->label('')
                                        
                            //             ->view('forms.components.required-checkbox'),
                                        RequiredCheckbox::make('technologies')->label('')
                    ])
                // Section::make('Application Questions')
                //         ->schema([
                //             Required::make('technologies')
                //                 ->options(JobAdditional::pluck('name', 'id')->toArray())
                //                 ->columns(4),
                //         ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->hasRole('Super Admin')) {
                    $query->where('job_status', 'forward')->orWhere('job_status', 'approved');
                } elseif (auth()->user()->hasRole('HR')) {
                    $query->where('created_by', auth()->user()->id);
                } 
                elseif (auth()->user()->hasRole('Supervisor')) {    
                    $query->whereNot('job_status', 'draft')->whereNot('job_status', 'closed')
                        ->where('hiring_lead_id', auth()->user()->id);
                }
            })
            ->columns([
                TextColumn::make('title')->label('Vacant')->searchable()->sortable(),
                TextColumn::make('Designation.name')->searchable()->sortable()->label('Position'),
                TextColumn::make('hiring.name')->label('Hiring Lead')->searchable()->sortable(),
                TextColumn::make('interview_date')->label('Created On')->searchable()->sortable(),
                // $jobStatusColumn->visible(fn () => Auth::check() && (Auth::user()->hasRole('HR') || Auth::user()->hasRole('Super Admin'))),
                SelectColumn::make('job_status')->label('Status')
                    ->disabled(
                        function (string $state): ?string {    
                            // if (auth()->user()->hasRole('HR')) {
                            //     if ($record->hiring_lead_id == auth()->user()->id) {
                            //         return false;
                            //     } else {
                            //         return $state == "forward";
                            //     }
                            // }
                            if (auth()->user()->hasRole('HR')) {
                                if ($state == 'open' || $state == 'draft' || $state == 'closed' || $state == 'rejected' || $state == 'approved')  {
                                    return false;
                                }
                                else{
                                    return true;
                                }
                            }
                            if (auth()->user()->hasRole('Supervisor')) {
                                if ($state == "rejected" || $state == "approved") {
                                    return true;
                                }
                                else{
                                    return false;
                                }
                            }
                            return false;
                        }
                    )
                    ->options([
                            'draft' => 'Draft',
                            'open' => 'open',
                            'closed' => 'closed',
                            'forward' => 'forward',
                            'approved' => 'approved',
                            'rejected' => 'rejected',
                    ]) ->selectablePlaceholder(false)->disableOptionWhen(function (string $value, $record): bool {
                        if (auth()->user()->hasRole('HR')) {
                            // if ($record->hiring_lead_id == auth()->user()->id && ($record['job_status'] == 'approved' || $record['job_status'] == 'open' ||$record['job_status'] == 'closed')) {
                            //     return $value === 'approved' || $value === 'forward' || $value === 'rejected' || $value === 'admin rejected';
                            // } else {
                                return $value === 'approved' || $value === 'forward' || $value === 'rejected';
                            // }
                        }
                        if (auth()->user()->hasRole('Super Admin')) {
                            return $value === 'forward' ||  $value === 'draft' || $value === 'open'|| $value === 'closed';
                        }
                        if ($record->hiring_lead_id == auth()->user()->id) {
                            return $value === 'approved' ||  $value === 'draft' || $value === 'open'|| $value === 'closed';
                        }
                    })
                    ->updateStateUsing(function (string $state,Model $record): ?string {
                        // dd($record); hiii
                        // dd($state);
                        if (auth()->user()->hasRole('HR')) {
                            if ($state == "open") {
                                $record->update([
                                    'job_status' => $state 
                                ]);
                                $recipient = User::where('id', $record->hiring_lead_id)->get();
                                Notification::make()
                                    ->title('Review')
                                    ->body("A new job role ".$record->title." has been created")
                                    ->actions([
                                        Action::make('view')
                                            ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close(),
                                        // Action::make('undo')
                                        //     ->color('gray')
                                        //     ->close()
                                    ])
                                    ->sendToDatabase($recipient);
                            }
                            if ($state == "draft") {
                                $record->update([
                                    'job_status' => $state
                                ]);
                            }
                            if ($state == "closed") {
                                $record->update([
                                    'job_status' => $state
                                ]);
                            }
                        }
                        
                        if (auth()->user()->hasRole('Super Admin')) {
                            $record->update([
                                'job_status' => $state,
                            ]);
                            if ($state == "rejected") {
                                $record->update([
                                    'approved_by' => null
                                ]);
                                $recipient = User::where('id', $record->hiring_lead_id)->get();
                                Notification::make()
                                    ->title('Rejected')
                                    ->body("A new job role ".$record->title." is rejected")
                                    ->actions([
                                        Action::make('view')
                                            ->button()->url('/recruitment/jobs/' . $record->id . '/edit')->close()
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
                            if ($state == "approved") {
                                $record->update([
                                    'approved_by' => auth()->user()->id
                                ]);
                                $recipient = User::where('id', $record->hiring_lead_id)->get();
                                Notification::make()
                                    ->title('Approved')
                                    ->body("A new job role ".$record->title." is approved")
                                    ->actions([
                                        Action::make('view')
                                            ->button()->url('/recruitment/jobs/sort?random=i%3A' . $record->id . '%3B')->close()
                                    ])
                                    ->sendToDatabase($recipient);
                                $random = User::where('id', $record->created_by)->get();
                                Notification::make()
                                    ->title('Approved')
                                    ->body("A new job role ".$record->title." is approved")
                                    ->actions([
                                        Action::make('view')
                                            ->button()->url('/recruitment/jobs/sort?random=i%3A' . $record->id . '%3B')->close()
                                    ])
                                    ->sendToDatabase($random);
                            }
                        }

                        if ($record->hiring_lead_id == auth()->user()->id) {
                            // dd($state,$record);
                            $record->update([
                                'job_status' => $state
                            ]);
                            if ($state == "rejected") {
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
                            if ($state == "forward") {
                                $recipient = User::whereHas('roles', function ($q) {
                                    $q->where('name', 'Super Admin');
                                })->first();
                                Notification::make()
                                    ->title('forwarded')
                                    ->body("A new job role ".$record->title." is forwarded")
                                    ->actions([
                                        Action::make('view')
                                            ->button()->url('/recruitment/jobs/' . $record->id . '/edit')
                                            ->close()
                                    ])
                                    ->sendToDatabase($recipient);

                                $random = User::where('id', $record->created_by)->get();
                                    Notification::make()
                                        ->title('forwarded')
                                        ->body("A new job role ".$record->title." is forwarded")
                                        ->actions([
                                            Action::make('view')
                                                ->button()->url('/recruitment/jobs/sort?random=i%3A' . $record->id . '%3B')->close()
                                        ])
                                        ->sendToDatabase($random);
                            }
                        }
                        return $state;
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                ActionsDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(
                function (Model $record) {
                    if (auth()->user()->hasRole('HR')) {
                        // dd($record->job_status == "draft");
                        if ($record->job_status == "draft" || $record->job_status == "open" || $record->job_status == "closed" || $record->job_status == "approved" || $record->job_status == "rejected") {
                            return '/recruitment/jobs/' . $record->id . '/edit';
                        }
                        else{
                            return route('filament.admin.resources.recruitment.jobs.sort', ['random' => serialize($record->id)]);
                        }
                        // if ($record->job_status == "approved" || $record->job_status == "open" || $record->job_status == "closed") {
                        //     return route('filament.admin.resources.recruitment.jobs.sort', ['random' => serialize($record->id)]);
                        // }
                    } elseif (auth()->user()->hasRole('Super Admin')) {
                        return '/recruitment/jobs/' . $record->id . '/edit';
                    } elseif($record->hiring_lead_id == auth()->user()->id) {
                        if($record->job_status == "approved" || $record->job_status == "rejected"){
                            return route('filament.admin.resources.recruitment.jobs.sort', ['random' => serialize($record->id)]);
                        }
                        if($record->job_status == "open" || $record->job_status == "forward"){
                            return '/recruitment/jobs/' . $record->id . '/edit';
                        }
                    }
                }
            );
    }

    public static function getRelations(): array
    {
        return [
            CandidateRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
            'sort' => JobView::route('/sort')
        ];
    }
}
