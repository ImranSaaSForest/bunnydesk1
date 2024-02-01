<x-filament-panels::page>
{{-- header --}}
<div class="flex gap-4 justify-between items-center">

    <x-filament::section class=" shadow-md w-[30%] text-center flex items-center justify-center flex-col">


    {{-- Content --}}
    <div class="px-3 py-4 ">
            <p class="py-2">Total Questions : {{$totalQuestionCount}}</p>
            <p class="py-2">Correct Answer : {{$correctAnswerCount}}</p>
            <p class="py-2">Incorrect Answer : {{$incorrectAnswerCount}}</p>
            <p class="py-2">Score Percentage : {{$scorePercentage}}%</p>
        </div>
</x-filament::section>
<x-filament::input.wrapper class="shadow-md w-[70%] p-1">

    <textarea wire:model='feedback' class="border-none focus-within:ring-0 rounded-xl w-full" name="" id="" cols="30" rows="9" placeholder="Post Your Feedback Here..."></textarea>
</x-filament::input.wrapper>
</div>
<x-filament::section>

    {{ $this->certificateInfolist }}

    <button class="bg-blue-600 px-3 py-1 mt-5 rounded-md text-white w-fit" wire:click='generateCertificate'>
        Generate & Send Certificate
    </button>

</x-filament::section>

{{-- incorrect answer --}}
@if ($incorrectQuestions)

    <x-filament::section class="shadow-md" >
        <x-slot name="heading">
            Incorrect Answer
        </x-slot>
        <div >
            @foreach ($incorrectQuestions as $incorrectQuestion)
            <h1 class="text-bold text-3xl mt-5">{!!$incorrectQuestion['question']!!}</h1>
            <div class="p-3 ">

                @foreach ($incorrectQuestion['options'] as $option)

                <div class=" flex gap-2 mt-2 items-center">
                {{-- <input id="{{$option['id']}}" name="answer" disabled type="checkbox" {{$incorrectQuestion['correctAnswer']== $option['id'] || $incorrectQuestion['incorrectAnswer']== $option['id']?'checked':''}}
                name="" id="" class="w-5 h-5 {{$incorrectQuestion['correctAnswer']== $option['id']?'text-green-500':'text-red-500'}}  focus-within:ring-0 border border-gray-400 rounded" value="{{$option['id']}}"> --}}
                {{-- text-[#B39800] --}}
        <span class="w-5 h-5 rounded p-[1px] flex justify-center items-center {{$incorrectQuestion['correctAnswer']== $option['id']?'bg-green-500':($incorrectQuestion['incorrectAnswer']== $option['id']?'bg-red-500':'bg-white border border-gray-500')}}">
            @if ($incorrectQuestion['correctAnswer']== $option['id'])
            <x-heroicon-o-check class="w-5 h-5 text-white" />
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
        </div>
    </x-filament::section>
    @endif
</x-filament-panels::page>
