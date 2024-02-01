<?php

namespace App\Filament\Resources\TimeOff;

use App\Filament\Resources\TimeOff\PendingRequestResource\Pages;
use App\Filament\Resources\TimeOff\PendingRequestResource\RelationManagers;
use App\Models\TimeOff\LeaveApproval;
use App\Models\TimeOff\Leave;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendingRequestResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $modelLabel = 'Pending Requests';

    protected static ?string $navigationGroup = 'TIME OFF';

    protected static ?string $navigationLabel = 'Pending Request';

    protected static ?string $slug = 'Request';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('leaveType.leaveType.name')
                    ->label('Time Off Types')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('leaveDates.leave_date')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('days_taken')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('forwarded')
                    ->label(function () {
                        if (auth()->user()->hasRole('Supervisor')) {
                            return 'forwarded';
                        }
                        if (auth()->user()->hasRole('Staff')) {
                            return 'forwarded';
                        }elseif (auth()->user()->hasRole('HR')) {
                            return 'approve';
                        }
                    })
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->mountUsing(fn (Forms\ComponentContainer $form) => $form->fill([
                        'status' => 'approved',
                        'comment' => 'Your leave request has been approved. '
                    ]))
                    ->action(function (Leave $record, array $data): void {

                        if (auth()->user()->hasRole('Supervisor')) {
                            LeaveApproval::create([
                                'leave_id' => $record->id,
                                'user_id' => auth()->user()->id,
                                'status' => 'forwarded',
                                // 'comments' => $data['comment'],
                                'comments' => 'Your leave request has been approved',
                            ]);
                        }
                        elseif (auth()->user()->hasRole('Staff')) {
                            LeaveApproval::create([
                                'leave_id' => $record->id,
                                'user_id' => auth()->user()->id,
                                'status' => 'forwarded',
                                // 'comments' => $data['comment'],
                                'comments' => 'Your leave request has been approved',

                            ]);
                        }elseif (auth()->user()->hasRole('HR')) {
                            LeaveApproval::create([
                                'leave_id' => $record->id,
                                'user_id' => auth()->user()->id,
                                'status' => 'approved',
                                // 'comments' => $data['comment'],
                                'comments' => 'Your leave request has been approved',

                            ]);
                        }


                        if (auth()->user()->hasRole('Supervisor')) {
                            $record->status = 'forwarded';
                        }
                        elseif (auth()->user()->hasRole('Staff')) {
                            $record->status = 'forwarded';
                        } elseif (auth()->user()->hasRole('HR')) {
                            $record->status = 'approved';
                        }

                        $record->save();
                        $recipient = User::where('id', $record->user_id)->get();

                        if (auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('Staff')) {
                            Notification::make()
                                ->title('Your leave request forwarded to HR')
                                ->actions([
                                    ActionsAction::make('view')
                                    ->button()->url('/time-off/leaves')
                                    ->close()
                                ])
                                ->sendToDatabase($recipient);
                        }
                        if (auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('Staff')) {
                            $recip = User::whereHas('roles', function ($q) {
                                $q->where('name', 'HR');
                            })->get();
                            if(count($recip) > 0){
                                foreach ($recip as $value) {
                                    $users = User::find($value->id);
                                    Notification::make()
                                        ->title('A new leave has been requested')
                                        ->actions([
                                            ActionsAction::make('view')
                                            ->button()->url('/Request')
                                            ->close()
                                        ])
                                        ->sendToDatabase($users);
                                }
                            }
                            
                            // if ($recip[0]->id) {
                            //     Notification::make()
                            //         ->title('A new leave has been requested')
                            //         ->actions([
                            //             ActionsAction::make('view')
                            //             ->button()->url('/Request')
                            //             ->close()
                            //         ])
                            //         ->sendToDatabase($recip);
                            // }
                        }
                        if (auth()->user()->hasRole('HR')) {
                            Notification::make()
                                ->title('Your leave request has been approved')
                                ->actions([
                                    ActionsAction::make('view')
                                    ->button()->url('/time-off/leaves')
                                    ->close()
                                ])
                                ->sendToDatabase($recipient);
                        }
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comments')
                            ->required()->disabled(),
                    ]),
                Action::make('reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->mountUsing(fn (Forms\ComponentContainer $form) => $form->fill([
                        'status' => 'denied',
                        'comment' => 'We regret to inform you that your leave request has been denied due to current project needs/workload. Please consider rescheduling your leave for a later date.'
                    ]))
                    ->action(function (Leave $record, array $data): void {
                        LeaveApproval::create([
                            'leave_id' => $record->id,
                            'user_id' => auth()->user()->id,
                            'status' => 'denied',
                            // 'comments' => $data['comment'],
                            'comments' => 'We regret to inform you that your leave request has been denied due to current project needs/workload. Please consider rescheduling your leave for a later date'
                        ]);

                        $record->status = 'denied';

                        $record->save();
                        $recipient = User::where('id', $record->user_id)->get();
                        if (!auth()->user()->hasRole('HR')) {
                        Notification::make()
                            ->title('Your leave request has been denied')
                            ->actions([
                                ActionsAction::make('view')
                                ->button()->url('/time-off/leaves')
                                ->close()
                            ])

                            ->sendToDatabase($recipient);
                        }
                        if (auth()->user()->hasRole('HR')) {
                            Notification::make()
                                ->title('Your leave request has been denied')
                                ->actions([
                                    ActionsAction::make('view')
                                    ->button()->url('/time-off/leaves')
                                    ->close()
                                ])
                                ->sendToDatabase($recipient);
                        }
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comments')->disabled()
                            ->required(),
                    ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('Supervisor')) {
            $query->whereDoesntHave('leaveApprovals', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })->where('status', 'pending');
            // dd($query->get());
        } else if (auth()->user()->hasRole('HR')) {
            // Show leaves that are approved by Supervisors to HR for further approval
            // $query->whereHas('leaveApprovals', function ($q) {
            //     $q->where('status', 'forward');
            // })->whereDoesntHave('leaveApprovals.user', function ($q) {
            // $q->whereHas('roles', function ($q) {
            //     $q->where('name', 'HR');
            // });
            // });
            $query->where('status', 'forwarded');
            // dd($query->get());
        } else {
            $query->orWhereHas('user.jobInfo', function (Builder $query) {
                $query->where('report_to', auth()->user()->id);
            })->where('status', 'pending');
        }

        return $query;
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendingRequests::route('/'),
            // 'create' => Pages\CreatePendingRequest::route('/create'),
            // 'edit' => Pages\EditPendingRequest::route('/{record}/edit'),
        ];
    }
}
