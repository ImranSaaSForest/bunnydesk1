<?php

namespace App\Models\Recruitment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OfferLetterTemplate extends Model
{
    use HasFactory;

    protected $guarded=[];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($emailtemplate) {
            if (Auth::check()) {
                $emailtemplate->created_by = Auth::id();
            }
        });

        static::updating(function ($emailtemplate) {
            if (Auth::check()) {
                $emailtemplate->updated_by = Auth::id();
            }
        });
    }
}
