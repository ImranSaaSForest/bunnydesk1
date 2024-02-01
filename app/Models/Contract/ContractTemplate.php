<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'template', 
        'start_date', 
        'end_date', 
        'active'
    ];

    public function employeeContracts()
    {
        return $this->hasMany(EmployeeContract::class, 'contract_template_id');
    }
}
