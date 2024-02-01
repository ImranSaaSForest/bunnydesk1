<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default', 
        'active',
        'value', 
        'path'
    ];
}
