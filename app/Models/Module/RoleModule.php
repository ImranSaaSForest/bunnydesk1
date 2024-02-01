<?php

namespace App\Models\Module;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModule extends Model
{
    use HasFactory;


    protected $fillable = [
        'role_id',
        'module_id'
     ];

     public function role()
     {
         return $this->belongsTo(Role::class, 'role_id');
     }

     public function module()
     {
         return $this->belongsTo(Module::class, 'module_id');
     }


}
