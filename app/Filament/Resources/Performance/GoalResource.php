<?php

namespace App\Filament\Resources\Performance;

use App\Filament\Resources\Performance\GoalResource\Pages;
use App\Filament\Resources\Performance\GoalResource\RelationManagers;
use App\Models\Performance\PerformanceGoal;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use IbrahimBougaoua\FilamentRatingStar\Actions\RatingStar;
use IbrahimBougaoua\FilamentRatingStar\Columns\RatingStarColumn;
use App\Filament\Resources\Performance\ReviewResource\Pages\Message as PagesMessage;
use App\Models\Performance\AppraisalMessage;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action as NotificationsActionsAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Column\RatingColumn;
use Illuminate\Database\Eloquent\Model;

class GoalResource extends Resource
{
    protected static ?string $model = PerformanceGoal::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';

    protected static ?string $modelLabel = 'Performance Goal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                // ->relationship('user', 'name')
                ->options(function(){
                    if (auth()->user()->hasRole('Super Admin')) {
                            $user = User::whereDoesntHave('roles', function ($q) {
                                $q->where('name', 'Super Admin');
                            });
                            return $user->pluck('name', 'id');
                    }
                    if (auth()->user()->hasRole('Supervisor')) {
                        $user = User::whereDoesntHave('roles', function ($q) {
                            $q->where('name', 'Super Admin')->orWhere('name','Supervisor');
                        });
                        return $user->pluck('name', 'id');
                }
                if (auth()->user()->hasRole('HR')) {
                    $user = User::whereDoesntHave('roles', function ($q) {
                        $q->where('name', 'Super Admin')->orWhere('name','Supervisor')->orWhere('name','HR');
                    });
                    return $user->pluck('name', 'id');
            }

                })
                ->label('Employee')
                ->required(),
                TextInput::make('title')->required(),
                Forms\Components\Textarea::make('goal_description')->columnSpanFull()
                ->required(),
                Forms\Components\DatePicker::make('start_date')->minDate(now()->subDays(1))
                ->required(),
                Forms\Components\DatePicker::make('end_date')
                ->required(),
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
               TextColumn::make('user.name')
                ->label('Assigned To')
                ->searchable()->sortable()->toggleable(),
                TextColumn::make('title')->searchable()->sortable(),
               TextColumn::make('goal_description')->searchable()->sortable()->toggleable()->limit(30),
               TextColumn::make('start_date')->searchable()->sortable()->toggleable(),
               TextColumn::make('end_date')->searchable()->sortable()->toggleable(),
            //    TextColumn::make('rating_score')->label('Rating'),
            //    RatingStarColumn::make('rating_score'),
               TextColumn::make('status')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(
                function(Model $records){
                    return route('filament.admin.resources.performance.performance-reviews.message',['random'=>serialize($records->id)]);
                })
            ->actions([
                Tables\Actions\EditAction::make()->visible(
                    function(){
                        if(auth()->user()->hasPermissionTo('Manage Performance Goals Edit')){
                                    return true;
                                }
                    }
                ),
                Tables\Actions\DeleteAction::make()->visible(
                    function(){
                        if(auth()->user()->hasPermissionTo('Manage Performance Goals Edit')){
                                    return true;
                                }
                    }
                ),
                ActionsAction::make('message')->form([
                    TextInput::make('Comments')->columnSpanFull()
                ])
                ->action(function ($data,$record): void {
                    if (isset($data['Comments'])) {
                      $messagesend =  AppraisalMessage::create([
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
        // if (auth()->user()->hasRole('Staff')) {
        //     $query->where('user_id',auth()->id());
        // }
        // else{
        //    $query;
        // }
        return $query->where('created_by',auth()->id());
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoals::route('/'),
            'create' => Pages\CreateGoal::route('/create'),
            'edit' => Pages\EditGoal::route('/{record}/edit'),
            'message' => PagesMessage::route('/message')
        ];
    }
}
