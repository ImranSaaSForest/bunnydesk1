<?php

namespace App\Models\Payroll;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaxSlab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
    ];


    /**
     * Get the user who created the tax slab.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the tax slab.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function taxSlabValue()
    {
        return $this->hasMany(TaxSlabValue::class, 'tax_slab_id');
    }

    public function PayrollPolicy()
    {
        return $this->hasMany(PayrollPolicy::class, 'tax_slab_id');
    }

    // To auto-update the created_by and updated_by fields based on the logged-in user
    /**
     * The booting method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($branch) {
            if (Auth::check()) {
                $branch->created_by = Auth::id();
            }
        });
        static::updating(function ($branch) {
            if (Auth::check()) {
                $branch->updated_by = Auth::id();
            }
        });
    }
}
