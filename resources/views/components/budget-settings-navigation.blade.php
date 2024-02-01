<div>
    <div class="hidden sm:block">
        <div class="border-b border-slate-200 dark:border-slate-600">
            <nav class="-mb-px flex space-x-7" aria-label="Tabs">

                <a href="/settings/expense-categories" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/expense-categories') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                   Expense Category
                </a>
                <a href="/settings/expense-types" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/expense-types') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Expense Type
                 </a>
                 <a href="/settings/budgets" class="whitespace-nowrap py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/budgets') ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Budget
                </a>
            </nav>
        </div>
    </div>
</div>
