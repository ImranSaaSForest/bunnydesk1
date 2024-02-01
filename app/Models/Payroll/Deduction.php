<?php

namespace App\Models\Payroll;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Deduction extends Model
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
        'frequency',
        'max_count',
        'is_fixed',
        'amount',
        'percentage',
        'before_tax',
    ];

    /**
     * Get the company that owns the allowance.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the user who created the allowance.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the allowance.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payrollPolicyAllowance()
    {
        return $this->hasMany(PayrollPolicyAllowance::class, 'deduction_id');
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
