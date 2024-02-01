<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaxSlabValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'tax_slab_id',
        'start',
        'end',
        'cal_range',
        'fixed_amount',
        'percentage',
        'description',
    ];

    /**
     * Get the tax slab that owns the tax slab value.
     */
    public function taxSlab()
    {
        return $this->belongsTo(TaxSlab::class, 'tax_slab_id');
    }

    /**
     * Get the user who created the tax slab value.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the tax slab value.
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

        static::creating(function ($taxSlabValue) {
            if (Auth::check()) {
                $taxSlabValue->created_by = Auth::id();
            }
        });

        static::updating(function ($taxSlabValue) {
            if (Auth::check()) {
                $taxSlabValue->updated_by = Auth::id();
            }
        });
    }
}
