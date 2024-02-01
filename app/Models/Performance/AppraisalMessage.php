<?php

namespace App\Models\Performance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AppraisalMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'performance_goal_id',
        'message',
        'created_by',
        'date',
        'default',

    ];
    public function appraisal()
    {
        return $this->belongsTo(PerformanceGoal::class, 'performance_goal_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    /**
* Get the user who updated the allowance.
*/

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
}
}
