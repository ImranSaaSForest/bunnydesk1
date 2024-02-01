<div>


    <form wire:submit="create">
        <div class="mt-3">
            {{-- ATTENDANCE TYPE --}}
            <select name="" id="" wire:model='attendanceTypeId' class='px-3 w-full py-2 border-gray-300 rounded-md' >
                <option value="">select a value</option>
                @foreach ($attendanceTypes as $attendanceTypeKey=>$attendanceTypeValue)
                <option value="{{$attendanceTypeValue}}">{{$attendanceTypeKey}}</option>
                @endforeach
            </select>
            <div class="text-red-500">@error('attendanceTypeId') {{ $message }} @enderror</div>
        </div>
        <div class="mt-3">

            {{-- STATUS --}}
            <select name="" id="" wire:model='status' x-data class='px-3 w-full py-2 border-gray-300 rounded-md' >
                <option value="">Select a value</option>
                @foreach ($statuses as $statusKey=>$statusValue)
                <option value="{{$statusValue}}"    > {{$statusKey}}</option>
                @endforeach
            </select>
            <div class="text-red-500">@error('status') {{ $message }} @enderror</div>
        </div>
        {{-- reason --}}
        <div class="mt-3">

            <input type="text" wire:model='reason' class='px-3 w-full py-2 border-gray-300 rounded-md' >
            <div class="text-red-500">@error('reason') {{ $message }} @enderror</div>
        </div>

        {{-- IN --}}

        <div x-data="timezoneInData()" x-init="init" class="mt-3">
            <select x-model="selectedTimezone" @change="updateDate()" class='px-3 w-full py-2 border-gray-300 rounded-md' >
                <template x-for="zone in timezones" :key="zone">
                    <option :value="zone" x-text="zone" :selected="zone==selectedTimezone?true:false"></option>
                </template>
            </select>

            <!-- Date and Time Input Fields -->
            <div class="flex gap-2">

                <div class="mt-2 ">
                    <label>Date:</label>
                    <input type="date" x-model="inputDate" @change="mergeDateTime()"  class='px-3 w-full py-2 border-gray-300 rounded-md' />
                </div>
                <div class="mt-2">
                    <label>Time:</label>
                    <input type="time" x-model="inputTime" @change="mergeDateTime()"  class='px-3 w-full py-2 border-gray-300 rounded-md' />
                </div>
                <div class="text-red-500">@error('in') {{ $message }} @enderror</div>
            </div>
        </div>
        {{-- OUT --}}
        <div x-data="timezoneOutData()" x-init="init" class="mt-3">
            <select x-model="selectedTimezone" @change="updateDate()" class='px-3 w-full py-2 border-gray-300 rounded-md' >
                <template x-for="zone in timezones" :key="zone">
                    <option :value="zone" x-text="zone" :selected="zone==selectedTimezone?true:false"></option>
                </template>
            </select>

            <!-- Date and Time Input Fields -->
            <div class="flex gap-2">

                <div class="mt-2 ">
                    <label>Date:</label>
                    <input type="date" x-model="inputDate" @change="mergeDateTime()"  class='px-3 w-full py-2 border-gray-300 rounded-md' />
                </div>
                <div class="mt-2">
                    <label>Time:</label>
                    <input type="time" x-model="inputTime" @change="mergeDateTime()"  class='px-3 w-full py-2 border-gray-300 rounded-md' />
                </div>
                <div class="text-red-500">@error('out') {{ $message }} @enderror</div>
            </div>
        </div>

            <button type="submit">
                Submit
            </button>
    </form>
    <!-- Display Adjusted Date & Time -->

    <script>
        document.addEventListener('livewire:init', () => {
            })

             function timezoneInData() {
                return {
                    timezones: [],
                    date: null,
                    selectedTimezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    inputDate: null,
                    inputTime: null,
    
                    init() {
                        this.timezones = Intl.supportedValuesOf('timeZone');
                        // this.updateDate();
                    },
    
                    updateDate() {
                        this.date = luxon.DateTime.local().setZone(this.selectedTimezone).toISO();
                        this.inputDate = this.date.split('T')[0]; // Split ISO date to extract date
                        this.inputTime = this.date.split('T')[1].substring(0,5); // Split ISO date to extract time
                        @this.dispatch('setInDateTime', { dateTime:this.date});
                   
                    },
    
                    mergeDateTime() {
                        if (this.inputDate && this.inputTime) {
                            this.date = luxon.DateTime.fromISO(`${this.inputDate}T${this.inputTime}`).setZone(this.selectedTimezone).toISO();
                            @this.dispatch('setInDateTime', { dateTime:this.date});
                         
                            
                        }
                    }
                };
            }
            function timezoneOutData() {
                return {
                    timezones: [],
                    date: null,
                    selectedTimezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    inputDate: null,
                    inputTime: null,
    
                    init() {
                        this.timezones = Intl.supportedValuesOf('timeZone');
                        // this.updateDate();
                    },
    
                    updateDate() {
                        this.date = luxon.DateTime.local().setZone(this.selectedTimezone).toISO();
                        this.inputDate = this.date.split('T')[0]; // Split ISO date to extract date
                        this.inputTime = this.date.split('T')[1].substring(0,5); // Split ISO date to extract time
                        @this.dispatch('setOutDateTime', { dateTime:this.date});
                    },
    
                    mergeDateTime() {
                        if (this.inputDate && this.inputTime) {
                            this.date = luxon.DateTime.fromISO(`${this.inputDate}T${this.inputTime}`).setZone(this.selectedTimezone).toISO();
                            @this.dispatch('setOutDateTime', { dateTime:this.date});


                        }
                    }
                };
            }

    </script>
</div> 