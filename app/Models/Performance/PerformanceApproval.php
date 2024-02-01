<?php

namespace App\Models\Performance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'performance_goal_id',
        'user_id',
        'status',
        'comments',
        'rating_score'
    ];

    public function performanceGoal()
    {
        return $this->belongsTo(PerformanceGoal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
