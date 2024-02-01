<?php

namespace App\Models\Finance;

use App\Models\Employee\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BudgetTeam extends Model
{
    use HasFactory;
    protected $fillable=[
        'budget_id',
        'team_id'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    /**
     * Get the user who created the budget team.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the budget team.
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

        static::creating(function ($budgetTeam) {
            if (Auth::check()) {
                $budgetTeam->created_by = Auth::id();
            }
        });

        static::updating(function ($budgetTeam) {
            if (Auth::check()) {
                $budgetTeam->updated_by = Auth::id();
            }
        });
    }
}
