<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <style>
        .star {
            font-size: 30px;
            cursor: pointer;
            padding-bottom: 6px;
        }

        .active {
            color: gold;
        }

        .starclass {
            display: flex;
            align-items: center;
            gap: 7px;
        }
    </style>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        {{-- @dd(session()->get('datas')) --}}
        <div style="display: grid; grid-template-columns: auto auto auto auto;">
            @foreach (session()->get('datas') as $key => $item)
            <div class="starclass" x-data="{ 
                active: false,
                checked: false,
                number: '', 
            }">
                <x-filament::input.checkbox wire:model="{{$item}}" @click="number = {{$key}};checked=!checked" id="checkbox-{{$key}}"  
                    @change="$dispatch('checkbox', { title: '{{$key}}',titles: '1' });" />
                <label for="checkbox-{{$key}}">
                    {{$item}}
                </label>
                    <button type="button" class="star" :class="{ 'active':checked && active  }" @click="number=={{$key}}?active = !active:'';number=={{$key}}?$dispatch('check', { random: '{{$key}}',number: '1' }):''"
                    @click="" ><span style="font-size: 24px;">★</span></button>
            </div>
            @endforeach
        </div>
    </div>
</x-dynamic-component>