<?php

namespace App\Filament\Resources\HelpDesk;

use App\Filament\Resources\HelpDesk\TicketResource\Pages;
use App\Filament\Resources\HelpDesk\TicketResource\RelationManagers;
use App\Models\HelpDesk\Ticket;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\StaticAction;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                ->required()
                ->columnSpanFull(),
                // Forms\Components\Select::make('assigned_agent_id'),
                // Forms\Components\Select::make('priority'),
                // Forms\Components\TextInput::make('resolution_comments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')->label('Ticket description'),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('resolution_comments'),
                Tables\Columns\TextColumn::make('status')


                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inprogress' => 'warning',
                        'closed' => 'danger',
                        'open' => 'success',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\Action::make('Update Ticket')->visible(function($record){
                    if(auth()->user()->hasPermissionTo('Ticket Management Update') && $record->user_id==auth()->id()){
                        return true;
                    }
                })
                ->icon('heroicon-o-plus')
                ->mountUsing(fn (Forms\ComponentContainer $form, Ticket $record) => $form->fill([
                    'status' => $record->status ,
                    'assigned_agent_id' => $record->assigned_agent_id ,
                    'priority' => $record->priority ,
                    'resolution_comments' => $record->resolution_comments ,
                    'description' => $record->description,
                ]))
                ->action(function (Ticket $record, array $data): void {
                    if (isset($data['assigned_agent_id'])) {
                    $old=$record->assigned_agent_id;

                        $record->assigned_agent_id = $data['assigned_agent_id'];
                    }

                    if (isset($data['resolution_comments'])) {
                        $record->resolution_comments = $data['resolution_comments'];
                    }

                    if (isset($data['status'])) {
                        $record->status = $data['status'];
                    }
                    if (isset($data['priority'])) {
                        $record->priority = $data['priority'];
                    }
                    $record->save();
                    $new=$data['assigned_agent_id'];
if($old!=$new){
                    $recipient = User::where('id',$new)->get();


                        Notification::make()
                            // ->title('Your leave request forward to HR')
                            ->title('You have a new ticket received')
                            ->actions([
                                Action::make('view')
                                    ->button()->url('/help-desk/tickets')->close()
                            ])
                            ->sendToDatabase($recipient);

                        }

                })
                ->form([
                    Forms\Components\Textarea::make('description')
                    ->required()
                    ->disabled(),
                    Forms\Components\Select::make('assigned_agent_id')
                    ->options(User::all()->pluck('name', 'id'))
                    ->label('Assignee')->required(),
                    Forms\Components\Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ]),

                    Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'inprogress' => 'Inprogress',
                        'closed' => 'Closed',
                    ]),

                    Forms\Components\Textarea::make('resolution_comments')
                    ->label('Comments'),

                ]),
                Tables\Actions\DeleteAction::make()
                ->visible(function(){
                    if(auth()->user()->hasPermissionTo('Ticket Management Delete')){
                        return true;
                    }
                }),

                Tables\Actions\Action::make('Update Status') ->visible(function($record){

                    if(auth()->id()==$record->assigned_agent_id){
                        return true;
                    }
                })
                ->icon('heroicon-o-plus')
                ->mountUsing(fn (Forms\ComponentContainer $form, Ticket $record) => $form->fill([
                    'status' => $record->status ,

                ]))
                ->action(function (Ticket $record, array $data): void {
                    if (isset($data['status'])) {
                        $record->status = $data['status'];
                    }

                    $record->save();
                    $recipient = User::where('id',$record->user_id)->get();


                    Notification::make('s')
                        // ->title('Your leave request forward to HR')
                        ->title('The Ticket is '.$record->status)
                        ->sendToDatabase($recipient);


                })
                ->form([
                    Forms\Components\Select::make('status')
                        ->default('notstarted')
                        ->options([
                            'open' => 'Open',
                        'inprogress' => 'Inprogress',
                        'closed' => 'Closed',
                        ]),


                ])
                ,


            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTickets::route('/'),
        ];
    }
}
