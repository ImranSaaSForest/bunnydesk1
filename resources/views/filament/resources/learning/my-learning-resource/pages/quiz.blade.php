<x-filament-panels::page>
    <style>
        .load-wrapp:last-child {
  margin-right: 0;
}

.line {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 15px;
  background-color: white;
}
.load-3 .line:nth-last-child(1) {
  animation: loadingC 0.6s 0.1s linear infinite;
}
.load-3 .line:nth-last-child(2) {
  animation: loadingC 0.6s 0.2s linear infinite;
}
.load-3 .line:nth-last-child(3) {
  animation: loadingC 0.6s 0.3s linear infinite;
}
@keyframes loadingC {
  0 {
    transform: translate(0, 0);
  }
  50% {
    transform: translate(0, 15px);
  }
  100% {
    transform: translate(0, 0);
  }
}


    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <div class=" w-full flex justify-end" wire:ignore>
        <input type="hidden" id="set-time" value='{{$quizTime}}' />
        <div id="countdown" class="bg-blue-600 px-2 py-1  rounded text-white w-fit">
            <div id='tiles' class="color-full"></div>
        </div>
    </div class="bg-blue-600 px-2 py-1  rounded text-white w-fit flex justify-end" >
    <div class="border rounded">

        <div style="width:{{($currentQuestion+1)/count($questions)*100}}% " class=" h-1 rounded bg-blue-600 mb-3">
        </div>
        <div class="p-10">
            {{-- @foreach ($questions as $index=>$question) --}}

            <h1 class="text-bold text-3xl">{!!$questions[$currentQuestion]->question!!}</h1>
            @if ($questions[$currentQuestion]->options)
            @foreach ($questions[$currentQuestion]->options as $option)
            <div class="p-3 flex gap-2 items-center">


                <input id="{{$option->id}}" name="answer"
                    wire:model='answerOptions.{{$questions[$currentQuestion]->id}}' type="radio" name="" id=""
                    class="w-5 h-5  text-blue-600 focus-within:ring-0" value="{{$option->id}}">
                <label class="cursor-pointer" for="{{$option->id}}"> {{$option->option}}</label>
                {{-- @endif --}}

            </div>
            @endforeach
            @endif
            {{-- @endforeach --}}
            {{-- bottom button --}}
            <div class="flex  items-center justify-between mt-2">

                <button style="opacity: {{$currentQuestion==0?'0.5':'1'}}" {{$currentQuestion==0?'disabled':''}}
                    class="flex gap-1 items-center justify-between" wire:click='decrementQuestion()'>
                    <x-heroicon-o-arrow-left class="w-5 h-5" />

                    <span>
                        Previous Question
                    </span>
                </button>
                <div>
                    Question {{($currentQuestion+1)}}/{{count($questions)}}
                </div>
                <button {{($currentQuestion+1)==count($questions)?'disabled':''}}
                    style="opacity: {{($currentQuestion+1)==count($questions)?'0.5':'1'}}"
                    class="flex gap-1 items-center justify-between" wire:click='incrementQuestion()'>

                    <span>
                        Next Question
                    </span>
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
    <button class="bg-blue-600 px-2 py-1  rounded text-white w-fit" wire:click='submit()'
        style="display:{{($currentQuestion+1)==count($questions)?'block':'none'}} "> <span wire:loading.remove>Submit</span>
        <div wire:loading wire:target="submit">
            <div class="load-wrapp">
                <div class="load-2">
                  <div class="line"></div>
                  <div class="line"></div>
                  <div class="line"></div>
                </div>
              </div>
        </div>
    </button>
    <script>
        var minutes = $( '#set-time' ).val();

var target_date = new Date().getTime() + ((minutes * 60 ) * 1000); // set the countdown date
var time_limit = ((minutes * 60 ) * 1000);
//set actual timer


var days, hours, minutes, seconds; // variables for time units

var countdown = document.getElementById("tiles"); // get tag element

getCountdown();

setInterval(function () { getCountdown(); }, 1000);

function getCountdown(){

	// find the amount of "seconds" between now and target
	var current_date = new Date().getTime();
	var seconds_left = (target_date - current_date) / 1000;
if(Math.round(seconds_left)==0){
    @this.dispatch('timeOver');

}
// if(Math.round(seconds_left)==60){
// new FilamentNotification()
//     .title('Form will be Submitted when time is finished')
//     .danger()
//     .send()
// }
if(Math.round(seconds_left)<=60){

    $('#countdown').removeClass('bg-blue-600');
    $('#countdown').addClass('bg-red-600');

}
if ( seconds_left >= 0 ) {


	days = pad( parseInt(seconds_left / 86400) );
	seconds_left = seconds_left % 86400;

	hours = pad( parseInt(seconds_left / 3600) );
	seconds_left = seconds_left % 3600;

	minutes = pad( parseInt(seconds_left / 60) );
	seconds = pad( parseInt( seconds_left % 60 ) );

	// format countdown string + set tag value
	countdown.innerHTML = "<span>" + hours + ":</span><span>" + minutes + ":</span><span>" + seconds + "</span>";



}



}

function pad(n) {

	return (n < 10 ? '0' : '') + n;
}

    </script>
</x-filament-panels::page>
