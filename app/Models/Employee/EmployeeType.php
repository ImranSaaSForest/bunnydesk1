<?php

namespace App\Models\Employee;

use App\Models\Recruitment\Job;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employment()
    {
        return $this->hasMany(Employment::class, 'employee_type_id');
    }
    public function jobEmployeeType()
    {
        return $this->hasMany(Job::class, 'employee_type_id');
    }
}
