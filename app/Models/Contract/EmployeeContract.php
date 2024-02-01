<?php

namespace App\Models\Contract;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    use HasFactory;

    protected $fillable = ['contract_template_id', 'user_id'];

    public function template() 
    {
        return $this->belongsTo(ContractTemplate::class, 'contract_template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
