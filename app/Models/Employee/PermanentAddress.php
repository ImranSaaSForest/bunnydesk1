<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanentAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'street',
        'city',
        'state',
        'zip',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
