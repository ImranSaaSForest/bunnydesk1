<x-filament-panels::page>
    <div>
        <style>
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }

            :root {
                --theme-color: #ff7f27;
                --theme-color-hover: #fc914a;
                --theme-color2: #000c7b;
            }

            /* Multi Select  */
        </style>

        <form wire:submit="{{ $updateVal ? 'updateQuestion' : 'createQuestion' }}">
            <div class=" border border-[#D1D5DB] rounded-xl p-6">
                <div>
                    <label for="" class=" text-[#374151] font-semibold">What question do you want to ask?</label>
                    <input type="text" wire:model="description"
                        class=" block border border-[#D1D5DB] bg-white px-5 py-2 mt-1 shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded-lg dark:text-white dark:bg-[#09090B] w-full">
                    @error('description')
                        <span class=" text-red-600 block mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class=" mt-6" x-data="{
                    value: '{{ $updateVal ? 'Daily On' : '' }}',
                    data: null,
                    inactive: 'border border-[#D1D5D8] px-5 py-1 text-center font-medium cursor-pointer rounded w-fit',
                    active: 'text-white bg-[#2563EB] px-5 py-1 text-center font-medium cursor-pointer rounded',
                }">
                    <h3 for="" class=" text-[#374151] font-semibold">How often do you want to ask?</h3>
                    {{-- wire:click="time_data('{{ $t['slot']['slot_time'] }}')" @click="val = {{  $t['slot']['id'] }}" --}}

                    @foreach ($this->dailys as $daily)
                        <div>
                            <div class=" flex items-center gap-x-2 mt-5">
                                <input @click="value = '{{ $daily }}'; data=''" type="radio" name="daily"
                                    value="{{ $daily }}" wire:model="status"
                                    class=" border border-[#D1D5DB] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] dark:bg-[#09090B]">
                                <label for="" class=" text-[#374151]">{{ $daily }}</label>
                            </div>
                            <div x-show="value=='{{ $daily }}'" class=" flex items-center gap-x-5 pt-2">
                                {{-- Daily On Section start  --}}
                                @foreach ($this->days as $key => $day)
                                    <template x-if="value=='Daily On'">
                                        <h1 @click="data = '{{ $day }}'"
                                            wire:click="dailyStatus('{{ $key }}')" {{$this->myArray ? '' : ''}}
                                            :class="{{ in_array($key, $this->myArray) ? 'active' : 'inactive' }}">
                                            {{ $day }}
                                        </h1>
                                    </template>
                                @endforeach
                                {{-- Daily On Section end  --}}

                                {{-- Once a Week Section start  --}}
                                @foreach ($this->days as $key => $day)
                                    <template x-if="value=='Once a Week'">
                                        <h1 @click="data = '{{ $day }}'"
                                            wire:click="dayCollect('{{ $key }}')"
                                            :class="data == '{{ $day }}' ? active : inactive">
                                            {{ $day }}
                                        </h1>
                                    </template>
                                @endforeach
                                {{-- Once a Week Section end  --}}

                                {{-- Once a month on the first Section start  --}}
                                @foreach ($this->days as $key => $day)
                                    <template x-if="value=='Once a month on the first'">
                                        <h1 @click="data = '{{ $day }}'"
                                            wire:click="dayCollect('{{ $key }}')"
                                            :class="data == '{{ $day }}' ? active : inactive">
                                            {{ $day }}
                                        </h1>
                                    </template>
                                @endforeach
                                {{-- Once a month on the first Section end  --}}
                            </div>
                        </div>
                    @endforeach

                    @error('status')
                        <span class=" text-red-600 block mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class=" mt-6">
                    <h3 for="" class=" text-[#374151] font-semibold">At what time of the day?</h3>
                    <div class="flex items-center gap-x-2 mt-5" x-data="{ checked: {{ $this->startTime ? 'true' : 'false' }} }">
                        <input type="checkbox" @click="checked=!checked" {{ $this->startTime ? 'checked' : '' }} wire:model="startTimeCheck" 
                            class="border border-[#D1D5DB] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded dark:bg-[#09090B]">
                        <label for="" class="text-[#374151]">Beginning of the day</label>
                        <input type="time" wire:model="startTime" :disabled="!checked"
                            class="bg-white border border-[#D1D5D8] px-1 h-6 w-[110px] rounded dark:text-white dark:bg-[#09090B]">
                    </div>
                    <div class="flex items-center gap-x-2 mt-5" x-data="{ checked: {{ $this->endTime ? 'true' : 'false' }} }">
                        <input type="checkbox" @click="checked=!checked" {{ $this->endTime ? 'checked' : '' }} wire:model="endTimeCheck"
                            class="border border-[#D1D5DB] shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] rounded dark:bg-[#09090B]">
                        <label for="" class="text-[#374151]">End of the day</label>
                        <input type="time" wire:model="endTime" :disabled="!checked"
                            class="bg-white border border-[#D1D5D8] px-1 h-6 w-[110px] rounded dark:text-white dark:bg-[#09090B]">
                    </div>
                    
                    {{-- @error('startTime')
                        <span class=" text-red-600 block mt-1">{{ $message }}</span>
                    @enderror --}}
                </div>
                <div class=" mt-6">
                    {{ $this->form }}
                </div>
            </div>

            {{-- Button section start  --}}
            <div class=" flex gap-x-6 items-center mt-7" x-data>
                <button type="submit" class=" text-white bg-[#3B82F6] font-medium px-5 py-2 rounded-lg">
                    {{ $updateVal ? 'update' : 'create' }}
                </button>
                <button x-on:click="refreshPage" type="button"
                    class=" text-[#1F2937] border border-[#D1D5DB] font-medium px-5 py-2 rounded-lg shadow-[0px_1px_3px_0px_rgba(0,0,0,0.07)] dark:text-white dark:bg-black">
                    Cancel
                </button>
            </div>
            {{-- Button section end  --}}
        </form>

        <script>
            function refreshPage() {
                window.location.reload();
            }
        </script>

    </div>
</x-filament-panels::page>
