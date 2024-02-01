<?php

namespace App\Models\TimeOff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_id', 
        'leave_date',
        'day_part',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class, 'leave_id');
    }
}
