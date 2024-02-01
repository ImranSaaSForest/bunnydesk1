<?php

namespace App\Models\Timesheet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'message'
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
