<?php

namespace App\Models\Performance;

use App\Models\Performance\PerformanceApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PerformanceGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'goal_description',
        'start_date',
        'end_date',
        'status',
        'rating_score',
        'comments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function AppraisalMessage()
    {
        return $this->hasMany(AppraisalMessage::class);
    }
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
