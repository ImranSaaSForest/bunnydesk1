<?php

namespace App\Models\DailyUpdate;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_id',
        'content',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function date()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }
}
