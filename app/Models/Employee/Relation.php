<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function emergencyContact()
    {
        return $this->hasMany(EmergencyContact::class, 'relation_id');
    }
}
