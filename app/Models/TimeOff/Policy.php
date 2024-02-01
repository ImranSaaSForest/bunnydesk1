<?php

namespace App\Models\TimeOff;
use App\Models\Company\Company;
use App\Models\Employee\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'is_active',
        'work_week_id',
        'holiday_id'
    ];

    public function workWeek()
    {
        return $this->belongsTo(WorkWeek::class, 'work_week_id');
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }

    public function policyleaveTypes()
    {
        return $this->hasMany(PolicyLeaveType::class, 'policy_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_policy');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_policy');
    }
}
