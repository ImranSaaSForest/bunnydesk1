<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $employeeListPermission = Permission::create(['name' => 'Employee List']);
        $employeeProfilePermission = Permission::create(['name' => 'Employee Profiles']);
        $timeOffRequestPermission = Permission::create(['name' => 'Time Off Request']);
        $timeOffApprovalPermission = Permission::create(['name' => 'Time Off Approval']);
        $companyTimeOffPermission = Permission::create(['name' => 'Company Time Off']);
        $ticketManagementPermission = Permission::create(['name' => 'Ticket Management']);
        $ticketManagementPermissionCreate = Permission::create(['name' => 'Ticket Management Create']);
        $ticketManagementPermissionUpdate = Permission::create(['name' => 'Ticket Management Update']);
        $ticketManagementPermissionDelete = Permission::create(['name' => 'Ticket Management Delete']);
        $timesheetManagementPermission = Permission::create(['name' => 'Timesheet Management']);
        $timesheetManagementPermissionCreate = Permission::create(['name' => 'Timesheet Management Create']);
        $settingsManagementPermission = Permission::create(['name' => 'Settings Management']);
        $reportAccessPermission = Permission::create(['name' => 'Report Access']);
        $hrToolkitPermission = Permission::create(['name' => 'HR Toolkit']);
        $hrToolkitPermissionUpload = Permission::create(['name' => 'HR Toolkit Upload']);
        $manageRolesPermission = Permission::create(['name' => 'Manage Roles']);
        $assetsCreate=Permission::create(['name' => 'assets Create']);
        $assetsEdit=Permission::create(['name' => 'assets Edit']);
        $assetsDelete=Permission::create(['name' => 'assets Delete']);
        $performanceManagementPermission = Permission::create(['name' => 'Manage Performance Goals']);
        $performanceManagementPermissionCreate = Permission::create(['name' => 'Manage Performance Goals Create']);
        $performanceManagementPermissionEdit = Permission::create(['name' => 'Manage Performance Goals Edit']);
        $appraisalManagementPermission = Permission::create(['name' => 'Manage Appraisal']);
        $reimbursementManagementPermission = Permission::create(['name' => 'Manage Reimbursement']);
        $payrunManagementPermission = Permission::create(['name' => 'Manage Payrun']);
        $onboardingPermission = Permission::create(['name' => 'onboarding Permission']);
        $offboardingPermission = Permission::create(['name' => 'offboarding Permission']);
        $performanceAdminPermission = Permission::create(['name' => 'Performance Admin']);
        $performanceAdminPermissionCreate = Permission::create(['name' => 'Performance Admin Create']);
        $performanceAdminPermissionEdit = Permission::create(['name' => 'Performance Admin Edit']);
        $performanceManagerPermission = Permission::create(['name' => 'Performance Manager']);
        $performanceManagerPermissionCreate = Permission::create(['name' => 'Performance Manager Create']);
        $performanceManagerPermissionEdit = Permission::create(['name' => 'Performance Manager Edit']);
        $performanceHRPermission = Permission::create(['name' => 'Performance HR']);
        $performanceHRPermissionCreate = Permission::create(['name' => 'Performance HR Create']);
        $performanceHRPermissionEdit = Permission::create(['name' => 'Performance HR Edit']);
        $jobPermission = Permission::create(['name' => 'Job List']);
        $jobPermissionCreate = Permission::create(['name' => 'Job Create']);
        $jobPermissionEdit = Permission::create(['name' => 'Job Edit']);
        $courseManagementPermission = Permission::create(['name' => 'Manage Course']);


        // create roles and assign existing permissions
        $superAdminRole = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());

        $hrRole = Role::create(['name' => 'HR'])
            ->givePermissionTo([
                $employeeListPermission,
                $employeeProfilePermission,
                $timeOffRequestPermission,
                $timeOffApprovalPermission,
                $companyTimeOffPermission,
                $ticketManagementPermissionCreate,
                $ticketManagementPermissionUpdate,
                $ticketManagementPermissionDelete,
                $ticketManagementPermission,
                $reportAccessPermission,
                $offboardingPermission,
                $assetsCreate,
                $assetsEdit,
                $onboardingPermission,
                $timesheetManagementPermission,
                $assetsDelete,
                $timesheetManagementPermission,
                $hrToolkitPermission,
                $hrToolkitPermissionUpload,
                $manageRolesPermission,
                $performanceManagementPermission,
                $performanceManagementPermissionCreate,
                $performanceManagementPermissionEdit,
                $appraisalManagementPermission,
                $reimbursementManagementPermission,
                $payrunManagementPermission,
                $performanceHRPermission,
                // $performanceHRPermissionCreate,
                $performanceHRPermissionEdit,
                $jobPermission,
                $jobPermissionCreate,
                $jobPermissionEdit,
                $courseManagementPermission
            ]);

        $supervisorRole = Role::create(['name' => 'Supervisor'])
            ->givePermissionTo([
                $employeeListPermission,
                $employeeProfilePermission,
                $timeOffApprovalPermission,
                $ticketManagementPermissionCreate,
                $ticketManagementPermissionUpdate,
                $ticketManagementPermissionDelete,
                $companyTimeOffPermission,
                $ticketManagementPermission,
                $timesheetManagementPermission,
                $hrToolkitPermission,
                $reportAccessPermission,
                $performanceManagementPermission,
                $performanceManagementPermissionCreate,
                $performanceManagementPermissionEdit,
                $appraisalManagementPermission,
                $reimbursementManagementPermission,
                $payrunManagementPermission,
                // $timesheetManagementPermissionCreate,
                $performanceManagerPermission,
                $performanceManagerPermissionEdit,
                $courseManagementPermission
            ]);
        $staffRole = Role::create(['name' => 'Staff'])
            ->givePermissionTo([
                $timesheetManagementPermission,
                $employeeListPermission,
                $performanceManagementPermission,
                $ticketManagementPermission,
                $timeOffRequestPermission,

            ]);
        $financeAdminRole=Role::create(['name' => 'Finance Admin']);
        $teamleadRole=Role::create(['name' => 'Team Lead' ]);

        // Define user ID and role mapping
        $userRoleMapping = [
            1 => 'Super Admin',
            2 => 'Supervisor',
            3 => 'HR',
        ];

        $users = User::all();

        foreach($users as $user) {
            // Assign role based on the user ID mapping, else assign 'Staff'
            $roleName = $userRoleMapping[$user->id] ?? 'Staff';
            $user->assignRole($roleName);
        }
    }
}
