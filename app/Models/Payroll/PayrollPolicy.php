<?php

namespace App\Models\Payroll;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PayrollPolicy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'policy_from',
        'company_id',
        'tax_slab_id',
        'over_time_rate_id',
        'social_security_id',
    ];

    /**
     * Get the company associated with the payroll policy.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the tax slab associated with the payroll policy.
     */
    public function taxSlab()
    {
        return $this->belongsTo(TaxSlab::class, 'tax_slab_id');
    }

    /**
     * Get the overtime rate associated with the payroll policy.
     */
    public function overTimeRate()
    {
        return $this->belongsTo(OverTimeRate::class, 'over_time_rate_id');
    }

    /**
     * Get the social security associated with the payroll policy.
     */
    public function socialSecurity()
    {
        return $this->belongsTo(SocialSecurity::class, 'social_security_id');
    }

    /**
     * Get the user who created the payroll policy.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payroll policy.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function UserPayrollPolicy()
    {
        return $this->hasMany(UserPayrollPolicy::class, 'payroll_policy_id');
    }

    public function payrollPolicyAllowance()
    {
        return $this->hasMany(PayrollPolicyAllowance::class, 'payroll_policy_id');
    }

    public function payrollPolicyDeduction()
    {
        return $this->hasMany(PayrollPolicyDeduction::class, 'payroll_policy_id');
    }

    public function payRun()
    {
        return $this->hasMany(Payrun::class, 'payroll_policy_id');
    }

    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payrollPolicy) {
            if (Auth::check()) {
                $payrollPolicy->created_by = Auth::id();
            }
        });

        static::updating(function ($payrollPolicy) {
            if (Auth::check()) {
                $payrollPolicy->updated_by = Auth::id();
            }
        });
    }
}
