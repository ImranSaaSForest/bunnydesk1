{{-- <x-filament-panels::page>
<h1>Testing</h1>
</x-filament-panels::page> --}}
<div>
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}"></script>

    <div class="hidden sm:block">

        <div class="border-b border-slate-200 dark:border-slate-600 cursor-pointer">
            <nav class="-mb-px flex space-x-7" aria-label="Tabs">
                <a wire:click='changeTab("overview")'
                    class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ $this->tab == 'overview' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Project Overview
                </a>
                <a wire:click='changeTab("taskcards")'
                    class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ $this->tab == 'taskcards' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Task Cards
                </a>
                <a wire:click='changeTab("chat")'
                    class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ $this->tab == 'chat' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Chat
                </a>
                <a wire:click='changeTab("docs")'
                    class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ $this->tab == 'docs' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Docs
                </a>
            </nav>
        </div>
    </div>

    {{-- <div class="mb-3">
        <livewire:project-navigation :record="$record" />
        {{-- <x-project-navigation />
    </div> --}}

    @switch($this->tab)
        @case($this->tab == 'overview')
            <x-filament::page>
                <form wire:submit='updateRec'>
                    {{ $this->form }}
                    <div class=" flex items-center gap-x-2 mt-6">
                        <button class=" text-white font-bold bg-[#3B82F6] px-[26px] py-2 rounded-lg">Save
                            changes</button>
                        <button class=" border border-[#D1D5D8] light:bg-white dark:bg-grey-500 px-[22px] py-2 rounded-lg"
                            href='/timesheet/projects' wire:navigate>Cancel</button>
                    </div>
                </form>

                <livewire:list-project-tasks :record="$record" />

            </x-filament::page>
        @break

        @case($this->tab == 'taskcards')
            <x-filament::page>

                <livewire:task-card-managment :record="$record" />
                {{-- @stack('scripts') --}}
                {{-- @livewireScripts --}}
            </x-filament::page>
        @break

        @case($this->tab == 'chat')
            <x-filament::page>

                <livewire:project-chat :record="$record" />

            </x-filament::page>
        @break

        @case($this->tab == 'docs')
            <x-filament::page>

                <livewire:project-docs :record="$record" />

            </x-filament::page>
        @break

        @default
            <p>Somthing went Wrong!</p>
    @endswitch


</div>
