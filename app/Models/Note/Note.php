<?php

namespace App\Models\Note;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'user_id', 
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
