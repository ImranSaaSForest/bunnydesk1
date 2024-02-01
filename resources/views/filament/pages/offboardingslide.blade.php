<div class="flex">
    <div class="">
        <p>Task</p>
            {{$record->offboardingTask->name}}
    </div>
    <div class="">
        <p>Duration</p>
        {{$record->offboardingTask->duration}}
    </div>
</div>
<div class="">
    <p>Worked To</p>
    {{$record->offboardingTask->user->name}}
</div>
{{$record->name}}
