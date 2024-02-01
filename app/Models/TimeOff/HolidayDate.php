<?php

namespace App\Models\TimeOff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'holiday_id',
        'holiday_date',
        'description',
        'optional',
    ];

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }
}
