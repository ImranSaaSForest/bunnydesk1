<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PayrunEmployee extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'payrun_id',
        'payrun_employee_id',
        'total_working_hours',
        'total_paid_leave',
        'total_unpaid_leave',
        'gross_salary',
        'include_in_payrun'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function payrun()
    {
        return $this->belongsTo(Payrun::class, 'payrun_id');
    }
    public function payrunEmployeeAllowance()
    {
        return $this->hasMany(PayrunEmployeeAllowance::class);
    }
    public function payrunEmployeeDeduction()
    {
        return $this->hasMany(PayrunEmployeeDeduction::class);
    }
    public function employeeTaxSlabValue()
    {
        return $this->hasMany(EmployeeTaxSlabValue::class);
    }
    public function payrunEmployeePayment()
    {
        return $this->hasMany(PayrunEmployeePayment::class);
    }
      /**
     * Get the user who created the payrun employee.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payrun employee.
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

        static::creating(function ($payrunEmployee) {
            if (Auth::check()) {
                $payrunEmployee->created_by = Auth::id();
            }
        });

        static::updating(function ($payrunEmployee) {
            if (Auth::check()) {
                $payrunEmployee->updated_by = Auth::id();
            }
        });
    }
}
