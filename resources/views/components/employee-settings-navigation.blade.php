<div>
    <div class="hidden sm:block">
        <div class="border-b border-slate-200 dark:border-slate-600">
            <nav class="-mb-px flex space-x-7" aria-label="Tabs">
                <a href="/employee/employee-statuses" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/employee-statuses') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Status
                </a>
                <a href="/employee/employee-types" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/employee-types') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Type
                </a>
                <a href="/employee/genders" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/genders') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Gender
                </a>
                <a href="/employee/marital-statuses" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/marital-statuses') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Marital Statuses
                </a>
                <a href="/employee/payment-intervals" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/payment-intervals') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Payment Intervals
                </a>
                <a href="/employee/payment-methods" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/payment-methods') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Payment Methods
                </a>


                <a href="/employee/relations" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('employee/relations') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Relations
                </a>
            </nav>
        </div>
    </div>
</div>
