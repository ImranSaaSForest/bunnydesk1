<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'currency',
        'amount'
    ];

    public function employeeBenefit()
    {
        return $this->hasMany(EmployeeBenefit::class, 'benefit_id');
    }
}
