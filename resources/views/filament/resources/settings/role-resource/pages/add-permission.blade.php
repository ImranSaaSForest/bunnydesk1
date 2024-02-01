<x-filament-panels::page>




@foreach ($allModules as $allModule)

<div class="border border-[#E5E7EB] rounded-md p-3">
    <div>
        <h1 class="font-bold text-lg">{{$allModule->module->name}}</h1>
        <hr>
    </div>
</div>
   
@endforeach
</x-filament-panels::page>
