<?php

namespace App\Livewire\Livechat;

use App\Events\MessageSend;
use App\Models\livechat\ChatRoom;
use App\Models\livechat\GroupMessage;
use App\Models\livechat\MemberChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Support\Facades\Storage;

class Chatapplication extends Component
{
    public $search;
    public $users;
    public $selectuser;
    public $message;
    public $viewchats;
    public $personalchat;
    public $userid;
    public $roomchatid;
    public $filename;
    public $originalname;
    public $chatedit;
    
    public function updated($property)
    {
        if ($property === 'search') {
            $this->users  = User::where('name', 'like', '%' . $this->search . '%')->whereNot('name', auth()->user()->name)->get();
        }
    }

    #[On('saveEditMessage')]
    public function editmessage($message,$messageId){
        $editMessage = GroupMessage::find($messageId);
        $editMessage->update([
            'content' => $message
        ]);
        $this->roomchatid = $editMessage->chat_room_id;
        event(new MessageSend($message));
    }

    public function deletechat($id){
        $message = "working";
        $item = GroupMessage::find($id);
        $item->delete();
        event(new MessageSend($message));

    }
    public function newuser($id)
    {
        $this->personalchat = "";
        $this->selectuser = User::find($id);
        $specificUserId = auth()->user()->id;
        $randomuser = $id;
        $totalchat = ChatRoom::whereNull('name')
            ->whereHas('members', function ($query) use ($randomuser, $specificUserId) {
                $query->whereIn('user_id', [$randomuser, $specificUserId]);
            }, '=', 2) // Ensure there are exactly 2 members
            ->get();
        // dd($totalchat);
        if (count($totalchat) > 0) {
            $this->roomchatid = $totalchat[0]->id;
            $this->personalchat = GroupMessage::where('chat_room_id',$this->roomchatid)->get()->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            })->toArray();
        } else {
            $this->personalchat = "";
        }
    }
    #[On('getData')]
    public function check()
    {
        // $this->personalchat = ChatRoom::with('groupmembers.chatuser')->find($this->roomchatid);
        $this->personalchat = GroupMessage::where('chat_room_id',$this->roomchatid)->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->toArray();
        // dd($random);
        // $roomId = $this->roomchatid;

        // // Retrieve the chat room with group members and associated chat users
        // $this->personalchat = ChatRoom::with('groupmembers.chatuser')
        //     ->find($roomId);

        // // Assuming you have a date variable, replace 'your_date_variable' with your actual date variable
        // $date = 'your_date_variable';

        // // Filter the group members based on the specified date
        // $this->personalchat->groupmembers = $this->personalchat->groupmembers
        //     ->where('created_at', '>=', $date . ' 00:00:00')
        //     ->where('created_at', '<=', $date . ' 23:59:59');

        // Optionally, you may want to load the chat users for the filtered group members
      
    }
    #[On('setFileNam')]
    public function FileUploade($filename, $originalname)
    {
        // dd($filename,$originalname);
        $this->filename = $filename;
        $this->originalname = $originalname;
        $message = null;
        // dd($this->selectuser);
        if ($this->selectuser != null) {

            $specificUserId = auth()->user()->id;
            $randomuser = $this->selectuser->id;
            $chats = ChatRoom::whereNull('name')
                ->whereHas('members', function ($query) use ($randomuser, $specificUserId) {
                    $query->whereIn('user_id', [$randomuser, $specificUserId]);
                }, '=', 2) // Ensure there are exactly 2 members
                ->get();
            // dd($chats);
            if (count($chats) > 0) {
                if ($message || $this->filename) {
                    GroupMessage::create([
                        'chat_room_id' => $chats[0]->id,
                        'user_id' => auth()->user()->id,
                        'content' => $message,
                        'filename' => $this->filename,
                        'originalfilename' => $originalname,
                    ]);
                    // $this->roomchatid = $chats[0]->id;
                    $this->personalchat = GroupMessage::where('chat_room_id',$chats[0]->id)->get()->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('Y-m-d');
                    })->toArray();
                    // $chats[0]->id
                    event(new MessageSend($message));
                    $message = '';
                    $this->filename = '';
                }
            } else {
                if ($message || $this->filename) {
                    $chatroom = ChatRoom::create([
                        'name' => null,
                    ]);
                    $participate = [auth()->user()->id, $this->selectuser->id];
                    // dd($participate);
                    foreach ($participate as $data) {
                        MemberChat::create([
                            'chat_room_id' => $chatroom->id,
                            'user_id' => $data
                        ]);
                    }
                    GroupMessage::create([
                        'chat_room_id' => $chatroom->id,
                        'user_id' => auth()->user()->id,
                        'content' => $message,
                        'filename' => $this->filename,
                        'originalfilename' => $this->originalname,

                    ]);
                    $this->personalchat = GroupMessage::where('chat_room_id',$chatroom->id)->get()->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('Y-m-d');
                    })->toArray();
                    event(new MessageSend($message));
                    $this->filename = '';
                    $this->message = '';
                }
            }
        }
    }
    #[On('sendmessage')]
    public function userinsert($message)
    {
        // dd($this->selectuser);
        if ($this->selectuser != null) {

            $specificUserId = auth()->user()->id;
            $randomuser = $this->selectuser->id;
            $chats = ChatRoom::whereNull('name')
                ->whereHas('members', function ($query) use ($randomuser, $specificUserId) {
                    $query->whereIn('user_id', [$randomuser, $specificUserId]);
                }, '=', 2) // Ensure there are exactly 2 members
                ->get();
            // dd($chats);
            if (count($chats) > 0) {
                if ($message || $this->filename) {
                    $rommId = GroupMessage::create([
                        'chat_room_id' => $chats[0]->id,
                        'user_id' => auth()->user()->id,
                        'content' => $message,
                        'filename' => $this->filename,
                        'originalfilename' => $this->originalname,
                    ]);
                    event(new MessageSend($message));
                    $message = '';
                    $this->filename = '';
                    $this->roomchatid = $rommId->chat_room_id;
                    $this->personalchat =  GroupMessage::where('chat_room_id',$rommId->chat_room_id)->get()->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('Y-m-d');
                    })->toArray();
                    //  ChatRoom::with('groupmembers.chatuser')->find($rommId->chat_room_id);
                }
            } else {
                if ($message || $this->filename) {
                    $chatroom = ChatRoom::create([
                        'name' => null,
                    ]);
                    $participate = [auth()->user()->id, $this->selectuser->id];
                    // dd($participate);
                    foreach ($participate as $data) {
                        MemberChat::create([
                            'chat_room_id' => $chatroom->id,
                            'user_id' => $data
                        ]);
                    }
                    GroupMessage::create([
                        'chat_room_id' => $chatroom->id,
                        'user_id' => auth()->user()->id,
                        'content' => $message,
                        'filename' => $this->filename,
                        'originalfilename' => $this->originalname,
                    ]);

                    $this->roomchatid = $chatroom->id;
                    $this->personalchat =  GroupMessage::where('chat_room_id',$chatroom->id)->get()->groupBy(function ($date) {
                        return Carbon::parse($date->created_at)->format('Y-m-d');
                    })->toArray();
                    event(new MessageSend($message));
                    $this->filename = '';
                    $this->message = '';
                }
            }
        }
    }
    public function viewchat()
    {
        $this->viewchats = ChatRoom::with('groupmembers.chatuser')->whereHas('members', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('name', null)->get();

        // $latestIds = GroupMessage::select(DB::raw('MAX(id) as latest_id'))
        // ->with('groupChat.members', function($query){
        //     $query->where('user_id',auth()->user()->id);
        // })
        // ->groupBy('chat_room_id')
        // ->pluck('latest_id');

        // $this->viewchats = GroupMessage::whereIn('id', $latestIds)->orderBy('created_at', 'desc')->get();


    }
    public function allchat($id, $userid)
    {
        $this->selectuser = User::find($userid);
        $this->userid = $userid;
        $this->roomchatid = $id;
        $this->personalchat = GroupMessage::with('chatuser')->where('chat_room_id',$id)->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->toArray();
    }
    public function render()
    {
        $this->viewchat();
        return view('livewire.livechat.chatapplication');
    }
    public function uploadLargeFiles(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

            $disk = Storage::disk(config('filesystems.default'));
            $path = $disk->putFileAs('videos', $file, $fileName);

            // delete chunked file

            unlink($file->getPathname());
            return [
                'path' => asset('storage/' . $path),
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
    public function downloadFile($messageId)
    {
        if ($messageId) {
            $messageRecord = GroupMessage::find($messageId);
            if ($messageRecord) {
                $filename = '/public/' . $messageRecord->filename;
                // $originalname = $messageRecord->original_attachment_name;
                if ($filename) {
                    // if ($originalname) {
                    // return Storage::download($filename, $originalname);
                    // } else {
                    return Storage::download($filename);
                }
            }
        }
    }
}

// $this->personalchat = ChatRoom::with(['groupmembers.chatuser', 'created_bys'])->find($this->roomchatid)
//                                 ->groupBy(function ($date) {
//                                     return Carbon::parse($date->created_at)->format('Y-m-d');
//                                 })
//                                 ->toArray();