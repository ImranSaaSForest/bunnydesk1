<?php

namespace App\Models\Recruitment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CandidateNote extends Model
{
    use HasFactory;
    protected $fillable = [

        'candidate_id',
        'notes',
        'create_by',

    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, "create_by");
    }
         /**
    * The booting method of the model.
    *
    * @return void
    */
    protected static function boot()
    {
    parent::boot();
    static::creating(function ($allowance) {
        if (Auth::check()) {
            $allowance->create_by = Auth::id();
        }
    });
    // static::updating(function ($allowance) {
    //     if (Auth::check()) {
    //         $allowance->updated_by = Auth::id();
    //     }
    // });
    }
}
