<?php

namespace App\Models\Recruitment;

use App\Models\Company\Company;
use App\Models\Company\Designation;
use App\Models\Employee\EmployeeType;
use App\Models\Onboarding\OnboardingList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'job_status',
        'hiring_lead_id',

        'employee_type_id',
        'designation_id',
        'description',
        'city',
        'province',
        'postal_code',
        'country',
        'salary',
        'company_id',
        'interview_date',
        'created_by',
        'update_by',
        'approved_by'
    ];
    public function candidate(){
        return $this->hasMany(Candidate::class, 'job_id');
    }
    public function jobJobAdditional(){
        return $this->hasMany(JobJobAdditional::class, 'job_id');
    }
    public function hiring(){
        return $this->belongsTo(User::class, 'hiring_lead_id');
    }
    // public function onboardingIist(){
    //     return $this->belongsTo(OnboardingList::class, 'onboarding_list_id');
    // }
    public function employeeType(){
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }
    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function Company(){
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function createdBy()
    {
    return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
    return $this->belongsTo(User::class, 'updated_by');
    }
        /**
    * The booting method of the model.
    *
    * @return void
    */
    protected static function boot()
    {
    parent::boot();
    static::creating(function ($allowance) {
        if (Auth::check()) {
            $allowance->created_by = Auth::id();
        }
    });
    static::updating(function ($allowance) {
        if (Auth::check()) {
            $allowance->updated_by = Auth::id();
        }
    });
    }
}
