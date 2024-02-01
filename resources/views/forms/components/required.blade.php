<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" class="grid  gap-4">
       {{-- @dd(session('number'),session('filldata'))  --}}
       @foreach (session('number') as $item)
       <label>
        <x-filament::input.checkbox x-model="state" />
        <span>
            {{$item}}
        </span>
        </label>
       @endforeach
    </div>
</x-dynamic-component>
