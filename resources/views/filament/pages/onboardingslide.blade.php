<div>
    <div class="flex">
        <div class="">
            <p>Task</p>
                {{$record->onboardingTask->name}}
        </div>
        <div class="">
            <p>Duration</p>
            {{$record->onboardingTask->duration}}
        </div>
    </div>
    <div class="">
        <p>Worked To</p>
        {{$record->onboardingTask->user->name}}
    </div>
</div>
