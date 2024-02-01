<x-filament-panels::page>
    {{-- <script src=" https://cdn.jsdelivr.net/npm/luxon@3.4.3/build/global/luxon.min.js "></script>
    <script src="https://cdn.tailwindcss.com"></script> --}}
    <div class=" flex gap-x-10">
        <div class="">
            @foreach ($attendanceTypes as $attendanceTypeKey => $attendanceTypeValue)
                @if ($attendanceTypeValue == 'work')
                    <div class="flex  py-2 px-4 gap-10 border rounded-sm  border-gray-300">
                        <p class="pr-2 py-1 capitalize">{{ $attendanceTypeValue }}</p>
                        <div class="flex gap-2">
                            <p class="bg-green-500 text-white px-4 py-1 rounded-md cursor-pointer"
                                wire:click="openAttendanceRecord({{ $attendanceTypeKey }})">Check-in</p>
                            <p class="bg-red-500 text-white px-4 py-1 rounded-md cursor-pointer"
                                wire:click="openAttendanceRecord({{ $attendanceTypeKey }})">Check-out</p>
                        </div>
                    </div>
                @endif
            @endforeach
            <x-filament::modal id="createAttendance" width="xl">
                <x-slot name="heading">
                    Create Attendance Record
                </x-slot>
                <form wire:submit="create">
                    {{ $this->form }}
                    <div class="mt-4 flex gap-2">
                        <button type="submit" class="px-3 py-1 bg-blue-600  rounded-md text-white">
                            Submit
                        </button>
                        <div class="px-3 py-1 border border-gray-300 rounded-md"
                            @click="$dispatch('close-modal', { id: 'createAttendance' })"> Cancel</div>
                    </div>
                </form>
            </x-filament::modal>
        </div>
        {{-- break  --}}
        @if($paidBreak)
        <div class="">
            {{-- @foreach ($attendanceTypes as $attendanceTypeKey => $attendanceTypeValue) --}}
            <div class="flex justify-between gap-x-5 w-[300px] py-2 px-4 gap-10 border rounded-sm  border-gray-300">
                <p class="pr-2 py-1 capitalize">Break</p>
                <div class="flex gap-2">
                    <p class="bg-green-500 text-white px-4 py-1 rounded-md cursor-pointer"
                        @click="$dispatch('open-modal', { id: 'createBreak' })">Start</p>
                    <p class="bg-red-500 text-white px-4 py-1 rounded-md cursor-pointer"
                        @click="$dispatch('open-modal', { id: 'createBreak' })">End</p>
                </div>
            </div>
            <x-filament::modal id="createBreak" width="xl">
                <x-slot name="heading">
                    Create Attendance Record
                </x-slot>
                <form wire:submit="createBreak">
                    {{ $this->breakForm }}
                    <div class="mt-4 flex gap-2">
                        <button type="submit" class="px-3 py-1 bg-[#B39800]  rounded-md text-white">
                            Submit
                        </button>
                        <div class="px-3 py-1 border border-gray-300 rounded-md"
                            @click="$dispatch('close-modal', { id: 'createBreak' })"> Cancel</div>
                    </div>
                </form>
            </x-filament::modal>
        </div>
        @endif

        @if($unpadiBreak)
        <div>
            @foreach ($attendanceTypes as $attendanceTypeKey => $attendanceTypeValue)
                @if ($attendanceTypeValue == 'unpaid break')
                    <div class="flex  py-2 px-4 gap-10 border rounded-sm  border-gray-300">
                        <p class="pr-2 py-1 capitalize">{{ $attendanceTypeValue }}</p>
                        <div class="flex gap-2">
                            <p class="bg-green-500 text-white px-4 py-1 rounded-md cursor-pointer"
                                wire:click="openAttendanceRecord({{ $attendanceTypeKey }})">Start</p>
                            <p class="bg-red-500 text-white px-4 py-1 rounded-md cursor-pointer"
                                wire:click="openAttendanceRecord({{ $attendanceTypeKey }})">End</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
     @endif

    </div>
</x-filament-panels::page>
