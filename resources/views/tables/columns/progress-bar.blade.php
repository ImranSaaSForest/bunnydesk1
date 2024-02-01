<div class="w-full">
    <style>

.progress {
  /* background: gray; */
  justify-content: flex-start;
  border-radius: 100px;
  align-items: center;
  position: relative;

  display: flex;
  height: 5px;
  width: 100%;
}

.progress-value-{{ $getRecord()->id }} {
  animation: load-{{ $getRecord()->id }} 3s normal forwards;
  box-shadow: 0 10px 40px -10px #fff;
  border-radius: 100px;
  background: #000;
  height: 5px;
  width: {{ !is_null($getState()[0])?round($getState()):0 }}%;
}

@keyframes load-{{ $getRecord()->id }} {
  0% { width: 0; }
  100% { width: {{ !is_null($getState()[0])?round($getState()):0 }}%; }
}
    </style>


    <div class="flex gap-2 items-center">
    <div class="progress  inline bg-gray-300">

        <div class="progress-value-{{ $getRecord()->id }}"></div>
    </div>
    <span class="w-[30%] count" data-target="{{ !is_null($getState()[0])?round($getState()):0 }}" >{{ !is_null($getState()[0])?round($getState()):0 }}%</span>
</div>
    {{-- <progress class="background-black h-1 " value="{{ $getState() }}" max="100">{{ $getState() }}
    </progress> --}}
</div>
