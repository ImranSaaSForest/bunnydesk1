<?php

namespace App\Models\Payroll;

use App\Models\Company\Company;
use App\Models\Employee\PaymentInterval;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payrun extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'payrun_id',
        'company_id',
        'payment_interval',
        'payroll_policy_id',
        'payroll_payslip_template_id',
        'is_approved',
        'status',
        'start',
        'end'

    ];

    public function payrunEmployee()
    {
        return $this->hasMany(PayrunEmployee::class);
    }
     /**
     * Get the company associated with the payrun variable.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
      /**
     * Get the payroll policy associated with the payrun .
     */
    public function payrollPolicy()
    {
        return $this->belongsTo(PayrollPolicy::class, 'payroll_policy_id');
    }
    public function paymentinterval()
    {
        return $this->belongsTo(PaymentInterval::class, 'payment_interval_id');
    }
    public function payrollPayslipTemplate()
    {
        return $this->belongsTo(PayrollPayslipTemplate::class, 'payroll_payslip_template_id');
    }
      /**
     * Get the user who created the payrun.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payrun.
     */
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

        static::creating(function ($payrun) {
            if (Auth::check()) {
                $payrun->created_by = Auth::id();
            }
        });

        static::updating(function ($payrun) {
            if (Auth::check()) {
                $payrun->updated_by = Auth::id();
            }
        });
    }
}
