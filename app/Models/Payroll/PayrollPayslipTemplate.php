<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\Company;
use App\Models\User;

class PayrollPayslipTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id',
        'description',
        'content'
    ];

    public function payRun()
    {
        return $this->hasMany(Payrun::class, 'payroll_payslip_template_id');
    }
    /**
     * Get the company associated with the payroll payslip template.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created the payroll payslip template.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the payroll payslip template.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payrollPolicyDeduction) {
            if (Auth::check()) {
                $payrollPolicyDeduction->created_by = Auth::id();
            }
        });

        static::updating(function ($payrollPolicyDeduction) {
            if (Auth::check()) {
                $payrollPolicyDeduction->updated_by = Auth::id();
            }
        });
    }
}
