<x-filament-panels::page>

    <div>
        <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>


        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-semibold">Announcement</h1>
            <div>
                <button @click="$dispatch('open-modal', { id: 'feed' })"
                class=" text-sm text-white font-bold flex justify-end bg-[#2563EB] px-4 py-2 rounded-lg">Add New post</button>
            </div>
        </div>
       

        <x-filament::modal id="feed" slide-over width="4xl">
            <div>

                <h1 class="font-medium text-lg">Add Your Post</h1>
                
                <div class="border-b-2  mt-2 border-[#EFEFEF]">

                </div>
                {{-- <div class="flex gap-x-3">
    
                    <div>
                        <img src="{{ $auth->employee->profile_picture_url }}" alt="" class="rounded-full w-14 h-14">
                    </div>
                    <div class="flex items-center justify-center">
                        <h1 class=" font-bold text-xl">{{ $auth->name }}</h1>
                        <h1 class="font-bold text-xl px-1"> -
                            {{ $designation->jobInfo->designation ? $designation->jobInfo->designation->name : '' }}</h1>
                    </div>
    
                </div> --}}
    
                <form wire:submit.prevent="feedCreate" class="mt-6">
    
                    {{ $this->detailForm }}
                    <div class="mt-6 space-x-4">
                        <button class="bg-[#2563EB] px-4 py-2 text-white rounded-md" type="submit">Publish</button>
                        <a href="">
                            <button class=" border-[#D1D5DB] border  px-4 py-2 rounded-md">Cancel</button>
                        </a>
                    </div>
    
    
                </form>
            </div>
            </x-filament::modal>
      
        {{-- post content --}}
       

        {{-- post content --}}


        <div>
            {{-- <img src="/storage/1pnGIvMCSLyDeMoDzSKNESPKCeoBUyT3jn4pGdrT.png" alt="image"/> --}}

            @foreach ($post->sortByDesc('created_at') as $posts)
                <div class="border border-[#D1D5DB] bg-white rounded-md mt-10 p-5" x-data="{ open: false }">


                    <div class="flex justify-between items-center">
                        {{-- post viwer end --}}
                        <div class="flex gap-x-3">
                            <div class="">
                                <img src="{{ $posts->createdBy->employee->profile_picture_url }}" alt=""
                                    class="w-14 h-14 rounded-full">
                            </div>
                            <div class="flex items-center justify-center">
                                <h1 class="font-bold text-xl">{{ $posts->createdBy->name }}</h1>
                                <h1 class="font-bold text-xl px-1"> -
                                    {{ $posts->createdBy->jobInfo->designation ? $posts->createdBy->jobInfo->designation->name : '' }}
                                </h1>
                               
                                <h1 class=" pl-5 text-[#4B5563]">{{ Carbon\Carbon::parse($posts->created_at)->diffForHumans() }}
                                </h1>
                                {{-- <button >de</button> --}}
                            </div>

                        </div>
                        {{-- post viwer end --}}
{{-- @dd( $posts->createdBY->id) --}}
                       @if($posts->created_by==auth()->id())
                        <x-filament::icon alias="panels::topbar.global-search.field" icon="heroicon-o-trash" wire:click="postDetele({{$posts->id}})"
                            class="h-5 w-5  text-gray-500 ml-6 cursor-pointer dark:text-gray-400"/>
