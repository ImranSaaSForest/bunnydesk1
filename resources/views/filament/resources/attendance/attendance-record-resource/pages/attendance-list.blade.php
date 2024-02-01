<x-filament-panels::page>

{{$this->form}}
{{-- {{ $this->table }} --}}
<div>

    {{-- @foreach ($previousRecords->sortByDesc('created_at') as $previousRecord)
    <h1>Check in:{{$previousRecord->in}}</h1>
    <h1>Check out:{{$previousRecord->out}}</h1>

    @if($previousRecord->in >= Carbon\Carbon::today())
    <button wire:click="edit({{$previousRecord->id}})">Edit</button>
    @endif
    @endforeach --}}
   
</div>
</x-filament-panels::page>
