<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WorkTime extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
        'over_time_rate_id',
        'per',
        'allowed_hour',
        'description',
    ];

    /**
     * Get the overtime rate associated with the work time.
     */
    public function overTimeRate()
    {
        return $this->belongsTo(OverTimeRate::class, 'over_time_rate_id');
    }

    /**
     * Get the user who created the work time.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the work time.
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

        static::creating(function ($workTime) {
            if (Auth::check()) {
                $workTime->created_by = Auth::id();
            }
        });

        static::updating(function ($workTime) {
            if (Auth::check()) {
                $workTime->updated_by = Auth::id();
            }
        });
    }
}
