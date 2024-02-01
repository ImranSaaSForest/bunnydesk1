<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FeedComments extends Model
{
    use HasFactory;

    protected $fillable=[
        'feed_id',
        'comment',
        'parent_id'
    ];

    public function feeds(){

        return $this->belongsTo(Feed::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(FeedComments::class, 'parent_id');
    }

    public function subfeeds()
    {
        return $this->hasMany(FeedComments::class, 'parent_id');
    }

    public function likesComment()
    {
        return $this->hasMany(Like::class);
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

        static::creating(function ($feedcomment) {
            if (Auth::check()) {
                $feedcomment->created_by = Auth::id();
            }
        });

        static::updating(function ($feedcomment) {
            if (Auth::check()) {
                $feedcomment->updated_by = Auth::id();
            }
        });
    }
    
}
