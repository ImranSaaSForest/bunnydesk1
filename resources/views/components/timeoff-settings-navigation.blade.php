<div>
    <div class="hidden sm:block">
        <div class="border-b border-slate-200 dark:border-slate-600">
            <nav class="-mb-px flex space-x-7" aria-label="Tabs">
                <a href="/time-off/holidays" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('time-off/holidays') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Holidays
                </a>
                <a href="/time-off/leave-types" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('time-off/leave-types') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Timeoff Type
                </a>
                <a href="/time-off/policy-frequencies" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('time-off/policy-frequencies') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Policy Frequencies
                </a>
                <a href="/time-off/work-weeks" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('time-off/work-weeks') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Work Weeks
                </a>
                <a href="/time-off/policies" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('time-off/policies') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Policies
                </a>
            </nav>
        </div>
    </div>
</div>
