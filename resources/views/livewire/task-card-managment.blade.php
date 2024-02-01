{{-- <x-filament-panels::page> --}}
<div>
    @assets
    <script src="https://cdn.tailwindcss.com"></script>
    @endassets

    <style>
        /* This is based on the work of Joshua Saunders */
        .list-layout {
            /* display: grid; */
            /* grid-gap: 10px; */
        }

        .board-text {
            font-weight: bold;
            font-size: 28px;
            padding: 5px;
        }

        .board-lists {
            /* display: grid; */
            /* display: flex; */
            /* grid-auto-columns: 275px; */
            /* grid-auto-flow: column; */
            /* grid-gap: 10px; */
            /* height: 100%; */
            /* background-color: red;
                height: 800px; */
        }

        .board-list {
            /*background-color: rgb(235, 236, 240);*/
            border-radius: 3px;
            display: grid;
            grid-auto-rows: max-content;
            grid-gap: 10px;
            /* Chrome use a fixed height */
            /* height: 300px; */
            /* padding: 10px; */
            /* background-color: red; */
            height: 800px;
        }

        .list-title {
            font-size: 18px;
            font-weight: bold;
        }

        /* #para{
                display: none !important;
            } */
        .card {
            /* background-color: white; */
            /* border-radius: 3px; */
            /* box-shadow: 0 1px 0 rgba(9, 30, 66, .25); */
            padding: 10px;
            cursor: pointer;
        }

        .scroll::-webkit-scrollbar {
            display: none;
        }
    </style>
    <div x-data="{
        value: @entangle('clicktap'),
        set: 'change text-[#E0BF00] font-medium py-2 px-5 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.16)]',
        first: 'cursor-pointer',
    }" wire.poll>
        <div class=" flex gap-x-10">
            <button class=" text-[#4B5563] font-medium" wire:click="OpenTap('1')"
                :class="value == '1' ? set : first">Board</button>
            <button wire:click="OpenTap('2')" :class="value == '2' ? set : first"
                class=" text-[#4B5563] font-medium">Chat</button>
            <button wire:click="OpenTap('3')" :class="value == '3' ? set : first"
                class=" text-[#4B5563] font-medium">Docs</button>
        </div>
        {{-- Task card Section --}}
        @if ($clicktap == '1')

            <div class=" pt-8">
                <img src="/icon/line.svg" alt="" class=" w-full">
                <div class="py-5 grid grid-cols-5">
                    <div class="list-title flex gap-x-10">
                        <span class="text-[#172B4D]">BACKLOG</span>
                        <span
                            class=" flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{ $count_assined }}</span>
                    </div>
                    <div class="list-title flex gap-x-10">
                        <span class="text-[#172B4D]">UNDER REVIEW</span>
                        <span
                            class=" flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{ $count_planing }}</span>
                    </div>
                    <div class="list-title flex gap-x-10">
                        <span class="text-[#172B4D]">IN PROGRESS</span>
                        <span
                            class="flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{ $count_inpro }}</span>
                    </div>
                    <div class="list-title flex gap-x-10">
                        <span class="text-[#172B4D]">READY FOR QA</span>
                        <span
                            class="flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{ $count_ready }}</span>
                    </div>
                    <div class="list-title flex gap-x-10 ml-2">
                        <span class="text-[#172B4D]">DONE</span>
                        <span
                            class="flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{ $count_done }}</span>
                    </div>
                </div>

                <div id='boardlists' class="board-lists  flex justify-between gap-2">
                    <div id='list0' class="board-list overflow-y-auto scroll h-40 w-[230px] " ondrop="dropIt(event)"
                        ondragover="allowDrop(event)">
                        {{-- <div class="list-title flex gap-x-10">
                    <span class="text-[#172B4D]">BACKLOG</span>
                    <span class=" flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{$count_assined}}</span>
                </div> --}}
                        @foreach ($project_assined as $assined)
                            <div class="border border-[#D1D5D8] rounded-[14px] p-5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] mt-10"
                                wire:key="{{ $assined->id }}" id='{{ $assined->id }}'
                                :draggable="{{ auth()->id() }} == {{ $assined->user_id }} || {{ auth()->id() }} ==
                                    {{ $assined->created_by }} ? 'true' : 'false'"
                                ondragstart="dragStart(event)" wire:click="open({{ $assined->task_id }})">
                                {{ substr($assined->name, 0, 18) }}<br>
                                {{ substr($assined->description, 0, 20) }}<br>
                                {{-- @dd($assined) --}}
                                <div class=" flex items-center mt-2" id="{{ $assined->id }}">
                                    <img src="{{ $assined->users->employee->profile_picture_url }}" alt=""
                                        class="h-10 w-10 mr-2 rounded-full">
                                    {{ $assined->users->name }}
                                    {{ \Carbon\Carbon::parse($assined->end_date)->format('d M y') }}
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div id='list1' class="board-list w-[230px]" ondrop="dropIt(event)"
                        ondragover="allowDrop(event)">
                        {{-- <div class="list-title flex gap-x-10">
                    <span class="text-[#172B4D]">UNDER REVIEW</span>
                    <span class=" flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{$count_planing}}</span>
                </div> --}}
                        @foreach ($planing as $plan)
                            <div class="border border-[#D1D5D8] rounded-[14px] p-5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] mt-10"
                                wire:key="{{ $plan->id }}" id='{{ $plan->id }}' class="card "
                                :draggable="{{ auth()->id() }} == {{ $plan->user_id }} || {{ auth()->id() }} ==
                                    {{ $plan->created_by }} ? 'true' : 'false'"
                                ondragstart="dragStart(event)" wire:click="open({{ $plan->task_id }})">

                                {{ substr($plan->name, 0, 18) }}..<br>
                                {{ substr($plan->description, 0, 20) }}..<br>
                                <div class=" flex items-center mt-2" id="{{ $plan->id }}">
                                    <img src="{{ $plan->users->employee->profile_picture_url }}" alt=""
                                        class="h-10 w-10 mr-2 rounded-full">
                                    {{ $plan->users->name }}
                                    {{ \Carbon\Carbon::parse($plan->end_date)->format('d M y') }}
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <div id='list2' class="board-list  w-[230px]" ondrop="dropIt(event)"
                        ondragover="allowDrop(event)">
                        {{-- <div class="list-title flex gap-x-10">
                    <span class="text-[#172B4D]">IN PROGRESS</span>
                    <span class="flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{$count_inpro}}</span>
                </div> --}}
                        @foreach ($project_inpro as $inprogress)
                            <div class="border border-[#D1D5D8] rounded-[14px] p-5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] mt-10"
                                wire:key="{{ $inprogress->id }}" id='{{ $inprogress->id }}' class="card"
                                :draggable="{{ auth()->id() }} == {{ $inprogress->user_id }} || {{ auth()->id() }} ==
                                    {{ $inprogress->created_by }} ? 'true' : 'false'"
                                ondragstart="dragStart(event)" wire:click="open({{ $inprogress->task_id }})">

                                {{ substr($inprogress->name, 0, 18) }}...<br>
                                {{ substr($inprogress->description, 0, 20) }}..<br>
                                <div class=" flex items-center mt-2" id="{{ $inprogress->id }}">
                                    <img src="{{ $inprogress->users->employee->profile_picture_url }}" alt=""
                                        class="h-10 w-10 mr-2 rounded-full">
                                    {{ $inprogress->users->name }}
                                    {{ \Carbon\Carbon::parse($inprogress->end_date)->format('d M y') }}
                                </div>

                            </div>
                        @endforeach
                    </div>
                    {{-- ready for qa --}}
                    <div id='list3' class="board-list  w-[230px]" ondrop="dropIt(event)"
                        ondragover="allowDrop(event)">
                        {{-- <div class="list-title flex gap-x-10">
                    <span class="text-[#172B4D]">READY FOR QA</span>
                    <span class="flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{$count_ready}}</span>
                </div> --}}
                        @foreach ($project_ready as $ready)
                            <div class="border border-[#D1D5D8] rounded-[14px] p-5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] mt-10"
                                wire:key="{{ $ready->id }}" id='{{ $ready->id }}' class="card"
                                :draggable="{{ auth()->id() }} == {{ $ready->user_id }} || {{ auth()->id() }} ==
                                    {{ $ready->created_by }} ? 'true' : 'false'"
                                ondragstart="dragStart(event)" wire:click="open({{ $ready->task_id }})">
                                {{ substr($ready->name, 0, 18) }}..<br>
                                {{ substr($ready->description, 0, 20) }}..<br>
                                <div class=" flex items-center mt-2" id="{{ $ready->id }}">
                                    <img src="{{ $ready->users->employee->profile_picture_url }}" alt=""
                                        class="h-10 w-10 mr-2  rounded-full">
                                    {{ $ready->users->name }}
                                    {{ \Carbon\Carbon::parse($ready->end_date)->format('d M y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- ready for qa end  --}}
                    <div id='list4' class="board-list  w-[230px]" ondrop="dropIt(event)"
                        ondragover="allowDrop(event)">
                        {{-- <div class="list-title flex gap-x-10">
                    <span class="text-[#172B4D]">DONE</span>
                    <span class="flex items-center justify-center  bg-[#E5E5E5] rounded-full h-6 w-6">{{$count_done}}</span>
                </div> --}}
                        @foreach ($project_done as $done)
                            <div class="border border-[#D1D5D8] rounded-[14px] p-5 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] mt-10"
                                wire:key="{{ $done->id }}" id='{{ $done->id }}' class="card"
                                draggable="true" ondragstart="dragStart(event)"
                                wire:click="open({{ $done->task_id }})">

                                {{ substr($done->name, 0, 18) }}...<br>
                                {{ substr($done->description, 0, 20) }}..<br>
                                <div class=" flex items-center mt-2" id="{{ $done->id }}">
                                    <img src="{{ $done->users->employee->profile_picture_url }}" alt=""
                                        class="h-10 w-10 mr-2 rounded-full">
                                    {{ $done->users->name }}
                                    {{ \Carbon\Carbon::parse($done->end_date)->format('d M y') }}
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        {{-- Figure out end --}}
        {{-- Task card section end --}}
        {{-- open model for card details view --}}

        <x-filament::modal alignment="center" id="open" width="3xl">

            <x-slot name="heading">
                {{ $project_name }}
            </x-slot>
            <b>Task Name</b>
            <x-filament::input.wrapper width="100px">
                <x-filament::input type="text" wire:model="task_name" disabled />
            </x-filament::input.wrapper>
            <b>Task Description</b>
            <textarea name="" id=""
                class="border-[#D1D5D8] border shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded-[14px] text-gray-500 "
                cols="40" rows="5" wire:model="task_desc" disabled>{{ $this->task_desc }}</textarea>
            <b>Assigner</b>
            <x-filament::input.wrapper>

                <x-filament::input type="text" wire:model="task_assiner" disabled />
            </x-filament::input.wrapper>

            {{-- Modal content --}}
        </x-filament::modal>


        {{-- open model for card details view --}}
    </div>
    @assets
    <script>
        function allowDrop(ev) {
            console.log(ev);
            ev.preventDefault(); // default is not to allow drop
        }

        function dragStart(ev, n) {
            ev.dataTransfer.setData("text/plain", ev.target.id, +"," + n);
        }

        function dropIt(ev) {
            ev.preventDefault(); // default is not to allow drop
            let sourceId = ev.dataTransfer.getData("text/plain");
            let sourceIdEl = document.getElementById(sourceId);
            let sourceIdParentEl = sourceIdEl.parentElement;
            let targetEl = document.getElementById(ev.target.id)
            // console.log(targetEl.id);
            let targetParentEl = targetEl.parentElement;
            if (targetParentEl.id !== sourceIdParentEl.id) {

                if (targetEl.className === sourceIdEl.className) {
                    let parentID = targetEl.parentElement;
                    Livewire.dispatch('post-created', {
                        postId: sourceId,
                        id: parentID.id
                    })
                    console.log(targetEl.id);
                    targetParentEl.appendChild(sourceIdEl);

                } else {
                    Livewire.dispatch('post-created', {
                        postId: sourceId,
                        id: targetEl.id
                    })
                    targetEl.appendChild(sourceIdEl);
                }
            } else {
                // console.log('no');
                // let holder = targetEl;
                // let holderText = holder.textContent;
                // targetEl.textContent = sourceIdEl.textContent;
                // sourceIdEl.textContent = holderText;
                // holderText = '';
            }
        }
    </script>
    @endassets
</div>
{{-- </x-filament-panels::page> --}}
