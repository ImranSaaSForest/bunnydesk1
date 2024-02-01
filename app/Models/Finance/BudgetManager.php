<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BudgetManager extends Model
{
    use HasFactory;
    protected $fillable=[
        'budget_id',
        'manager_id'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }
    /**
     * Get the user who created the budget manager.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the budget manager.
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

        static::creating(function ($budgetManager) {
            if (Auth::check()) {
                $budgetManager->created_by = Auth::id();
            }
        });

        static::updating(function ($budgetManager) {
            if (Auth::check()) {
                $budgetManager->updated_by = Auth::id();
            }
        });
    }
}
