<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    <div class="flex gap-5 h-full">
        <div class='w-[49%] '>
            <x-filament::section class="h-[100%]">
    <x-slot name="heading">
        Job Reports
    </x-slot>

    {{-- Content --}}
    {{ $this->form }}
</x-filament::section >
        </div>
        <div class="w-[50%]">
            @livewire(App\Filament\Resources\RecruitmentReportResource\Pages\RecruitmentMonthlyChart::class)
        </div>
    </div>
    <div class="w-[100%]">
        @livewire(App\Filament\Resources\RecruitmentReportResource\Pages\RecruitmentPositionChart::class)
    </div>
</x-filament-panels::page>