@endif
                    </div>


                    {{-- image --}}
                    <h1 class="mt-6"> {!! $posts->image !!}</h1>
                    <div class="flex gap-x-16 items-center">
                        @if ($posts->feedLike->count() > 0)
                            <div class="flex gap-x-2 items-center mt-3 cursor-pointer">

                                <span>{{ $posts->feedLike->count() > 0 ? $posts->feedLike->count() : '' }}</span>
                                <h1>likes</h1>

                            </div>
                        @endif

                        @if ($posts->feedComment->count() > 0)
                            <div class="flex gap-x-1 items-center mt-3 cursor-pointer" @click="open = ! open">
                                <span>View all</span>
                                <span>{{ $posts->feedComment->count() > 0 ? $posts->feedComment->count() : '' }}</span>
                                <span>comments</span>

                            </div>
                        @endif
                    </div>



                    <hr class="mt-3">



                    {{-- like $ comment --}}
                    <div class="flex  items-center gap-x-5 mt-3">
                        {{-- like --}}
                        <div class="flex cursor-pointer items-center gap-x-2"
                            wire:click="FeedLikes({{ $posts->id }})">
                            <x-filament::icon alias="panels::topbar.global-search.field" icon="heroicon-o-heart"
                                class="h-8 w-7  text-gray-500 dark:text-gray-400 {{ $posts->feedLike->where('user_id', auth()->id())->where('feeds_id', $posts->id)->count() > 0? 'fill-red-600 text-red-600': '' }} " />
                            {{-- <span class="font-medium  text-gray-500 ">Like</span> --}}
                        </div>
                        {{-- reply --}}
                        <div class="flex items-center gap-x-2  cursor-pointer" @click="open = ! open">
                            <x-filament::icon alias="panels::topbar.global-search.field"
                                icon="heroicon-o-chat-bubble-oval-left" wire:target="search"
                                class="h-8 w-7 text-gray-500 dark:text-gray-400" />
                            {{-- <span class="font-medium  text-gray-500 ">Comment</span> --}}

                        </div>
                        {{-- reply end --}}

                    </div>
                    {{-- like $ comment --}}



                    <div class="" x-show="open" @click.outside="open = false">
                        {{-- post viwer start --}}
                        <div class="flex gap-x-4 items-center mt-5">
                            <div class="">
                                <img src="{{ $posts->createdBy->employee->profile_picture_url }}" alt=""
                                    class="w-10 h-10 rounded-full">
                            </div>

                            <div class="flex items-center  w-full relative">
                                <input type="text" id="emoji-input" wire:model="postComments"
                                    placeholder="Add a comment..."
                                    wire:keydown.enter="displayfeed({{ $posts->id }})" class="w-full rounded-2xl "
                                    wire:keydown.shift.enter="insertNewLine">

                                <div x-data="{ open: false }" class=" absolute right-2">
                                    <x-filament::icon @click="open = ! open" alias="panels::topbar.global-search.field"
                                        icon="heroicon-o-face-smile"
                                        class="h-6 w-6 text-gray-500  dark:text-gray-400 cursor-pointer" />
                                    <div x-show="open" @click.outside="open = false" class="relative">

                                        <emoji-picker id="emojipicker-{{ $posts->id }}"
                                            class="absolute cursor-pointer right-2 top-4"></emoji-picker>

                                    </div>
                                </div>
                            </div>
                            {{-- input emoji --}}


                            <script>
                                // console.log('ki');
                                document.getElementById("emojipicker-{{ $posts->id }}").addEventListener('emoji-click', event => {
                                    // console.log('ji');
                                    const emojiInput = document.getElementById('emoji-input');
                                    // console.log(emojiInput);
                                    const clickedEmoji = event.detail.unicode;
                                    // console.log(clickedEmoji);
                                    // Append the clicked emoji to the current input value (or set it as the value)
                                    const emoji = emojiInput.value += clickedEmoji;
                                    // console.log(a);
                                    @this.set('postComments', emoji);


                                });
                            </script>

                            {{-- emoji --}}


                        </div>
                        {{-- post viwer start --}}

                        {{-- comment display --}}
                        <div>
                            @foreach ($posts->feedComment->whereNull('parent_id')->sortByDesc('created_at') as $allComments)
                                {{-- post viwer start --}}
                                <div class="flex items-center justify-between">

                                    <div class="flex gap-x-4  mt-5">
                                        <div>
                                            <img src="{{ $allComments->createdBy->employee->profile_picture_url }}"
                                                alt="" class="w-10 h-10 rounded-full">
                                        </div>
                                        <div class="flex justify-center items-center">
                                            <h1 class="font-bold text-xl">{{ $allComments->createdBy->name }}</h1>
                                            <h1 class=" px-1"> -
                                                {{ $allComments->createdBy->jobInfo->designation ? $allComments->createdBy->jobInfo->designation->name : '' }}
                                            </h1>

                                            <div>
                                                <x-filament::icon alias="panels::topbar.global-search.field" icon="heroicon-o-trash"  wire:click="deletedcomment({{$allComments->id}})"
                                                    class="h-5 w-5  text-gray-500 ml-6 cursor-pointer dark:text-gray-400"/>
                                               </div>
                                                
                                        </div>

                                    </div>

                                    <div>
                                        <div class="flex items-center gap-x-2 cursor-pointer"
                                            wire:click="sublikes({{ $allComments->id }})">
                                            <x-filament::icon alias="panels::topbar.global-search.field"
                                                icon="heroicon-o-heart" wire:target="search"
                                                class=" h-5 w-5 text-gray-500 dark:text-gray-400  {{ $allComments->likesComment->where('user_id', auth()->id())->where('feed_comments_id', $allComments->id)->count() > 0? 'fill-red-600 text-red-600': '' }}" />
                                            {{-- <h1>l</h1> --}}
                                        </div>


                                    </div>


                                </div>{{-- post viwer start --}}


                                <div>
                                    <p class=" pl-16 mt-2">{{ $allComments->comment }}</p>
                                    {{--  comment start --}}
                                    <div x-data="{ open: false }">
                                        <div class="flex gap-x-1.5 items-center pl-12 mt-2 cursor-pointer">
                                            <x-filament::icon alias="panels::topbar.global-search.field"
                                                icon="heroicon-o-chat-bubble-oval-left-ellipsis" wire:target="search"
                                                class="h-5 w-5 text-gray-500 dark:text-gray-400 " />

                                            <div class="space-x-4 flex items-center">
                                                <div class="font-medium  text-gray-500  "
                                                    @click="open = ! open">Reply</div>

                                                @if ($allComments->likesComment->count() > 0)
                                                    <span
                                                        class="font-medium text-gray-500">{{ $allComments->likesComment->count() > 0 ? $allComments->likesComment->count() : '' }}
                                                        Likes</span>
                                                @endif
                                                {{-- <span >Likes</span> --}}
                                                <div
                                                    class=" text-[#4B5563]">{{ Carbon\Carbon::parse($posts->created_at)->diffForHumans() }}
                                            </div>
                                               
                                                
                                            </div>

                                        </div>

                                        <div x-show="open" class="mt-3" @click.outside="open = false">



                                            <div class="flex items-center ml-14 gap-x-3">
                                                <div class="pt-2">
                                                    <img src="{{ $posts->createdBy->employee->profile_picture_url }}"
                                                        alt="" class="w-8 h-8 rounded-full">
                                                </div>




                                                {{-- emoji start --}}
                                                <div class="flex items-center  w-[70%] relative">
                                                    <input type="text" id="emoji-input-one"
                                                        wire:model="nextComment"
                                                        wire:keydown.enter="childcomment({{ $allComments->id }})"
                                                        class="w-full rounded-2xl" placeholder="Add a comment...">
                                                    <div x-data="{ open: false }" class=" absolute right-2">
                                                        <x-filament::icon @click="open = ! open"
                                                            alias="panels::topbar.global-search.field"
                                                            icon="heroicon-o-face-smile"
                                                            class="h-6 w-6 text-gray-500  dark:text-gray-400 cursor-pointer" />
                                                        <div x-show="open" @click.outside="open = false"
                                                            class="relative">
                                                            <emoji-picker id="emojipickerone-{{ $allComments->id }}"
                                                                class="absolute cursor-pointer right-2 top-4"></emoji-picker>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <script>
                                                // console.log('hi');
                                                document.getElementById("emojipickerone-{{ $allComments->id }}").addEventListener('emoji-click', event => {
                                                    // console.log('hi');
                                                    const emojiInputOne = document.getElementById('emoji-input-one');
                                                    // console.log( emojiInputOne);
                                                    const clickedEmojiOne = event.detail.unicode;
                                                    // console.log(clickedEmojiOne)
                                                    const emojiOne = emojiInputOne.value += clickedEmojiOne;
                                                    // console.log(emojiOne);
                                                    @this.set('nextComment', emojiOne);
                                                });
                                            </script>

                                            {{-- emoji end --}}

                                            {{-- sub comment start --}}
                                            <div class=" ml-40">
                                                @foreach ($allComments->subfeeds->sortByDesc('created_at') as $item)
                                                    {{-- profile start --}}
                                                    {{-- @dd($item) --}}
                                                    <div class="flex items-center  justify-between">
                                                        <div class="flex gap-x-4 items-center mt-5">
                                                            <div>
                                                                <img src="{{ $allComments->createdBy->employee->profile_picture_url }}"
                                                                    alt="" class="w-6 h-6 rounded-full">
                                                            </div>
                                                            <div class="flex justify-center items-center">
                                                                <h1 class="font-bold text-sm">
                                                                    {{ $allComments->createdBy->name }}</h1>
                                                                <h1 class="px-1 text-sm"> -
                                                                    {{ $allComments->createdBy->jobInfo->designation ? $allComments->createdBy->jobInfo->designation->name : '' }}
                                                                </h1>

                                                                <div>
                                                                    <x-filament::icon alias="panels::topbar.global-search.field" icon="heroicon-o-trash"  wire:click="deletechildComment({{$item->id}})"
                                                                        class=" h-4 w-4  text-gray-500 ml-6 cursor-pointer dark:text-gray-400"/>
                                                                   </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="flex items-center gap-x-2 cursor-pointer"
                                                                wire:click="sublikes({{ $item->id }})">
                                                                <x-filament::icon
                                                                    alias="panels::topbar.global-search.field"
                                                                    icon="heroicon-o-heart" wire:target="search"
                                                                    class="h-5 w-5 text-gray-500 dark:text-gray-400 {{ $item->likesComment->where('user_id', auth()->id())->where('feed_comments_id', $item->id)->count() > 0? 'fill-red-600 text-red-600': '' }}  " />


                                                            </div>



                                                        </div>


                                                    </div>
                                                    {{-- profile end --}}
                                                    <h1 class=" pl-10">{{ $item->comment }}</h1>
                                                    <div class="flex gap-x-4 items-center mt-1">
                                                        @if ($item->likesComment->count() > 0)
                                                            <span
                                                                class="font-medium text-gray-500 pl-10  text-xs">{{ $item->likesComment->count() > 0 ? $item->likesComment->count() : '' }}
                                                                Likes</span>
                                                        @endif

                                                        <span
                                                            class=" text-[#4B5563] text-xs pr-5 ">{{ Carbon\Carbon::parse($posts->created_at)->diffForHumans() }}
                                                        </span>

                                                       

                                                      
                                                            
                                                    </div>
                                                @endforeach
                                            </div>
                                            {{-- sub comment end --}}

                                        </div>

                                    </div>
                                    {{-- comment end --}}


                                </div>
                            @endforeach
                        </div>
                        {{-- comment display end --}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>






</x-filament-panels::page>
