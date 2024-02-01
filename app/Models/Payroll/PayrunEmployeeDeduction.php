<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PayrunEmployeeDeduction extends Model
{
    use HasFactory;
    protected $fillable=[
        'payrun_employee_id',
        'deduction_id',
        'occurrence',
        'include_in_payrun',
        'frequency',
        'is_fixed',
        'amount',
        'percentage',
        'before_tax',


    ];
    public function payrunEmployeePayment()
    {
        return $this->hasMany(PayrunEmployeePayment::class);
    }
    public function payrunEmployee()
    {
        return $this->belongsTo(PayrunEmployee::class, 'payrun_employee_id');
    }
     /**
     * Get the allowance associated with the payrun.
     */
    public function deduction()
    {
        return $this->belongsTo(Deduction::class, 'deduction_id');
    }
      /**
     * Get the user who created the payrun employee deduction.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');

    }

    /**
     * Get the user who updated the payrun employee deduction.
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

        static::creating(function ($payrunEmployeeDeduction) {
            if (Auth::check()) {
                $payrunEmployeeDeduction->created_by = Auth::id();
            }
        });

        static::updating(function ($payrunEmployeeDeduction) {
            if (Auth::check()) {
                $payrunEmployeeDeduction->updated_by = Auth::id();
            }
        });
    }
}
