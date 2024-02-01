<x-filament::page>
    <div id="org" class=" flex flex-row overscroll-y-auto  space-x-8  justify-center whitespace-nowrap" style="" x-data="{
        
    }">
        @foreach($employees as $employee)
            {{-- @include('components.employee', ['employee' => $employee, 'depth' => 0]) <!-- Initialize depth as 0 --> --}}
            <div style="inline-block; min-width:120px">
            <x-employee :employee="$employee" :depth="0" :key="$employee->id" />
            </div>
        @endforeach
    </div>
{{-- <script>
     let width=document.querySelector('.fi-sidebar-nav').offsetWidth;
    document.getElementById('org').style.paddingLeft=width
</script> --}}
</x-filament::page>
