<?php

namespace App\Models\Payroll;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OverTimeRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'percentage',
    ];

    /**
     * Get the user who created the over time rate.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the over time rate.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function workTime()
    {
        return $this->hasOne(WorkTime::class, 'over_time_rate_id');
    }

    public function PayrollPolicy()
    {
        return $this->hasMany(PayrollPolicy::class, 'over_time_rate_id');
    }

    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($overTimeRate) {
            if (Auth::check()) {
                $overTimeRate->created_by = Auth::id();
            }
        });

        static::updating(function ($overTimeRate) {
            if (Auth::check()) {
                $overTimeRate->updated_by = Auth::id();
            }
        });
    }
}
