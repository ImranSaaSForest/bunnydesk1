<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        @if (session()->get('videosList'))
        <div>

            @foreach (session()->get('videosList') as $index=>$video)
            <div onclick="changeUrl('{{$video}}')" @click="$dispatch('open-modal', { id: 'open-video' })" 
                class="px-3 py-1.5 rounded bg-gray-500 text-white flex gap-2 cursor-pointer mt-1">{{$index+1}}. Video
            </div>
            @endforeach
        </div>
        @endif
        <x-filament::modal id="open-video" width="3xl" alignment="center" wire:ignore>
        <x-slot name="heading">
            Video
        </x-slot>
        {{-- Modal content --}}
        <div class="flex items-center justify-center w-full">

            <video id="my-video" class="video-js" controls preload="auto" width="640" height="264"
                data-setup="{fluid:true}"></video>
        </div>
    </x-filament::modal>
    <script>
        var video = document.getElementById('my-video');



    function changeUrl(url){

        videoSrc='/storage/'+url;
        video.src=videoSrc


    }
    </script>
    </div>
</x-dynamic-component>
