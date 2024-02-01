<?php

namespace App\Models\Finance;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [

        'company_id',
        'name',
        'description',
        'start_date',
        'frequency',
        'last_reset_date'
    ];
    /**
     * Get the company associated with the budget.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


    public function budgetManager()
    {
        return $this->hasMany(BudgetManager::class);
    }
    public function budgetTeam()
    {
        return $this->hasMany(BudgetTeam::class);
    }
    public function budgetExpense()
    {
        return $this->hasMany(BudgetExpense::class);
    }

    public function reimbursementRequest()
    {
        return $this->through('budgetExpense')->has('reimbursementRequest');
    }
    /**
     * Get the user who created the budget.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the budget.
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

        static::creating(function ($budget) {
            if (Auth::check()) {
                $budget->created_by = Auth::id();
            }
        });

        static::updating(function ($budget) {
            if (Auth::check()) {
                $budget->updated_by = Auth::id();
            }
        });
    }
}
