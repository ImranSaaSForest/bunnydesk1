<div class=" w-full">
    <style>
        /* Card Outline Css */

        .fi-ta-record {
            position: relative;
        }

        .fi-ta-record input {
            position: absolute;
            top: 0px;
        }

        .fi-ta-record a {
            padding-left: 10px;
            padding-right: 12px;
        }

        .fi-ta-record {
            border-radius: 4px;
            border: 1px solid #EFEFEF;
        }

        /* Card Section Css  */

        .connect {
            margin-left: 30px;
            margin-top: -5px;
        }

        .connect div:nth-child(1) {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .connect h1 {
            font-weight: 500;
        }

        .connect div:nth-child(2) h6 {
            color: #6B7280;
            padding-top: 15px;
            font-size: 14px;
        }

        .connect div:nth-child(3) {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .connect div:nth-child(3) h6 {
            color: #6B7280;
            font-size: 14px;
        }

        .connect div:nth-child(3) div {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            width: 36px;
            height: 36px;
            border-radius: 100%;
            border: 2px solid #117143;
        }

        .team {
            display: flex;
            align-items: center;
        }

        .team-1 {
            position: relative;
            z-index: 10;
        }

        .team-2 {
            margin-left: -8px;
            position: relative;
            z-index: 9;
        }

        .team-3 {
            margin-left: -8px;
            position: relative;
            z-index: 8;
        }

        .team-4 {
            margin-left: -8px;
            position: relative;
            z-index: 7;
        }

        .team-5 {
            margin-left: -6px;
            position: relative;
            z-index: 6;
            font-size: 12px;
            color: #42526E;
            background-color: #DFE1E6;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 100%;
        }


    </style>
    {{-- @dd($getRecord()->id) --}}

    <div class="connect">
        <div>
            <h1>{{ $getRecord()->name }}</h1>
            {{-- <div class=" team">
                <img src="/images/profile-1.png" alt="" class=" team-1">
                <img src="/images/profile-1.png" alt="" class=" team-2">
                <img src="/images/profile-1.png" alt="" class=" team-3">
                <img src="/images/profile-1.png" alt="" class=" team-4">
                <div class="team-5">+14</div>
            </div> --}}
        </div>
        <div>
            <h6>Assigned Teams:
                @foreach ($getRecord()->projectTeams as $Teams)
                    {{ $Teams->teams->name }},
                @endforeach
            </h6>
        </div>

        @php
            $totalTasks = App\Models\Timesheet\Task::where('project_id', $getRecord()->id)->count();
            $completedTasks = App\Models\Timesheet\Task::where('project_id', $getRecord()->id)
                ->where('status', 'done')
                ->count();
            if ($totalTasks > 0) {
                $percentageCompleted = round(($completedTasks / $totalTasks) * 100, 0);
            }
        @endphp

        <div>
            <h6>Due on: {{ \Carbon\Carbon::parse($getRecord()->end_date)->format('M jS, Y') }}</h6>

            @if ($totalTasks > 0)
                <div class="">{{ $percentageCompleted }}%</div>
            @endif

        </div>
    </div>

</div>
