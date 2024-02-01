<div class="flex flex-col items-center mb-6 space-y-8 " style="gap: 10px">
    <div class="bg-white  rounded cursor-pointer relative shadow-sm border border-gray-200"
        style="width: 250px;height: 150px;border-radius: 25px;text-align: center;position: relative;box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.16);"
        wire:click="toggleExpand({{ $employee->id }})">
        <!-- Display employee photo here -->
        <img class="w-10 h-10 mb-1 rounded-full"
            style="position: absolute;width: 60px;height: 60px;bottom: 120px;left: 100px"
            src="{{ $employee->employee->profile_picture_url }}" alt="{{ $employee->name }}'s profile picture">
        <h3 style="padding-top: 50px;text-black" class="font-bold">{{ $employee->name }} {{ $employee->last_name }}</h3>
        <p style="text-black">{{ optional($employee->jobInfo->designation)->name }}</p>
        <!-- Show count here -->
        @if ($employee->direct_report_count > 0)
            <p style="left: 40%;background: #E2E8ED;position: absolute; top:20px;left: 20px"
                class="absolute w-6 h-6 text-sm font-medium z-10 bg-[#E2E8ED] rounded text-black ">
                {{ $employee->direct_report_count }}</p>
        @endif
    </div>
    {{-- <img src="/images/ss.png" alt=""> --}}
    <!-- move the count display out of the condition -->
    <div id="ww" class=" overflow-y-auto" style="gap: 20px">
        @if (in_array($employee->id, $this->expanded) && count($employee->supervisorJobInfo) > 0)
            @foreach ($employee->supervisorJobInfo as $subordinate)
            <x-employee :employee="$subordinate->user" :depth="0" :key="$employee->id" />
            @endforeach
        @endif
    </div>
    <style>
        #ww{
            /* padding-left: 50%  !important; */
            width: 100% !important;
            /* padding-left: 200px !important; */
            padding-top: 50px  !important;
            display: flex !important;
            /* grid-template-columns: auto auto auto auto auto !important; */
            /* overflow-x: auto !important; */
            /* z-index: 40 !important; */
        }
        .fi-main{
            max-width: 100% !important;
        }
    </style>
</div>
