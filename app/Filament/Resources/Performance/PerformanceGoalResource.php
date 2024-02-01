<?php

namespace App\Filament\Resources\Performance;

use App\Filament\Resources\Performance\PerformanceGoalResource\Pages;
use App\Filament\Resources\Performance\PerformanceGoalResource\RelationManagers;
use App\Models\Performance\AppraisalMessage;
use App\Models\Performance\PerformanceGoal;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use IbrahimBougaoua\FilamentRatingStar\Columns\RatingStarColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Actions\Action as ActionAC;
use Filament\Notifications\Actions\Action as NotificationsActionsAction;


class PerformanceGoalResource extends Resource
{
    protected static ?string $model = PerformanceGoal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'My Goal';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                ->default('not_started')
                ->options([
                    'not_started' => 'Not Started',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('createdBy.name')
             ->label('Assigned By')
             ->searchable()->sortable()->toggleable(),
            TextColumn::make('goal_description')->searchable()->sortable()->toggleable()->limit(30),
            TextColumn::make('start_date')->searchable()->sortable()->toggleable(),
            TextColumn::make('end_date')->searchable()->sortable()->toggleable(),
            ViewColumn::make('rating_score')->view('tables.columns.rating_score'),
            TextColumn::make('status')->searchable()->sortable()->toggleable(),
         ])
         ->filters([
             //
         ])
         ->recordUrl(
            function(Model $records){
                return route('filament.admin.resources.performance.performance-reviews.message',['random'=>serialize($records->id)]);
            })
         ->emptyStateHeading('No Goal')
         ->actions([
            //  Tables\Actions\EditAction::make()->visible(
            //      function(){
            //          if(auth()->user()->hasPermissionTo('Manage Performance Goals Edit')){
            //                      return true;
            //                  }
            //      }
            //  ),
            Tables\Actions\Action::make('Status Update')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\Select::make('status')
                                ->default('not_started')
                                ->options([
                                    'not_started' => 'Not Started',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                ])
                    ])
                    ->action(function ($data,$record): void {
                        $user = PerformanceGoal::find($record->id);
                        $user->update([
                            'status' => $data['status'],
                        ]);

                        if($data['status'] == 'in_progress'){
                            $recipient = User::where('id', $record->created_by)->get();
                            Notification::make()
                                ->title('The performance goal has been In Progress')
                                ->body($record->title)
                                ->actions([
                                    ActionAC::make('view')
                                        ->button()->url('/performance/goals')
                                ])
                                ->sendToDatabase($recipient);
                        }
                        if($data['status'] == 'completed'){
                            $recipient = User::where('id', $record->created_by)->get();
                            Notification::make()
                                ->title('The performance goal has been Completed')
                                ->body($record->title)
                                ->actions([
                                    ActionAC::make('view')
                                        ->button()->url('/performance/performance-reviews')
                                ])
                                ->sendToDatabase($recipient);
                        }

                    }),
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

                        // dd($messagesend->performance_goal_id);
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
        // if (auth()->user()->hasRole('Staff')) {
        //     $query->where('user_id',auth()->id());
        // }
        // else{
        //    $query;
        // }
        return $query->where('user_id',auth()->id());
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerformanceGoals::route('/'),
            // 'create' => Pages\CreatePerformanceGoal::route('/create'),
            // 'edit' => Pages\EditPerformanceGoal::route('/{record}/edit'),
        ];
    }
}
