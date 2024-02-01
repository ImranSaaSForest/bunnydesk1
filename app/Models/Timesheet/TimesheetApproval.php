<?php

namespace App\Models\Timesheet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'timesheet_id',
        'user_id',
        'status',
        'comments',
    ];

    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
}
