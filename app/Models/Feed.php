<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Feed extends Model
{
    use HasFactory;

    protected $fillable=[
        'image',
        'caption',
        
    ];




    // public function user(){

    //     return $this->belongsTo(User::class);
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function feedComment(){
        
        return $this->hasMany(FeedComments::class, 'feed_id');
    }

    public function feedLike(){
        
        return $this->hasMany(FeedLike::class, 'feeds_id');
    }

    /**
     * Get the user who updated the payroll policy.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feed) {
            if (Auth::check()) {
                $feed->created_by = Auth::id();
            }
        });

        static::updating(function ($feed) {
            if (Auth::check()) {
                $feed->updated_by = Auth::id();
            }
        });
    }

}
