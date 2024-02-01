<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function jobInfo()
    {
        return $this->hasMany(JobInfo::class, 'shift_id');
    }
}
