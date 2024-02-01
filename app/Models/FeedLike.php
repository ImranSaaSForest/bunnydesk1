<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedLike extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'feeds_id'
        
    ];

    public function users(){

        return $this->belongsTo(User::class);
    }

    public function feeds(){

        return $this->belongsTo(Feed::class);
    }

}
