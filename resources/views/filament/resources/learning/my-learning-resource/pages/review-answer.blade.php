<x-filament-panels::page>

<x-filament::section>

<div >
    @if ($incorrectQuestions)
    @foreach ($incorrectQuestions as $incorrectQuestion)
    <h1 class="text-bold text-3xl">{!!$incorrectQuestion['question']!!}</h1>
    <div class="p-3 ">

        @foreach ($incorrectQuestion['options'] as $option)

        <div class=" flex gap-2 mt-2">
        {{-- <input id="{{$option['id']}}" name="answer" disabled type="checkbox" {{$incorrectQuestion['correctAnswer']== $option['id'] || $incorrectQuestion['incorrectAnswer']== $option['id']?'checked':''}}
        name="" id="" class="w-5 h-5 {{$incorrectQuestion['correctAnswer']== $option['id']?'text-green-500':'text-red-500'}}  focus-within:ring-0 border border-gray-400 rounded" value="{{$option['id']}}"> --}}
        {{-- text-[#B39800] --}}
        <span class="w-5 h-5 rounded p-[1px] flex justify-center items-center {{$incorrectQuestion['correctAnswer']== $option['id']?'bg-green-500':($incorrectQuestion['incorrectAnswer']== $option['id']?'bg-red-500':'bg-white border border-gray-500')}}">
            @if ($incorrectQuestion['correctAnswer']== $option['id'])
            <x-heroicon-o-check class="w-5 h-5  text-white" />
            @elseif ($incorrectQuestion['incorrectAnswer']== $option['id'])
            <x-heroicon-o-x-mark class="w-5 h-5 text-white" />
            @endif
</span>
        <span> {{$option['option']}}</span>
    </div>
            @endforeach
        {{-- @endif --}}

    </div>
    <div class="w-full h-1 border-t border-black my-2"></div>
    @endforeach
    @endif
</div>
</x-filament::section>
</x-filament-panels::page>
