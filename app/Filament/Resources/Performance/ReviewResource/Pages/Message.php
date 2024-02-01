<?php

namespace App\Filament\Resources\Performance\ReviewResource\Pages;

use App\Filament\Resources\Performance\ReviewResource;
use App\Models\Performance\AppraisalMessage;
use App\Models\Performance\PerformanceGoal;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Message extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $random;
    public $performance_goal;
    public $performance;

    protected static string $resource = ReviewResource::class;

    protected static string $view = 'filament.resources.performance.review-resource.pages.message';

    public function mount(){
        $this->random = unserialize($_GET['random']);
        $this->performance = PerformanceGoal::find($this->random);
        $this->performance_goal = $this->random;
    }
    public function productInfolist(Infolist $infolist): Infolist
    {
        if(isset($_GET['random'])){
        $this->random = unserialize($_GET['random']);
        $this->performance_goal = $this->random;
    }
        return $infolist
            ->record($this->performance)
            // ->record(function())
            ->schema([
                Section::make('')->schema([
                TextEntry::make('user.name')->label('Assigned To'),
                TextEntry::make('title'),
                TextEntry::make('goal_description')->columnSpanFull(),
                TextEntry::make('start_date'),
                TextEntry::make('end_date'),
                TextEntry::make('status'),
                ])->columns(2),
                Section::make('Ratings')->hidden(function($record){
                    $hide = AppraisalMessage::where('default',1)->where('performance_goal_id',$record->id)->get();
                    if(count($hide) == 0){
                       return true;
                    }
                })->schema([
                TextEntry::make('rating_score')->view('tables.columns.view-message'),
                TextEntry::make('appraisal.message')->default(function($record){
                    $message = AppraisalMessage::where('default',1)->where('performance_goal_id',$record->id)->get();

                    if(count($message) > 0){

                        return $message[0]['message'];
                    }
                })->columnSpanFull(),
                ])->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        // dd(AppraisalMessage::where('performance_goal_id',$this->performance_goal)->with('created_by')->get());
        return $table
        ->heading('Comments')
            ->query(AppraisalMessage::WhereHas('createdBy')->where('default',0)->where('performance_goal_id',$this->performance_goal)->orderBy('id', 'DESC'))
            ->columns([
                TextColumn::make('createdBy.name')->label('Name'),
                TextColumn::make('message')->label('Comments'),
                TextColumn::make('date'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                Action::make('Add Comments')
                    ->form([
                        TextInput::make('Comments')->columnSpanFull()
                    ])
                    ->action(function ($data): void {
                        if (isset($data['Comments'])) {
                            $messagesend = AppraisalMessage::create([
                                'performance_goal_id' => $this->performance_goal,
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
                                    ActionsAction::make('view')
                                        ->button()->url('/performance/performance-reviews/message?random=i%3A'.$currectuser->id.'%3B')->close()
                                ])
                                ->sendToDatabase($recipient);
                            }
                            if($currectuser->created_by == auth()->user()->id){
                                $recipient = User::where('id', $currectuser->user_id)->get();
                                Notification::make()
                                ->title('Message')
                                ->body(auth()->user()->name.' sent you a message')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->button()->url('/performance/performance-reviews/message?random=i%3A'.$currectuser->id.'%3B')->close()
                                ])
                                ->sendToDatabase($recipient);
                                }
                        }
                    })
            ])
            ->bulkActions([
                // ...
            ]);
    }

}
