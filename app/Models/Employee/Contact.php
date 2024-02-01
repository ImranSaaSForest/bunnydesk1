<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_phone',
        'mobile_phone',
        'home_phone',
        'home_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
