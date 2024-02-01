<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BudgetExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_id',
        'expense_type_id',
        'limit',
        'auto_approved'
    ];
    public function reimbursementRequest()
    {
        return $this->hasMany(ReimbursementRequest::class);
    }
    /**
     * Get the user who created the budget expense.
     */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the budget expense.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }
    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budgetExpense) {
            if (Auth::check()) {
                $budgetExpense->created_by = Auth::id();
            }
        });

        static::updating(function ($budgetExpense) {
            if (Auth::check()) {
                $budgetExpense->updated_by = Auth::id();
            }
        });
    }
}
