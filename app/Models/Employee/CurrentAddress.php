<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'street',
        'city',
        'state',
        'zip',
        'permanent_address_same',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
