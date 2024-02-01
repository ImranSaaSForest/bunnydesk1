<div>
    <div class="hidden sm:block">
        <div class="border-b border-slate-200 dark:border-slate-600">
            <nav class="-mb-px flex space-x-7" aria-label="Tabs"
            >
                <a href="/settings/tax-slabs" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/tax-slabs') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Tax Slab
                </a>
                <a href="/settings/allowances" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/allowances') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Allowance
                </a>
                <a href="/settings/deductions" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/deductions') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Deduction
                </a>
                <a href="/settings/over-time-rates" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/over-time-rates') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Over Time
                </a>
                <a href="/settings/social-securities" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/social-securities') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Social Security
                </a>
                <a href="/settings/payroll-policies" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/payroll-policies') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                   Payroll Policy
                </a>
                <a href="/settings/payslips" class="whitespace-nowrap  py-4 px-3 border-b font-medium text-sm {{ request()->is('settings/payslips') ? 'border-primary-600 text-primary-600 ' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:border-slate-300' }}">
                    Payslip
                 </a>
            </nav>
        </div>
    </div>
</div>
