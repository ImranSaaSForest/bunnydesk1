<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>

<input wire:model="timezone" />

    {{-- <div x-data="{ 
        state: $wire.$entangle('{{ $getStatePath() }}'),
        timezones:Intl.supportedValuesOf('timeZone'),
         }">
        <select name="" id=""wire:model='timezone' style="border-radius: 5px;border-color:rgb(209 213 219)">
            <template x-for="timezone in timezones">
                <option value="timezone" x-text='timezone' :selected="timezone==Intl.DateTimeFormat().resolvedOptions().timeZone?true:false"></option>
            </template>
        </select>
       
    </div> --}}
    
</x-dynamic-component>
