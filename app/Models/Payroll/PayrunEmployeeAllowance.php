<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PayrunEmployeeAllowance extends Model
{
    use HasFactory;
    protected $fillable=[
        'payrun_employee_id',
        'allowance_id',
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
    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'allowance_id');
    }
      /**
     * Get the user who created the payrun employee allowance.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payrun employee allowance.
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

        static::creating(function ($payrunEmployeeAllowance) {
            if (Auth::check()) {
                $payrunEmployeeAllowance->created_by = Auth::id();
            }
        });

        static::updating(function ($payrunEmployeeAllowance) {
            if (Auth::check()) {
                $payrunEmployeeAllowance->updated_by = Auth::id();
            }
        });
    }
}
