<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExpenseType extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'expense_category_id'
    ];
    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
    public function budgetExpense()
    {
        return $this->hasMany(BudgetExpense::class);
    }
    /**
     * Get the user who created the expense type.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the expense type.
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

        static::creating(function ($expenseType) {
            if (Auth::check()) {
                $expenseType->created_by = Auth::id();
            }
        });

        static::updating(function ($expenseType) {
            if (Auth::check()) {
                $expenseType->updated_by = Auth::id();
            }
        });
    }
}
