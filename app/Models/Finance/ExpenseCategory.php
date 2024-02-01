<?php

namespace App\Models\Finance;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExpenseCategory extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'company_id'
    ];

    public function expenseType()
    {
        return $this->hasMany(ExpenseType::class);
    }
     /**
     * Get the company associated with the expense category.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    /**
     * Get the user who created the expense category.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the expense category.
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

        static::creating(function ($expenseCategory) {
            if (Auth::check()) {
                $expenseCategory->created_by = Auth::id();
            }
        });

        static::updating(function ($expenseCategory) {
            if (Auth::check()) {
                $expenseCategory->updated_by = Auth::id();
            }
        });
    }
}
