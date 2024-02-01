<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Attendance\AttendanceTypeSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\Employee\GendersTableSeeder;
use Database\Seeders\Employee\EmployeeStatusesTableSeeder;
use Database\Seeders\Employee\EmployeeTypesTableSeeder;
use Database\Seeders\Employee\MaritalStatusesTableSeeder;
use Database\Seeders\Employee\RelationsTableSeeder;
use Database\Seeders\Employee\DepartmentsTableSeeder;
use Database\Seeders\Employee\DesignationsTableSeeder;
use Database\Seeders\Employee\TeamsTableSeeder;
use Database\Seeders\Employee\ShiftsTableSeeder;
use Database\Seeders\Employee\PaymentIntervalsTableSeeder;
use Database\Seeders\Employee\PaymentMethodsTableSeeder;
use Database\Seeders\Employee\UsersTableSeeder;
use Database\Seeders\Employee\EmployeesTableSeeder;
use Database\Seeders\Employee\SalaryDetailsTableSeeder;
use Database\Seeders\Employee\ContactsTableSeeder;
use Database\Seeders\Employee\EmploymentsTableSeeder;
use Database\Seeders\Employee\JobInfosTableSeeder;
use Database\Seeders\Employee\BankInfosTableSeeder;
use Database\Seeders\Employee\BenefitsTableSeeder;
use Database\Seeders\Employee\AssetsTableSeeder;
use Database\Seeders\Jobs\JobSeeder;
use Database\Seeders\Jobs\job_application;
use Database\Seeders\Module\ModuleSeeder;
use Database\Seeders\Offboarding\OffboardingListsTableSeeder;
use Database\Seeders\Onboarding\EmployeeOnboardingsTableSeeder;
use Database\Seeders\Onboarding\OnboardingListsTableSeeder;
use Database\Seeders\Payroll\TaxSeeder;
use Database\Seeders\Recruitment\CandidateAdditionalSeeder;
use Database\Seeders\Recruitment\CandidateSeeder;
use Database\Seeders\TimeOff\LeaveTypesTableSeeder;
use Database\Seeders\TimeOff\PoliciesSeeder;
use Database\Seeders\TimeOff\HolidaysAndDatesSeeder;
use Database\Seeders\TimeOff\WorkWeekAndDaysSeeder;
use Database\Seeders\Settings\CompaniesTableSeeder;
use Database\Seeders\TimeOff\PolicyFrequenciesTableSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(GendersTableSeeder::class);
        $this->call(EmployeeStatusesTableSeeder::class);
        $this->call(EmployeeTypesTableSeeder::class);
        $this->call(MaritalStatusesTableSeeder::class);
        $this->call(RelationsTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(DesignationsTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(ShiftsTableSeeder::class);
        $this->call(PaymentIntervalsTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(EmployeesTableSeeder::class);
        $this->call(SalaryDetailsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(EmploymentsTableSeeder::class);
        $this->call(JobInfosTableSeeder::class);
        // $this->call(BankInfosTableSeeder::class);
        // $this->call(BenefitsTableSeeder::class);
        // $this->call(AssetsTableSeeder::class);
        $this->call(PolicyFrequenciesTableSeeder::class);
        $this->call(WorkWeekAndDaysSeeder::class);
        $this->call(HolidaysAndDatesSeeder::class);
        $this->call(LeaveTypesTableSeeder::class);
        $this->call(ModuleSeeder::class);
        // $this->call(PoliciesSeeder::class);
        // $this->call(OnboardingListsTableSeeder::class);
        // $this->call(OffboardingListsTableSeeder::class);
        // $this->call(EmployeeOnboardingsTableSeeder::class);
        $this->call(AttendanceTypeSeeder::class);
        // $this->call(JobSeeder::class);
        // $this->call(TaxSeeder::class);
        $this->call(job_application::class);
        // $this->call(CandidateSeeder::class);
        // $this->call(CandidateAdditionalSeeder::class);



    }
}
