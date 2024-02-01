<?php

namespace App\Models\Employee;

use App\Models\Payroll\Payrun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInterval extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function salaryDetail()
    {
        return $this->hasMany(SalaryDetails::class, 'payment_interval_id');
    }
    public function payrun()
    {
        return $this->hasMany(Payrun::class, 'payment_interval_id');
    }
}
