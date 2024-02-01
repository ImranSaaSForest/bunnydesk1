<x-filament-panels::page>
    <style>
        ul {
            list-style-type: disc;
        }

        ol {
            list-style-type: decimal;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <div x-data="{
        open: false,
        popup: 'fixed z-40 w-full',
    }" :class="open == true ? popup : ''">
        {{-- <h1 class=" text-3xl font-bold">What did you work on yesterday, and what are you planning to work on
            today?</h1> --}}
        <form wire:submit="createWork" class="">
            {{ $this->workForm }}

            @if($this->isCurrentDate)
            <div class=" flex gap-x-7 items-center mt-7">
                <button class=" text-white bg-[#3B82F6] font-medium px-5 py-2 rounded-lg" type="submit" {{ auth()->id() == 1 ? 'disabled' : '' }}>
                    Submit
                </button>
                <button
                    class=" text-[#1F2937] border border-[#D1D5DB] font-medium px-5 py-2 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"
                    type="reset" {{ auth()->id() == 1 ? 'disabled' : '' }}>
                    Cancel
                </button>
            </div>
            @endif
        </form>

        {{-- Daily Update Section start  --}}
        @foreach ($dailyworks as $dailywork)
            <div class=" border-b border-[#D1D5D8] pb-7 mt-7">
                <div class=" flex items-start justify-between">
                    <div class=" flex items-center gap-x-2">
                        <div
                            class=" text-lg text-white bg-black w-11 h-11 font-medium flex justify-center items-center rounded-full">
                            <h1>{{ $dailywork->user->name[0] }}</h1>
                        </div>
                        <div>
                            <div class=" flex gap-x-1 items-end">
                                <h1 class=" text-lg font-medium">{{ $dailywork->user->name }}</h1>
                                {{-- {{\Carbon\Carbon::parse($dailywork->created_at)->format('H:i A')}} --}}
                                <h6 class=" text-xs text-[#AAAAAA] mb-[3px]">
                                    {{ $dailywork->created_at->format('h:i A') }}</h6>
                            </div>
                            <h4 class=" text-[#666666]">{{ $dailywork->user->jobInfo->designation->name }}</h4>
                        </div>
                    </div>
                    @if (auth()->id() == $dailywork->user->id)
                        <button @click="open = true" wire:click="editWork({{ $dailywork->id }})"
                            class=" text-[#1F2937]">Edit</button>
                    @endif
                </div>
                <div class=" pl-[55px] pt-2">{!! $dailywork->content !!}</div>
            </div>
        @endforeach
        {{-- Daily Update section end  --}}

        {{-- Popup section start  --}}
        <div x-show="open" x-transition.duration.500ms class="fixed inset-0 z-40 bg-black/40">
            <div @click.outside="open = false"
                class="bg-white fixed inset-0 w-[1000px] h-[440px] rounded-xl m-auto overflow-y-auto">
                <div class=" px-6 h-full">
                    <h1 class=" text-[26px] font-bold pt-4 pb-10">What did you work on yesterday, and what are you
                        planning to work on today?</h1>
                    <form wire:submit="updateWork" class="">
                        {{ $this->dailyForm }}
                        
                        <div class=" flex gap-x-7 items-center mt-7 pb-5">
                            <button class=" text-white bg-[#3B82F6] font-medium px-5 py-2 rounded-lg" type="submit"
                                @click="open = false">
                                Update My Answer
                            </button>
                            <button
                                class=" text-[#1F2937] border border-[#D1D5DB] font-medium px-5 py-2 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"
                                type="reset" @click="open = false">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Popup section end  --}}
        
    </div>
</x-filament-panels::page>
