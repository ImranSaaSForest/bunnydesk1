<div>
    <div class="hidden sm:block">
        <div class="border-b border-slate-200 dark:border-slate-600">
            <nav class="-mb-px flex space-x-7" aria-label="Tabs">
                <a href="/settings/companies" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm mr-2 {{ request()->is('settings/companies') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Company
                </a>
                <a href="/employee/shifts" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/shifts') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Shifts
                </a>
                <a href="/employee/teams" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/teams') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Teams
                </a>
                <a href="/settings/departments" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/departments') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Department
                </a>
                <a href="/settings/designations" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/designations') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Designation
                </a>

            </nav>
        </div>
    </div>
</div>
