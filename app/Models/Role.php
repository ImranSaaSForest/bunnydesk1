<?php

namespace App\Models;

use App\Models\Module\RoleModule;

class Role extends \Spatie\Permission\Models\Role
{


    public function role()
    {
        return $this->hasMany(RoleModule::class,);
    }

}
