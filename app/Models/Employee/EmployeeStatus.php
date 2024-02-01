<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employment()
    {
        return $this->hasMany(Employment::class, 'employee_status_id');
    }
}
