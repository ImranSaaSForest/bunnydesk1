<?php

namespace App\Models\TimeOff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function holidayDates()
    {
        return $this->hasMany(HolidayDate::class, 'holiday_id');
    }

    public function timeOffPolicy()
    {
        return $this->hasMany(Policy::class, 'holiday_id');
    }
}
