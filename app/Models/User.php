<?php

namespace App\Models;

use App\Models\Asset\EmployeeAsset;
use App\Models\Attendance\AttendanceRecord;
use App\Models\Contract\EmployeeContract;
use App\Models\Employee\BankInfo;
use App\Models\Employee\EmployeeBenefit;
use App\Models\Employee\Contact;
use App\Models\Employee\Contract;
use App\Models\Employee\CurrentAddress;
use App\Models\Employee\Education;
use App\Models\Employee\EmergencyContact;
use App\Models\Employee\Employee;
use App\Models\Employee\Employment;
use App\Models\Employee\Experience;
use App\Models\Employee\JobInfo;
use App\Models\Employee\Team;
use App\Models\Employee\PermanentAddress;
use App\Models\Employee\SalaryDetail;
use App\Models\File\File;
use App\Models\Finance\Budget;
use App\Models\Finance\BudgetExpense;
use App\Models\Finance\BudgetManager;
use App\Models\Finance\BudgetTeam;
use App\Models\Finance\ExpenseCategory;
use App\Models\Finance\ExpenseType;
use App\Models\Learning\Course;
use App\Models\Learning\EnrollmentCourse;
use App\Models\Learning\LearningEmployee;
use App\Models\Learning\VideoProgress;
use App\Models\livechat\GroupMessage;
use App\Models\livechat\MemberChat;
use App\Models\Note\Note;
use App\Models\Timesheet\Timesheet;
use App\Models\Offboarding\EmployeeOffboarding;
use App\Models\Offboarding\OffboardingTask;
use App\Models\Offboarding\EmployeeOffboardingTask;
use App\Models\Onboarding\EmployeeOnboarding;
use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\Onboarding\OnboardingTask;
use App\Models\Payroll\Allowance;
use App\Models\Payroll\Deduction;
use App\Models\Payroll\EmployeeTaxSlab;
use App\Models\Payroll\EmployeeTaxSlabValue;
use App\Models\Payroll\OverTimeRate;
use App\Models\Payroll\PayrollPayslipTemplate;
use App\Models\Payroll\PayrollPolicy;
use App\Models\Payroll\PayrollPolicyAllowance;
use App\Models\Payroll\PayrollPolicyDeduction;
use App\Models\Payroll\PayrollVariable;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\PayrunEmployee;
use App\Models\Payroll\PayrunEmployeeAllowance;
use App\Models\Payroll\SocialSecurity;
use App\Models\Payroll\TaxSlab;
use App\Models\Payroll\TaxSlabValue;
use App\Models\Payroll\UserPayrollPolicy;
use App\Models\Payroll\WorkTime;
use App\Models\Performance\Appraisal;
use App\Models\Performance\AppraisalMessage;
use App\Models\Performance\PerformanceGoal;
use App\Models\Recruitment\CandidateNote;
use App\Models\Recruitment\Job;
use App\Models\TimeOff\Leave;
use App\Models\Timesheet\Project;
use App\Models\Timesheet\ProjectChat;
use App\Models\Timesheet\TimesheetApproval;
use App\Tables\Columns\ProjectCard;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Notifications\Notification;
use Filament\Panel;
use GrahamCampbell\ResultType\Success;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasAvatar
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;


    public function getFilamentAvatarUrl(): ?string
    {
        $current_user = Employee::where('user_id',auth()->id())->with('media')->first();

        if($current_user?$current_user['media']->count() > 0:false){
            return $current_user->profile_picture_url;
        }
        else{
            return $this->avatar_url;
        }
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $user = Employment::with('employeeStatus')->where('user_id',$panel->auth()->id())->first();
        // dd($user);
        if( is_null($user)){
            Notification::make()
            ->title("Your profile is Incomplete,please contact Admin.")
            ->danger()
            ->send();
            return false;
        }
        elseif ($user->employeeStatus->name === 'Terminated') {
            Notification::make()
            ->title("Your are no more an employee.")
            ->danger()
            ->send();
            return false;
        }
        elseif($user->employeeStatus->name === 'Suspended'){
            Notification::make()
            ->title("Your are no more an employee.")
            ->danger()
            ->send();
            return false;
        }
        elseif( $user->employeeStatus->name === 'Inactive'){
            Notification::make()
            ->title("Your status is Inactive.")
            ->danger()
            ->send();
            return false;
        }


        elseif($user->employeeStatus->name === 'Active'){
            return true;
        }
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'is_active',
    ];


     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * This function returns true if the user can access the filament.
     *
     * @return bool A boolean value.
     */
    public function canAccessFilament(): bool
    {
        return true;
    }


    public function team()
    {
        return $this->hasOneThrough(Team::class, JobInfo::class, 'user_id', 'id', 'id', 'team_id');
    }
    public function ProjectCard()
    {
        return $this->hasMany(ProjectChat::class,'user_id');
    }
    public function chatuser()
    {
        return $this->hasMany(GroupMessage::class,'user_id');
    }

    public function memberchat()
    {
        return $this->hasMany(MemberChat::class,'user_id');
    }

    public function currentAddress()
    {
        return $this->morphOne(CurrentAddress::class, 'addressable');
    }

    public function permanentAddress()
    {
        return $this->morphOne(PermanentAddress::class, 'addressable');
    }

    public function salaryDetail()
    {
        return $this->hasOne(SalaryDetail::class, 'user_id');
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'user_id');
    }

    public function employment()
    {
        return $this->hasOne(Employment::class, 'user_id');
    }

    public function jobInfo()
    {
        return $this->hasOne(JobInfo::class, 'user_id');
    }

    public function supervisorJobInfo()
    {
        return $this->hasMany(JobInfo::class, 'report_to');
    }

    public function education()
    {
        return $this->hasMany(Education::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function feedlikes()
    {
        return $this->hasMany(FeedLike::class, 'user_id');
    }


    public function experience()
    {
        return $this->hasMany(Experience::class, 'user_id');
    }

    public function bankInfo()
    {
        return $this->hasOne(BankInfo::class, 'user_id');
    }

    public function emergencyContact()
    {
        return $this->hasMany(EmergencyContact::class, 'user_id');
    }

    // public function tasks()
    // {
    //     return $this->hasMany(OnboardingTask::class, 'user_id');
    // }

    // public function task()
    // {
    //     return $this->hasMany(Task::class);
    // }

    public function offboardingTask()
    {
        return $this->hasMany(OffboardingTask::class, 'user_id');
    }

    public function employeeOnboarding()
    {
        return $this->hasMany(EmployeeOnboarding::class, 'user_id');
    }
    public function assignedonboarding()
    {
        return $this->hasMany(EmployeeOnboarding::class, 'assigned_by');
    }

    public function employeeOffboarding()
    {
        return $this->hasMany(EmployeeOffboarding::class, 'user_id');
    }
    public function assignedoffbording()
    {
        return $this->hasMany(EmployeeOffboarding::class, 'assigned_by');
    }


    public function leave()
    {
        return $this->hasMany(Leave::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function benefit()
    {
        return $this->hasMany(EmployeeBenefit::class, 'user_id');
    }

    public function employeeAsset()
    {
        return $this->hasMany(EmployeeAsset::class, 'user_id');
    }

    public function Notes()
    {
        return $this->hasMany(Note::class, 'user_id');
    }

    public function files(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable');
    }

    public function employeeContracts()
    {
        return $this->hasOne(EmployeeContract::class, 'user_id');
    }

    public function reportsTo($userId)
    {
        if ($this->jobInfo->report_to == $userId) {
            return true;
        }

        if ($this->jobInfo->report_to !== null) {
            $supervisor = User::find($this->jobInfo->report_to);
            return $supervisor->reportsTo($userId);
        }

        return false;
    }

    public function performanceGoals()
    {
        return $this->hasMany(PerformanceGoal::class);
    }

    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'user_id');
    }

    public function getDirectReportCountAttribute() {
        return $this->supervisorJobInfo()->count();
    }

    public function employeeOnboardingWork()
    {
        return $this->hasManyThrough(
            EmployeeOnboardingTask::class, // the related model
            OnboardingTask::class, // the intermediate model
            'user_id', // Foreign key on intermediate model...
            'onboarding_task_id', // ...which connects to final model
            'id', // Local key on this model...
            'id' // ...which connects to intermediate model
        );
    }

    public function employeeOffboardingWork()
    {
        return $this->hasManyThrough(
            EmployeeOffboardingTask::class, // the related model
            OffboardingTask::class, // the intermediate model
            'user_id', // Foreign key on intermediate model...
            'offboarding_task_id', // ...which connects to final model
            'id', // Local key on this model...
            'id' // ...which connects to intermediate model
        );
    }

    public function employeeOnboardingTasks()
    {
        return $this->hasManyThrough(
            EmployeeOnboardingTask::class, // the related model
            EmployeeOnboarding::class, // the intermediate model
            'user_id', // Foreign key on intermediate model...
            'employee_onboarding_id', // ...which connects to final model
            'id', // Local key on this model...
            'id' // ...which connects to intermediate model
        );
    }

    public function employeeOffboardingTasks()
    {
        return $this->hasManyThrough(
            EmployeeOffboardingTask::class, // the related model
            EmployeeOffboarding::class, // the intermediate model
            'user_id', // Foreign key on intermediate model...
            'employee_offboarding_id', // ...which connects to final model
            'id', // Local key on this model...
            'id' // ...which connects to intermediate model
        );
    }

    public function timesheetApprovals()
    {
        return $this->hasMany(TimesheetApproval::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
// project
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function attendanceRecord()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
    public function budgetManager()
    {
        return $this->hasMany(BudgetManager::class);
    }

    public function payrunEmployee()
    {
        return $this->hasMany(PayrunEmployee::class);
    }

    public function taxSlabCreatedBy()
    {
        return $this->hasMany(TaxSlab::class, 'created_by');
    }

    public function taxSlabUpdatedBy()
    {
        return $this->hasMany(TaxSlab::class, 'updated_by');
    }

    public function taxSlabValueCreatedBy()
    {
        return $this->hasMany(TaxSlabValue::class, 'created_by');
    }

    public function taxSlabValueUpdatedBy()
    {
        return $this->hasMany(TaxSlabValue::class, 'updated_by');
    }

    public function allowanceCreatedBy()
    {
        return $this->hasMany(Allowance::class, 'created_by');
    }

    public function allowanceUpdatedBy()
    {
        return $this->hasMany(Allowance::class, 'updated_by');
    }

    public function deductionCreatedBy()
    {
        return $this->hasMany(Deduction::class, 'created_by');
    }

    public function deductionUpdatedBy()
    {
        return $this->hasMany(Deduction::class, 'updated_by');
    }

    public function overTimeRateCreatedBy()
    {
        return $this->hasMany(OverTimeRate::class, 'created_by');
    }

    public function overTimeRateUpdatedBy()
    {
        return $this->hasMany(OverTimeRate::class, 'updated_by');
    }

    public function workTimeCreatedBy()
    {
        return $this->hasMany(WorkTime::class, 'created_by');
    }

    public function workTimeUpdatedBy()
    {
        return $this->hasMany(WorkTime::class, 'updated_by');
    }

    public function socialSecurityCreatedBy()
    {
        return $this->hasMany(SocialSecurity::class, 'created_by');
    }

    public function socialSecurityUpdatedBy()
    {
        return $this->hasMany(SocialSecurity::class, 'updated_by');
    }

    public function payrollPolicyCreatedBy()
    {
        return $this->hasMany(PayrollPolicy::class, 'created_by');
    }

    public function payrollPolicyUpdatedBy()
    {
        return $this->hasMany(PayrollPolicy::class, 'updated_by');
    }

    public function payrollPolicyAllowanceCreatedBy()
    {
        return $this->hasMany(PayrollPolicyAllowance::class, 'created_by');
    }

    public function payrollPolicyAllowanceUpdatedBy()
    {
        return $this->hasMany(PayrollPolicyAllowance::class, 'updated_by');
    }

    public function payrollPolicyDeductionCreatedBy()
    {
        return $this->hasMany(PayrollPolicyDeduction::class, 'created_by');
    }

    public function payrollPolicyDeductionUpdatedBy()
    {
        return $this->hasMany(PayrollPolicyDeduction::class, 'updated_by');
    }

    public function UserPayrollPolicyCreatedBy()
    {
        return $this->hasMany(UserPayrollPolicy::class, 'created_by');
    }

    public function UserPayrollPolicyUpdatedBy()
    {
        return $this->hasMany(UserPayrollPolicy::class, 'updated_by');
    }

    public function UserPayrollPolicy()
    {
        return $this->hasOne(UserPayrollPolicy::class, 'user_id');
    }

    public function payrollVariableCreatedBy()
    {
        return $this->hasMany(PayrollVariable::class, 'created_by');
    }

    public function payrollVariableUpdatedBy()
    {
        return $this->hasMany(PayrollVariable::class, 'updated_by');
    }

    public function payrollPayslipTemplateCreatedBy()
    {
        return $this->hasMany(PayrollPayslipTemplate::class, 'created_by');
    }

    public function payrollPayslipTemplateUpdatedBy()
    {
        return $this->hasMany(PayrollPayslipTemplate::class, 'updated_by');
    }
    public function attendaceRecordCreatedBy()
    {
        return $this->hasMany(AttendanceRecord::class, 'created_by');
    }
    public function attendaceRecordUpdatedBy()
    {
        return $this->hasMany(AttendanceRecord::class, 'updated_by');
    }
    public function budgetCreatedBy()
    {
        return $this->hasMany(Budget::class, 'created_by');
    }
    public function budgetUpdatedBy()
    {
        return $this->hasMany(Budget::class, 'updated_by');
    }
    public function budgetManagerCreatedBy()
    {
        return $this->hasMany(BudgetManager::class, 'created_by');
    }
    public function budgetManagerUpdatedBy()
    {
        return $this->hasMany(BudgetManager::class, 'updated_by');
    }
    public function budgetTeamCreatedBy()
    {
        return $this->hasMany(BudgetTeam::class, 'created_by');
    }
    public function budgetTeamUpdatedBy()
    {
        return $this->hasMany(BudgetTeam::class, 'updated_by');
    }
    public function expenseCategoryCreatedBy()
    {
        return $this->hasMany(ExpenseCategory::class, 'created_by');
    }
    public function expenseCategoryUpdatedBy()
    {
        return $this->hasMany(ExpenseCategory::class, 'updated_by');
    }
    public function expenseTypeCreatedBy()
    {
        return $this->hasMany(ExpenseType::class, 'created_by');
    }
    public function expenseTypeUpdatedBy()
    {
        return $this->hasMany(ExpenseType::class, 'updated_by');
    }
    public function budgetExpenseCreatedBy()
    {
        return $this->hasMany(BudgetExpense::class, 'created_by');
    }
    public function budgetExpenseUpdatedBy()
    {
        return $this->hasMany(BudgetExpense::class, 'updated_by');
    }
    public function payrunCreatedBy()
    {
        return $this->hasMany(Payrun::class, 'created_by');
    }
    public function payrunUpdatedBy()
    {
        return $this->hasMany(Payrun::class, 'updated_by');
    }
    public function payrunEmployeeCreatedBy()
    {
        return $this->hasMany(PayrunEmployee::class, 'created_by');
    }
    public function payrunEmployeeUpdatedBy()
    {
        return $this->hasMany(PayrunEmployee::class, 'updated_by');
    }
    public function payrunEmployeeAllowanceCreatedBy()
    {
        return $this->hasMany(PayrunEmployeeAllowance::class, 'created_by');
    }
    public function payrunEmployeeAllowanceUpdatedBy()
    {
        return $this->hasMany(PayrunEmployeeAllowance::class, 'updated_by');
    }
    public function payrunEmployeeDeductionCreatedBy()
    {
        return $this->hasMany(PayrunEmployeeDeduction::class, 'created_by');
    }
    public function payrunEmployeeDeductionUpdatedBy()
    {
        return $this->hasMany(PayrunEmployeeDeduction::class, 'updated_by');
    }
    public function employeeTaxSlabValueCreatedBy()
    {
        return $this->hasMany(EmployeeTaxSlabValue::class, 'created_by');
    }
    public function employeeTaxSlabValueUpdatedBy()
    {
        return $this->hasMany(EmployeeTaxSlabValue::class, 'updated_by');
    }

    public function payrunEmployeePaymentCreatedBy()
    {
        return $this->hasMany(PayrunEmployeePayment::class, 'created_by');
    }
    public function payrunEmployeePaymentUpdatedBy()
    {
        return $this->hasMany(PayrunEmployeePayment::class, 'updated_by');
    }

    public function performanceGoalCreatedBy()
    {
        return $this->hasMany(PerformanceGoal::class, 'created_by');
    }
    public function performanceGoalUpdatedBy()
    {
        return $this->hasMany(PerformanceGoal::class, 'updated_by');
    }
    public function userappraisal()
    {
        return $this->hasMany(AppraisalMessage::class, 'created_by');
    }

    public function jobUpdatedBy()
    {
        return $this->hasMany(Job::class, 'updated_by');
    }
    public function jobCreatedBy()
    {
        return $this->hasMany(Job::class, 'created_by');
    }
    public function approvBy()
    {
        return $this->hasMany(Job::class, 'approved_by');
    }
    public function hiringUser()
    {
        return $this->hasMany(Job::class, 'hiring_lead_id');
    }
    public function candidateNotes()
    {
        return $this->hasMany(CandidateNote::class, 'create_by');
    }
    public function courseCreatedBy()
    {
        return $this->hasMany(Course::class, 'created_by');
    }
    public function courseUpdatedBy()
    {
        return $this->hasMany(Course::class, 'updated_by');
    }

    public function learningEmployee()
    {
        return $this->hasMany(LearningEmployee::class, 'user_id');
    }
    public function enrollmentCourse()
    {
        return $this->hasMany(EnrollmentCourse::class, 'user_id');
    }
    public function quizResponse()
    {
        return $this->hasMany(Course::class, 'user_id');
    }
    public function videoProgress()
    {
        return $this->hasMany(VideoProgress::class, 'user_id');
    }

    public function timesheetCreatedBy()
    {
        return $this->hasMany(Timesheet::class, 'created_by');
    }
    public function timesheetUpdatedBy()
    {
        return $this->hasMany(Timesheet::class, 'updated_by');
    }


}
