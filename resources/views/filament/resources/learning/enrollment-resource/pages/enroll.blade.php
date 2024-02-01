<x-filament-panels::page>
    <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>

    {{ $this->courseInfolist }}

    <h1 class="text-[35px] fo ">Videos</h1>
    @if ($videos)
    @foreach ($videos as $index=>$video)
{{-- @dd($video) --}}
    <div class="px-3 py-1.5 rounded bg-gray-500 text-white flex gap-2 cursor-pointer">


        <div class="w-full cursor-pointer" > Video {{$index+1}}</div>
    </div>
    @endforeach
    @endif
   
</x-filament-panels::page>
