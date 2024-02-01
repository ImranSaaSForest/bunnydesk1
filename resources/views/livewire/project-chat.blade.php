<div>
    @assets
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js"
        integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script>
        // function scrollToBottom(){
        const checkMessageCont= setInterval(function(){
        var messageContainerWidth = $("#messageCont").width(); // find width
        if( messageContainerWidth > 0) {
        clearInterval(checkMessageCont);
        $("#messageCont").animate({ scrollTop: 20000000 }, "slow");
        }
        }, 10);
        // }
        
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
    function messagesend(){
    // console.log(document.getElementById("wage1").value);
    @this.dispatch('sendmessage',{message:document.getElementById("wage").value})
    document.getElementById("wage").value ="";
    scrollToBottom();
    }
    </script>
    @endassets
   
    <div class="board-list w-[230px]" style="background-color: rgb(235, 236, 240);
    border-radius: 3px;
    overflow: auto;
    height: 500px;
    padding: 10px;width: 100%;" id="messageCont">
        @foreach ($this->viewChats as $key => $value)
        {{-- @dd($key,$value) --}}
        <h3 style="    margin: auto;
    margin-top: 21px;display: flex;
    align-items: center;
    justify-content: center;">
    <span class="text-[#6B7280]">
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
        
    </span>
    {{$date}}</h3>
        @foreach ($value as $chats)
        {{-- @dd($chats) --}}
        @if (auth()->user()->id != $chats['user_id'])
        {{-- LHS --}}
        <div>
            @php
                $userImage = App\Models\User::find($chats['user_id'])   
            @endphp
            <div style="display: flex;gap: 13px;">
                <div><img src="{{$userImage->employee->profile_picture_url}}" alt="" style="height: 50px;width: 50px;border-radius: 50px;">
                </div>
                <div>
                    <div style="display: flex;gap: 9px;">
                        <div><span>{{$chats['users']['name']}}</span></div>
                        <div><span style="color:#666666;padding-top: 6px;font-size: 9px;">{{ Carbon\Carbon::parse($chats['created_at'])->format('h:i A') }}</span></div>
                    </div>
                    <div><span style="color:#666666;">{{$chats['users']['job_info']['designation']['name']}}</span></div>
                    {{-- job_info employee->profile_picture_url--}}
                </div>
            </div>
            <p style="margin-left: 63px;
    width: 75%;">{{$chats['message']}}</p>
        </div>  
        @endif

        @if (auth()->user()->id == $chats['user_id'])
        <div>
            @php
                $userImage = App\Models\User::find($chats['user_id'])   
            @endphp
            <div style="display: flex;gap: 13px;justify-content: end;margin-top: 12px;align-items: center;">
                <div style="display: flex;gap: 9px;">
                    <div><span style="color:#666666;padding-top: 6px;font-size: 9px;">{{ Carbon\Carbon::parse($chats['created_at'])->format('h:i A') }}</span></div>
                    <div><span>Me</span></div>
                </div>
                <div><img src="{{$userImage->employee->profile_picture_url}}" alt="" style="height: 50px;width: 50px;border-radius: 50px;"></div>
            </div>
            <p style="display: flex;justify-content: end;margin-right: 27px;">{{$chats['message']}}</p>
        </div>
        @endif
        @endforeach

        @endforeach

    </div>
    <div class="" onclick="showEmoji()" wire:ignore x-data="{ open: false }" style="position: absolute;
    margin-top: 7px;
    margin-left: 7px;">

                        <svg @click="open = ! open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                        </svg>
                        <div id="emoji" class="absolute bottom-21" style="bottom: 35px;" x-show="open"
                            @click.outside="open = false">
                        </div>

                    </div>
    <x-filament::input.wrapper>
        <x-filament::input type="text" id="wage" style="margin-left: 20px;"/>
    </x-filament::input.wrapper>
    <div class="" style="position: absolute;right: 43px;margin-top: -28px;">
        <div onclick="messagesend()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                onclick="messagesend()" stroke="blue" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>
        </div>
    </div>

</div>