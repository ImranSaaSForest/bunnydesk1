<?php

namespace App\Models\Payroll;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SocialSecurity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'employee_contribution',
        'employer_contribution',
        'description',
        'before_tax',
    ];


    /**
     * Get the user who created the social security.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the social security.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function PayrollPolicy()
    {
        return $this->hasMany(PayrollPolicy::class, 'social_security_id');
    }

    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($socialSecurity) {
            if (Auth::check()) {
                $socialSecurity->created_by = Auth::id();
            }
        });

        static::updating(function ($socialSecurity) {
            if (Auth::check()) {
                $socialSecurity->updated_by = Auth::id();
            }
        });
    }
}
