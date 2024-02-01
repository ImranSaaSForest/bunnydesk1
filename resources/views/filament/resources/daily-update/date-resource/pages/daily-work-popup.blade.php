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
    open: true,
    popup: 'fixed z-40 w-full',
}" :class="open == true ? popup : ''">
    {{-- Popup section start  --}}
    <div x-show="open" x-transition.duration.500ms class="fixed inset-0 z-40 bg-black/40">
        <div class="bg-white fixed inset-0 w-[1000px] h-[440px] rounded-xl m-auto overflow-y-auto">
            <div class=" px-6 h-full">
                <h1 class=" text-[26px] font-bold pt-4 pb-10">What did you work on yesterday, and what are you
                    planning to work on today?</h1>
                <form wire:submit="createWork" class="">
                    {{ $this->dailyForm }}

                    <div class=" flex gap-x-7 items-center mt-7">
                        <button class=" text-white bg-[#B39800] font-medium px-5 py-2 rounded-lg" type="submit">
                            submit
                        </button>
                        <button wire:click="cancel"
                            class=" text-[#1F2937] border border-[#D1D5DB] font-medium px-5 py-2 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)]"
                            type="reset">
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
