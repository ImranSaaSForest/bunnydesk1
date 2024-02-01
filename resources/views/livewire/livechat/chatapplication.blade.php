<div>
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js"
        integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    <div class=" flex">

        <div style="padding-right:15px;" class=" w-fit border-r border-[#D9DCE0] bg-white">
            <h2>Ping</h2>

            <div class="relative border-b border-black w-fit  ">
                <div class=" absolute" style="top: 9px;left:9px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input type="search" class=" border-none pl-10" placeholder="Search...." wire:model.live="search">

            </div>

            {{-- chat section start --}}

            {{-- chat 1 --}}
            @if ($search)
            @foreach ($users as $user)
            <div class=" flex justify-between border-b border-[#D1D5D8]" style="padding:10px;"
                onclick="scrollToBottom()" wire:click="newuser({{$user->id}})">
                <div class=" flex gap-x-3">
                    <img src="{{$user->employee->profile_picture_url}}" alt="" style="width: 48px;
                    height: 48px;border-radius: 48px;">
                    <div>
                        <h1>{{$user->name}}</h1>
                        {{-- <span class="text-[#6B7280]">Arun: Ok Bhai</span> --}}
                    </div>
                </div>
                <div>
                    <span class="text-[#6B7280]"></span>
                </div>
            </div>
            @endforeach
            @else
            {{-- @dd($viewchats) --}}
            @foreach ($viewchats as $user)
            @php
            // Assuming $user->groupmembers is a relationship or collection
            $lateschat = $user->groupmembers->sortByDesc('created_at')->first();
            @endphp
            @foreach ($user->members->where('user_id','!=',auth()->user()->id) as $item)
            <div onclick="scrollToBottom()" class="flex justify-between border-b border-[#D1D5D8]" style="padding:10px;"
                wire:click="allchat({{$user->id}},{{$item->user_id}})">
                <div class=" flex gap-x-3">
                    <img src="{{$item->user->employee->profile_picture_url}}" alt="" style="width: 48px;
                            height: 48px;border-radius: 48px;">
                    <div>
                        <h1>{{$item->user->name}}</h1>
                        {{-- @dd($lateschat->user_id == auth()->user()->id,$lateschat) --}}
                        <span class="text-[#6B7280]">
                            @if ($lateschat->user_id == auth()->user()->id)
                            You
                            @else
                            {{$lateschat->chatuser->name}}
                            @endif
                            :{{$lateschat->content}}</span>
                    </div>
                </div>
                <div>
                    <span class="text-[#6B7280]">
                        @php
                        $now = \Carbon\Carbon::now();
                        $chatDate = \Carbon\Carbon::parse($lateschat->created_at);

                        if ($chatDate->format('Y-m-d') == $now->format('Y-m-d')) {
                        $date = "Today";
                        }
                        elseif ($chatDate->format('Y-m-d') == $now->subDay()->format('Y-m-d')) {
                        $date = "Yesterday";
                        }
                        else {
                        $date = $chatDate->format('F j, Y'); // Or any other format you prefer for other days
                        }
                        @endphp
                        {{$date}}
                    </span>
                </div>
            </div>
            {{-- @endif --}}
            @endforeach
            @endforeach
            @endif

        </div>
        {{-- side open --}}

        <div class=" border-l border-[#6B7280]" style="width:100%;">
            <div class="flex items-center border-b border-[#D9DCE0]"
                style="gap: 15px;padding-left:30px;padding-bottom:22px;">
                {{-- <img src="/icon/profile.svg" alt="">
                <h1>Arun Kumar M</h1> --}}
                @if ($selectuser)
                <img src="{{$selectuser->employee->profile_picture_url}}" alt="" style="width: 48px;
            height: 48px;border-radius: 48px;">
                <h1>{{$selectuser->name}}</h1>
                @endif
            </div>

            {{-- chat convercation --}}
            <div class="" style="">
                <div class="w-full " style="width:100%;background-color:#F7F7F7;height:500px;overflow-y: scroll;"
                    id="messageCont">
                    {{-- <div class="flex justify-center" style="padding-top: 50px;">

                        <div>
                            <div style="border: 1px solid #C4C4C4;display:inline-block;width: 300px;margin: 3px;"></div>
                            <span style="color: #6B7280;">Today</span>
                            <div style="border: 1px solid #C4C4C4;display:inline-block;width: 300px;margin: 3px;"></div>
                        </div>

                    </div> --}}
                    @if($personalchat)
                    @foreach ($personalchat as $key => $value)
                    @php
                    $now = \Carbon\Carbon::now();
                    $chatDate = \Carbon\Carbon::parse($key);

                    if ($chatDate->format('Y-m-d') == $now->format('Y-m-d')) {
                    $date = "Today";
                    
                    }
                    elseif ($chatDate->format('Y-m-d') == $now->subDay()->format('Y-m-d')) {
                    $date = "Yesterday";
                    }
                    else {
                    $date = $chatDate->format('F j, Y'); // Or any other format you prefer for other days
                    }
                    @endphp
                    <span style="display: flex;justify-content: center;">{{$date}}</span>
                    
                    @foreach ($value as $personalchats)
                    @if ($personalchats['user_id'] != auth()->user()->id)
                    @php
                    $userImage = App\Models\User::find($personalchats['user_id'])   
                    @endphp
                    <div class=" flex gap-x-3" style="padding-top:50px;padding-left:30px;" x-data="{ open: false }">
                        {{-- {{$personalchats['chatuser']->employee->profile_picture_url}} --}}
                        <img src="{{$userImage->employee->profile_picture_url}}"
                            style="width: 48px;height: 48px;border-radius: 48px;" alt="" class=" object-fill">
                        @if($personalchats['content'])
                        <div class="bg-white w-fit" style="border-radius: 12px;">
                            <p class="text-[#09101D] " style="padding: 10px;">{{$personalchats['content']}}</p>
                            <span class=" text-[#6B7280] text-right block" style="padding: 13px;"
                                style="">{{ Carbon\Carbon::parse($personalchats['created_at'])->format('h:i A') }}</span>

                        </div>
                        @endif
                        @if ($personalchats['filename'])
                        <!-- FILE MESSAGE -->
                        <div class="flex items-center gap-2 py-2" style="border-radius: 12px;background-color:white;">

                            <!-- FILENAME -->
                            <div class="text-semibold underline cursor-pointer" style="margin-left: 19px;"
                                wire:click="({{$personalchats['id']}})">
                                {{$personalchats['originalfilename']}}
                            </div>

                            <!-- DOWNLOAD FILE -->
                            <x-heroicon-m-arrow-down-tray class="w-5 h-5 cursor-pointer"
                                wire:click="downloadFile({{$personalchats['id']}})" />
                            <span class=" text-[#6B7280] text-right block" style="padding: 13px;"
                                style="">{{ Carbon\Carbon::parse($personalchats['created_at'])->format('h:i A') }}

                            </span>
                        </div>
                        @endif
                        <div style="padding-top: 60px;" class=" relative">
                            <div class=" absolute right-[-10px]" style="background-color: beige;bottom:35px;"
                                id="options">
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($personalchats['user_id'] == auth()->user()->id)
                    <div class=" flex gap-x-3 justify-end" x-data="{ open: false, editbox:'', }"
                        style="background-color:#F7F7F7;padding-top:50px;padding-left:30px;">
                        <div style="padding-top: 60px;" class=" relative">
                            <svg x-on:click="open = ! open" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>

                            <div class=" absolute right-[-10px]"
                                style="background-color: beige;bottom:35px;margin-left: -40px;">

                                <div class=" flex items-center" x-show.important="open"
                                    x-on:click="editbox = {{$personalchats['id']}};open=false">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    <div>Edit</div>
                                </div>
                                @if ($personalchats['user_id'] == auth()->user()->id)

                                <div x-show.important="open" class="flex items-center"
                                    wire:click="deletechat({{$personalchats['id']}})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    <div>Delete</div>

                                </div>
                                @endif

                                {{-- <div x-show.important="open" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                    </svg>
                                    <div>Replay</div>
                                </div> --}}
                            </div>
                        </div>
                        @if($personalchats['content'])
                        <div class="" style="border-radius: 12px;background-color:#D8DEF1;">
                            {{-- <a href="/storage/videos/{{$personalchats->filename}}"
                                target="_blank">{{$personalchats->filename}}</a> --}}
                            <p class="text-[#09101D] " style="padding: 10px;">{{$personalchats['content']}}</p>
                            <span class=" text-[#6B7280] text-right block" style="padding: 13px;"
                                style="">{{ Carbon\Carbon::parse($personalchats['created_at'])->format('h:i A') }}
                            </span>
                            <span x-show.important="editbox">
                                <textarea style="height: 44px;" name="" id="editMessageInput{{$personalchats['id']}}"
                                    cols="30" rows="10" value="">{{$personalchats['content']}}</textarea>
                                <div>
                                    <button name="" id="" value="change" @click="editbox = false"
                                        onclick="saveEditMessage({{$personalchats['id']}})">Savechange</button>
                                    <button @click="editbox = false">cancal</button>
                                </div>
                            </span>
                        </div>
                        @endif
                        @if ($personalchats['filename'])
                        <!-- FILE MESSAGE -->
                        <div class="flex items-center gap-2 py-2" style="border-radius: 12px;background-color:#D8DEF1;">

                            <!-- FILENAME -->
                            <div class="text-semibold underline cursor-pointer" style="margin-left: 19px;"
                                wire:click="({{$personalchats['id']}})">
                                {{$personalchats['originalfilename']}}
                            </div>

                            <!-- DOWNLOAD FILE -->
                            <x-heroicon-m-arrow-down-tray class="w-5 h-5 cursor-pointer"
                                wire:click="downloadFile({{$personalchats['id']}})" />
                            <span class=" text-[#6B7280] text-right block" style="padding: 13px;"
                                style="">{{ Carbon\Carbon::parse($personalchats['created_at'])->format('h:i A') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                    @endforeach
                    @endif
                </div>
                <div id="fileUploadSuccess" style="display: none"
                    class="p-2 flex justify-between bg-green-500 rounded-lg text-white">
                </div>
                <div style="display: none" class="progress mt-3" style="height: 6px" wire:ignore>
                    <div style="background: linear-gradient(to left, #F2709C, #FF9472);box-shadow: 0 3px 3px -5px #F2709C, 0 2px 5px #F2709C;   border-radius: 20px;"
                        class="progress-bar py-[6px] progress-bar-striped  progress-bar-animated px-2 text-white"
                        role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                        style="width: 75%; height: 100%">75%
                    </div>
                </div>
                {{-- input --}}
                <div class=" relative">
                    {{-- emoji --}}
                    <div class=" absolute" style="top:10px;left: 10px;border-right:1px solid #5E5E5E;padding-right:5px;"
                        onclick="showEmoji()" wire:ignore x-data="{ open: false }">

                        <svg @click="open = ! open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                        </svg>
                        <div id="emoji" class="absolute bottom-21" style="bottom: 35px;" x-show="open"
                            @click.outside="open = false">
                        </div>

                    </div>
                    {{-- attachment --}}
                    <div id="parentMessage" class=" hidden absolute bottom-11  bg-[#F0F4F8] h-fit p-2 w-full">
                        <!-- parent message user -->
                        <div class="flex justify-between">
                            <div class="text-xs font-semibold" id="parentMessageUserName">

                            </div>
                            <x-heroicon-o-x-mark class="w-4 h-4 cursor-pointer" onclick="closeParentMessage()" />
                        </div>
                        <div id="parentMessageBody" class="text-xs">

                        </div>
                    </div>
                    <div class=" absolute" style="left: 50px;top:10px;">
                        <div id="fileContainer">
                            <div @click="$refs.open.click()" id="drag">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                </svg>

                            </div>

                            <!-- Interact with the `state` property in Alpine.js -->
                            <x-filament::input x-ref="open" required, type='file' , id="file-upload"
                                class="hidden py-1.5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.10)]" />
                        </div>
                    </div>
                    <div>

                        <input id="wage" type="text" placeholder="Type your message here..."
                            style="width: 100%;border-radius: 12px;border: 1px solid #D1D5D8; background: #FFF;padding-left:80px;">
                    </div>

                </div>
            </div>
        </div>

        {{-- emoji script --}}
        <script>
            function showEmoji(){
    console.log(document.getElementById('emoji').childNodes);
    if(document.getElementById('emoji').childNodes.length<2){
        const pickerOptions = { onEmojiSelect: displayEmoji }
    const picker = new EmojiMart.Picker(pickerOptions)
    // console.log(document.getElementById('messageContainer'));
    document.getElementById('emoji').appendChild(picker)
    }
}
function displayEmoji(data){
    console.log(data);
const messageInput=document.getElementById('wage')
const value = messageInput.value;
// save selection start and end position
const start = messageInput.selectionStart;
const end = messageInput.selectionEnd;
  // update the value with our text inserted
  messageInput.value = value.slice(0, start) + data.native + value.slice(end);
  // update cursor to be at the end of insertion
  messageInput.selectionStart = messageInput.selectionEnd = start + data.native.length;
  messageInput.focus();
// messageInput.value=
}


function sendEmoji(){
    console.log(document.getElementById("message").value);
     @this.dispatch('sendEmoji',{message:document.getElementById("message").value})
}

var wage = document.getElementById("wage");
wage.addEventListener("keydown", function (e) {
    if (e.code === "Enter") {  
        @this.dispatch('sendmessage',{message:document.getElementById("wage").value})
    document.getElementById("wage").value ="";//checks whether the pressed key is "Enter"
        const checkMessageContainer= setInterval(function(){
 var messageContainerWidth = $("#messageCont").width(); // find width

 if( messageContainerWidth > 0) {
     clearInterval(checkMessageContainer);
     $("#messageCont").animate({ scrollTop: 20000000 }, "slow");
     }
 }, 10);
    }
});

function scrollToBottom(){
const checkMessageCont= setInterval(function(){
var messageContainerWidth = $("#messageCont").width(); // find width
if( messageContainerWidth > 0) {
  clearInterval(checkMessageCont);
  $("#messageCont").animate({ scrollTop: 20000000 }, "slow");
  }
}, 10);
}

function messagesend(){
    // console.log(document.getElementById("wage1").value);
    @this.dispatch('sendmessage',{message:document.getElementById("wage").value})
    document.getElementById("wage").value ="";
    scrollToBottom();
 }
 var r = new Resumable({
        target: '/upload_doc',
        chunkSize: 1*1024*1024, // 1 MB per chunk, adjust as needed
        query: {_token: '{{ csrf_token() }}'},
        testChunks: false,
        throttleProgressCallbacks: 1,
        maxFileSize:500*1024*1024
        // minFileSize:
    });
    r.assignBrowse(document.getElementById('file-upload'));
    r.assignDrop(document.getElementById('drag'));
    r.on('fileAdded', function(file){
      console.log(file);
        showProgress();
        // Start uploading once a file is added
        r.upload();
    });
    r.on('fileProgress', function(file) {
        updateProgress(Math.floor(file.progress() * 100));
// Send the total number of chunks with each chunk
});
r.on('fileSuccess', function (file, response) {
  response = JSON.parse(response)
  console.log(response);
  let extension=response.mime_type.split("-")
      let filename=response.path + '/' + response.name
  @this.dispatch('setFileNam', { filename:filename,originalname:file.fileName});
});
    r.on('fileError', function(file, message){
        // Handle errors
        console.log(message);
    });
    let progress = $('.progress');
function showProgress() {
progress.find('.progress-bar').css('width', '0%');
progress.find('.progress-bar').html('0%');
progress.show();
}
function updateProgress(value) {
progress.find('.progress-bar').css('width', `${value}%`)
progress.find('.progress-bar').html(`${value}%`)
progress.find('.progress-bar').addClass('bg-green-500');
if (value === 100) {
    hideProgress()
}
}
function hideProgress() {
progress.hide();
}

function saveEditMessage(messageId){
        const editMessageValue=document.getElementById("editMessageInput"+messageId).value;
        if(editMessageValue){
            console.log(editMessageValue,messageId);
            @this.dispatch('saveEditMessage',{message:editMessageValue,messageId:messageId})
        }
    }


        </script>
    </div>
</div>