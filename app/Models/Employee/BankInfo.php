<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'name',
        'ifsc',
        'micr',
        'account_number',
        'branch_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
