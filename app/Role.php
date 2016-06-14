<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public static function getAdminRole() {
        return Role::where('name', 'admin')->first();
    }

    public static function getSuperAdminRole()
    {
        return Role::where('name', 'superadmin')->first();
    }
}
