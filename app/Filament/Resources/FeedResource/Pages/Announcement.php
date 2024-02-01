<?php

namespace App\Filament\Resources\FeedResource\Pages;

use App\Filament\Resources\FeedResource;
use App\Models\Feed;
use App\Models\FeedComments;
use App\Models\FeedLike;
use App\Models\Like;
use App\Models\User;
use App\Settings\GeneralSettings;
use Filament\Forms;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Mail;

class Announcement extends Page
{


    use Forms\Concerns\InteractsWithForms;
    protected static ?string $title = '';
    protected static string $resource = FeedResource::class;

    protected static string $view = 'filament.resources.feed-resource.pages.announcement';

    public ?array $data = [];
    public $auth;
    public $authName;
    public $designation;
    public $postImage;
    public $post;
    public $postComments;
    public $allComment;
    public $nextComment;
    public $count;
    public $postCommentCount;
    public $notification;
    public $emailNotification;



    public function detailForm(Form $form): Form
    {

        return $form

            ->schema([

                RichEditor::make('image')->label('')->required()

            ])
            ->statePath('data');
    }

    public function feedCreate()
    {
        $this->postImage = $this->detailForm->getState();
        // dd($this->postImage);
        Feed::create([
            'image' => $this->postImage['image'],

        ]);
        $this->post = Feed::get();



        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
        $this->detailForm->fill();

        if ($this->notification) {

            $recipient = User::all();
            //   dd($recipient);
            Notification::make()
                ->title('A new  feed is posted')
                ->sendToDatabase($recipient);
        }


        if ($this->emailNotification) {
            $recipient = User::all()->pluck('email', 'id')->toArray();
            // dd($recipients);
            $emailnotifications = $recipient;

            foreach ($emailnotifications as $data) {
                // dd($data);
                // $data=['name'=>'sandhiya@saasforest.com'];
                $value = ['name' => 'A new  feed is posted', 'url' => config('app.url') . '/feeds'];
                Mail::send('emailnotification', $value, function ($message) use ($data) {
                    $message->to($data)
                        ->subject('HRMS CONNECT');
                });
            }
        }


        return  redirect()->to('/feeds');
    }

    public function childcomment($id)
    {
        $this->validate([
            'nextComment' => 'required', // Add validation rule to ensure the comment is not empty
        ]);

        // dd($this->nextComment);
        $feed = FeedComments::find($id);

        // dd($feed);
        if (!empty($this->nextComment)) {
            FeedComments::create([
                'feed_id' => $feed->feed_id,
                'comment' => $this->nextComment,
                'parent_id' => $feed->id
            ]);
        }
        // dd('none');
        $this->reset([
            'nextComment'
        ]);
    }

    public function insertNewLine()
    {
        // dd('ki');
        // Perform the action when Shift + Enter keys are pressed
        // For example, insert a newline character
        $this->postComments .= "\n";
    }





    public function displayfeed($id)
    {

        $this->validate([
            'postComments' => 'required', // Add validation rule to ensure the comment is not empty
        ]);
        //  dd('ji');
        $user = FeedComments::where('created_by', auth()->id())->where('feed_id', $id)->first();
        // dd($user);
        $this->postCommentCount = FeedComments::where('feed_id', $id)->count();

        if (!empty($this->postComments)) {
            // $this->postComments = $this->postComments ?? '';
            FeedComments::create([
                'feed_id' => $id,
                'comment' => $this->postComments,

            ]);
        }

        $this->reset([
            'postComments'
        ]);
        // dd('done');

        return  redirect()->to('/feeds');
    }

    public function postDetele($id)
    {
        // dd($id);
        $feeds = Feed::find($id);
        //    dd($feeds);
        $feeds->delete();
        return  redirect()->to('/feeds');
    }


    public function deletedcomment($id)
    {

        $parentComment = FeedComments::with('subfeeds')->find($id);
        // dd($childComment);
        $parentComment->delete();
    }

    public function deletechildComment($id)
    {
        $childComment = FeedComments::find($id);
        // dd('ji');
        $childComment->delete();
    }



    public function FeedLikes($id)

    {
        // dd('done');
        $user = FeedLike::where('user_id', auth()->id())->where('feeds_id', $id)->first();
        $this->count = FeedLike::where('feeds_id', $id)->count();
        //  dd($count);

        if (!$user) {


            FeedLike::create([
                'feeds_id' => $id,
                'user_id' => auth()->id(),

            ]);
        } else {
            $user->delete();
        }
        $this->post = Feed::with('createdBy.employee', 'createdBy.jobInfo.designation', 'feedComment.subfeeds', 'feedLike')->get();
    }



    public function sublikes($id)
    {

        $user = Like::where('user_id', auth()->id())->where('feed_comments_id', $id)->first();

        $this->count = Like::where('feed_comments_id', $id)->count();
        //  dd($this->count);

        if (!$user) {
            Like::create([
                'feed_comments_id' => $id,
                'user_id' => auth()->id(),

            ]);
        } else {
            $user->delete();
        }

        $this->post = Feed::with('createdBy.employee', 'createdBy.jobInfo.designation', 'feedComment.subfeeds', 'feedLike')->get();
        // dd('done');

    }




    public function mount(): void
    {
        // $this->count=FeedLike::where('feeds_id',$id)->count();
        $generalSetting = app(GeneralSettings::class);
        $this->notification =  $generalSetting->app_notification;
        // dd($this->notification);
        $this->emailNotification = $generalSetting->email_notification;
        // dd( $this->emailNotification);
        $this->allComment = FeedComments::with('createdBy.employee', 'createdBy.jobInfo.designation', 'likesComment')->get();
        //    dd($this->allComment[0]->createdBy->name);


        //    dd($this->b->name);
        $this->post = Feed::with('createdBy.employee', 'createdBy.jobInfo.designation', 'feedComment.subfeeds', 'feedLike')->get();
        // dd(  $this->post[0]);


        $this->auth = User::whereId(auth()->id())->with('employee')->first();

        $this->designation = User::whereId(auth()->id())->with('jobInfo.designation')->first();
        // dd($this->designation->id);
        $this->detailForm->fill();
        static::authorizeResourceAccess();
    }



    /**
     * Get all form definitions.
     *
     * @return array
     */
    protected function getForms(): array
    {
        return [
            'detailForm',
        ];
    }
}
