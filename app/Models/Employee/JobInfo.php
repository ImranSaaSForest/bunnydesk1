<?php

namespace App\Models\Employee;

use App\Models\Company\Designation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation_id',
        'report_to',
        'team_id',
        'shift_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'report_to');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
