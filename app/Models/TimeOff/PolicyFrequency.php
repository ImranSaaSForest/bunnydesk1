<?php

namespace App\Models\TimeOff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyFrequency extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function policyleaveTypes()
    {
        return $this->hasMany(PolicyLeaveType::class, 'policy_frequency_id');
    }
}
