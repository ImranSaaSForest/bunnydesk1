<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PayrunEmployeePayment extends Model
{

    protected $fillable=[
        'payrun_employee_id',
        'employee_social_security',
        'employee_social_security_percentage',
        'employer_social_security',
        'employer_social_security_percentage',
        'taxable',
        'tax',
        'net_pay',
        'workman',
        'workman_percentage',
    ];
    use HasFactory;
    /**
     * The booting method of the model.
     *
     * @return void
     */

     public function payrunEmployee()
    {
        return $this->belongsTo(PayrunEmployee::class, 'payrun_employee_id');
    }
   
    public function socialSecurity()
    {
        return $this->belongsTo(SocialSecurity::class, 'social_security_id');
    }
      /**
     * Get the user who created the payrun employee payment.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payrun employee payment.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payrunEmployeePayment) {
            if (Auth::check()) {
                $payrunEmployeePayment->created_by = Auth::id();
            }
        });

        static::updating(function ($payrunEmployeePayment) {
            if (Auth::check()) {
                $payrunEmployeePayment->updated_by = Auth::id();
            }
        });
    }
}
