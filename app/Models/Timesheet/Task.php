<?php

namespace App\Models\Timesheet;

use App\Models\User;
use Database\Seeders\Employee\UsersTableSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'name',
        'description',
        'start_date',
        'end_date'

    ];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
  /**
     * Get the user who created the payroll policy.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payroll policy.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($timesheet) {
            if (Auth::check()) {
                $timesheet->created_by = Auth::id();
            }
        });

        static::updating(function ($timesheet) {
            if (Auth::check()) {
                $timesheet->updated_by = Auth::id();
            }
        });
    }
}
