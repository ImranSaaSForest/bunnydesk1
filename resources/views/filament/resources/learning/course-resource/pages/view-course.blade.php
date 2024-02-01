<x-filament-panels::page>
    <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>

    {{ $this->courseInfolist }}

    <h1 class="text-[35px]">Videos</h1>
    @if ($videos)
    @foreach ($videos as $index=>$video)
{{-- @dd($video) --}}
    <div class="px-3 py-1.5 rounded bg-gray-500 text-white flex  cursor-pointer">


        <div class="w-full cursor-pointer" @click="$dispatch('open-modal', { id: 'open-video' })" onclick="changeUrl('{{$video->video_path}}',{{$video->id}})"> Video {{$index+1}}</div>
    </div>
    @endforeach
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
      var currentVideId;
      var currentTime;
      var duration=0;


    function changeUrl(url,id,){
        currentVideoId=id;
        videoSrc='/storage/'+url;
        video.src=videoSrc


    }
    </script>
</x-filament-panels::page>
