<x-filament-panels::page>
    <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>

    {{ $this->courseInfolist }}

    <h1 class="text-[35px] fo ">Videos</h1>
    @if ($videos)
    @foreach ($videos as $index=>$video)
{{-- @dd($video) --}}
    <div class="px-3 py-1.5 rounded bg-gray-500 text-white flex gap-2 cursor-pointer">


        <div class="w-full cursor-pointer" @click="$dispatch('open-modal', { id: 'open-video' })" onclick="changeUrl('{{$video->video_path}}',{{$video->id}},{{$video->videoProgress?$video->videoProgress->progress:''}})"> Video {{$index+1}}</div>
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

      var percentage;
      var lastWatchedTime;
      var lastWatchPercentage;
      var player = videojs('my-video');
    //   video.addEventListener('loadedmetadata', function () {

    //   window.duration=video.duration;
    //   console.log(duration);
    // })
    // var videoSrc = '/storage/adaptive_steve.m3u8';
video.addEventListener('blur', function () {
      currentTime=video.currentTime;
      duration=video.duration;
      percentage=(currentTime/duration)*100;

    //  get current time from percentage
    @this.dispatch('setWatchTime', { percentage:percentage,videoId:currentVideoId});


    })
    video.addEventListener('ended', function () {
        currentTime=video.currentTime;
    //   duration=video.duration;
      percentage=(currentTime/duration)*100;
    //  get current time from percentage
    @this.dispatch('setWatchTime', { percentage:percentage,videoId:currentVideoId});


    })
 video.addEventListener('focus', function () {

    duration=video.duration;
    // console.log( parseInt(lastWatchPercentage)/100);
    lastWatchedTime=(parseInt(lastWatchPercentage)*duration)/100;
    // console.log(lastWatchedTime);
    video.currentTime=lastWatchedTime

    })

    function changeUrl(url,id,watchPercentage){

        currentVideoId=id;
        videoSrc='/storage/'+url;
        video.src=videoSrc
        lastWatchPercentage=watchPercentage;


    }
    </script>
</x-filament-panels::page>
