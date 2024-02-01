<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    
    protected $fillable = [

       'name'
    ];

    public function roleModule()
    {
        return $this->hasMany(RoleModule::class,);
    }

}
