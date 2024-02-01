<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class ReimbursementRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'reason',
        'comments',
        'amount',
        'name',
        'description',
        'budget_expense_id',
        'attachment'
    ];
    
    // public function budget()
    // {
    //     return $this->belongsToMany(BudgetExpense::class, Budget::class);
    // }
    /**
     * Get the user who created the allowance.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the user who updated the allowance.
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function budgetExpense()
    {
        return $this->belongsTo(BudgetExpense::class, 'budget_expense_id');
    }
    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reimbursementRequest) {
            if (Auth::check()) {
                $reimbursementRequest->requested_by = Auth::id();
            }
        });

        static::updating(function ($reimbursementRequest) {
            if (Auth::check()) {
                $reimbursementRequest->processed_by = Auth::id();
            }
        });
    }
   
}
