<?php

namespace App\Filament\Resources\Performance;

use App\Filament\Resources\Performance\ReviewResource\Pages;
use App\Filament\Resources\Performance\ReviewResource\Pages\Message as PagesMessage;
use App\Filament\Resources\Performance\ReviewResource\RelationManagers;
use App\Models\Employee\JobInfo;
use App\Models\Performance\Appraisal;
use App\Models\Performance\AppraisalMessage;
use App\Models\Performance\PerformanceApproval;
use App\Models\Performance\PerformanceGoal;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as NotificationsActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use IbrahimBougaoua\FilamentRatingStar\Actions\RatingStar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use GuzzleHttp\Psr7\Message;
use Illuminate\Database\Eloquent\Model;

class ReviewResource extends Resource
{
    protected static ?string $model = PerformanceGoal::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $modelLabel = 'Performance Review';

    protected static ?string $slug = 'performance/performance-reviews';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where('created_by',auth()->id())->where('rating_score',null)->where('status','completed')->count();
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned To')
                    ->searchable()->sortable()->toggleable(),
                TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('goal_description')->searchable()->sortable()->toggleable()->limit(30),
                Tables\Columns\TextColumn::make('start_date')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('end_date')->searchable()->sortable()->toggleable(),
                ViewColumn::make('rating_score')->view('tables.columns.rating_score'),

                Tables\Columns\TextColumn::make('status')
                    // ->enum([
                    //     'not_started' => 'Not Started',
                    //     'in_progress' => 'In Progress',
                    //     'completed' => 'Completed',
                    // ])
                    ->searchable()->sortable()->toggleable(),

                    TextColumn::make('performanceApprovals.comments')->visible(
                        function(){
                        if(auth()->user()->hasRole('Staff')) {
                            return true;
                        }
                    }
                ),
            ])
            ->filters([
                //
            ])
            ->recordUrl(
                function(Model $records){
                    return route('filament.admin.resources.performance.performance-reviews.message',['random'=>serialize($records->id)]);
                })
            ->actions([
                Tables\Actions\Action::make('Rating')
                    ->icon('heroicon-o-plus')
                    ->mountUsing(fn (Forms\ComponentContainer $form, PerformanceGoal $record) => $form->fill([
                        'rating_score' => $record->rating_score,
                        'comments' => $record->comments,
                    ]))
                    ->action(function (PerformanceGoal $record, array $data): void {

                        if (isset($data['rating_score']) && isset($data['comments'])) {
                            AppraisalMessage::create([
                                'performance_goal_id' => $record->id,
                                'message' => $data['comments'],
                                'default' => 1,
                                'date' => Carbon::now()
                            ]);
                            // PerformanceGoal::where()
                            $user = PerformanceGoal::find($record->id);
                            $user->update([
                                'rating_score' => $data['rating_score'],
                            ]);
                            $recipient = User::where('id', $record->user_id)->get();
                            Notification::make()
                            ->title('Your performance review is updated')
                            ->sendToDatabase($recipient);
                            // Appraisal::create([
                            //     'user_id' => $user->user_id,
                            //     'date' => Carbon::now(),
                            //     'total_score' => $data['rating_score'],
                            //     'final_comments' => $data['comments'],
                            // ]);
                        }
                        if (auth()->user()->hasRole('HR')) {
                            $record->rating_score = $data['rating_score'];
                            $record->save();
                        }

                    })->hidden(function($record){
                        if($record->rating_score){
                            return true;
                        }
                    })

                    ->form([
                        // Forms\Components\TextInput::make('rating_score')
                        //     ->label('Rating Score')->numeric()->minValue(1)->maxValue(10)
                        //     ->suffix('/10')
                        //     ->required(),
                        RatingStar::make('rating_score'),
                        Forms\Components\Textarea::make('comments')
                            ->label('Comments')
                            ->required(),
                    ]),
                    ActionsAction::make('message')->form([
                        TextInput::make('Comments')->columnSpanFull()
                    ])
                    ->action(function ($data,$record): void {
                        if (isset($data['Comments'])) {
                            $messagesend = AppraisalMessage::create([
                                'performance_goal_id' => $record->id,
                                'message' => $data['Comments'],
                                'date' => Carbon::now()
                            ]);
                            $currectuser = PerformanceGoal::find($messagesend->performance_goal_id);
                        if($currectuser->user_id == auth()->user()->id){
                            $recipient = User::where('id', $currectuser->created_by)->get();
                            Notification::make()
                                ->title('Message')
                                ->body(auth()->user()->name.' sent you a message')
                                ->actions([
                                    NotificationsActionsAction::make('view')
                                        ->button()->url('/performance/performance-reviews/message?random=i%3A'.$record->id.'%3B')->close()
                                ])
                                ->sendToDatabase($recipient);
                            }
                            if($currectuser->created_by == auth()->user()->id){
                                $recipient = User::where('id', $currectuser->user_id)->get();
                                Notification::make()
                                ->title('Message')
                                ->body(auth()->user()->name.' sent you a message')
                                ->actions([
                                    NotificationsActionsAction::make('view')
                                        ->button()->url('/performance/performance-reviews/message?random=i%3A'.$record->id.'%3B')->close()
                                ])
                                ->sendToDatabase($recipient);
                                }
                        }
                    })
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
        // if (auth()->user()->hasRole('Supervisor')) {
            // $query->whereDoesntHave('performanceApprovals.user', function ($q) {
            //     $q->whereHas('roles', function ($q) {
            //         $q->where('name', 'Supervisor');
            //     });
            // })->where('status', 'completed');
        //     dd($query->get());
        // } else if (auth()->user()->hasRole('HR')) {
        //     // Show leaves that are approved by Supervisors to HR for further approval
        //     $query->whereHas('performanceApprovals', function ($q) {
        //         $q->where('status', 'completed');
        //     })->whereDoesntHave('performanceApprovals.user', function ($q) {
        //         $q->whereHas('roles', function ($q) {
        //             $q->where('name', 'HR');
        //         });
        //     });
        // } else if (auth()->user()->hasRole('Staff')) {
        //     // Show leaves that are approved by Supervisors to HR for further approval
        //     $query->whereHas('performanceApprovals', function ($q) {
        //         $q->where('status', 'completed')->where('rating_score','>',0);
        //     });
        // } else {
        //     $query->orWhereHas('user.jobInfo', function (Builder $query) {
        //     $query->where('report_to', auth()->user()->id);
        //     })->where('status', 'completed');
        // }
        $query->where('created_by',auth()->id())->where('status', 'completed')->get();
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
            'message' => PagesMessage::route('/message')
        ];
    }
}
