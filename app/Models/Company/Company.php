<?php

namespace App\Models\Company;

use App\Models\Asset\Asset;
use App\Models\Employee\Employee;
use App\Models\File\File;
use App\Models\Finance\Budget;
use App\Models\Finance\ExpenseCategory;
use App\Models\Payroll\Allowance;
use App\Models\Payroll\Deduction;
use App\Models\Payroll\OverTimeRate;
use App\Models\Payroll\PayrollPayslipTemplate;
use App\Models\Payroll\PayrollPolicy;
use App\Models\Payroll\PayrollVariable;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\SocialSecurity;
use App\Models\Payroll\TaxSlab;
use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\Job;
use App\Models\TimeOff\Policy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'company_type',
        'tax_id',
        'registration_id',
        'currency',
        'latitude',
        'longitude',
        'timezone',
        'phone',
        'mobile',
        'email',
        'website',
        'street',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'twitter',
        'facebook',
        'youtube',
        'instagram'
    ];
    public function candidate()
    {
        return $this->hasMany(Candidate::class, 'company_id');
    }

    public function employee()
    {
        return $this->hasMany(Employee::class, 'company_id');
    }

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'company_policy');
    }

    public function asset()
    {
        return $this->hasMany(Asset::class, 'company_id');
    }

    public function noteCategory()
    {
        return $this->hasMany(NoteCategory::class, 'company_id');
    }

    public function files(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable');
    }


    public function allowance()
    {
        return $this->hasMany(Allowance::class, 'company_id');
    }

    public function deduction()
    {
        return $this->hasMany(Deduction::class, 'company_id');
    }



    public function PayrollPolicy()
    {
        return $this->hasMany(PayrollPolicy::class, 'company_id');
    }

    public function PayrollVariable()
    {
        return $this->hasMany(PayrollVariable::class, 'company_id');
    }

    public function payrollPayslipTemplate()
    {
        return $this->hasMany(PayrollPayslipTemplate::class, 'company_id');
    }
    public function budget()
    {
        return $this->hasMany(Budget::class, 'company_id');
    }
    public function expenseCategory()
    {
        return $this->hasMany(ExpenseCategory::class, 'company_id');
    }
    public function payrun()
    {
        return $this->hasMany(Payrun::class, 'company_id');
    }
    public function jobCompany()
    {
        return $this->hasMany(Job::class, 'company_id');
    }


}
