<?php

namespace App\Models\DailyUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
    ];

    public function dailyWork()
    {
        return $this->hasMany(DailyWork::class, 'date_id');
    }
}
