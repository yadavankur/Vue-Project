<?php

namespace App\Models\Entities;

class Permission extends BaseModel
{
    const PERMISSION_RW = 'RW';
    const PERMISSION_R = 'R';
    const PERMISSION_D = 'D';
    const PERMISSION_H = 'H';

    public function roles()
    {
        return $this->belongsToMany(Role::class)->where('active',1);
    }
    static public function getHigherPermission($origPermission, $newPermission)
    {
        $retHigherPermission = Permission::PERMISSION_RW;
        if ( ($origPermission === Permission::PERMISSION_RW)
            || ($newPermission === Permission::PERMISSION_RW) )
        {
            $retHigherPermission = Permission::PERMISSION_RW;
        }
        else if ( ($origPermission === Permission::PERMISSION_R)
            || ($newPermission === Permission::PERMISSION_R) )
        {
            $retHigherPermission = Permission::PERMISSION_R;
        }
        else if ( ($origPermission === Permission::PERMISSION_D)
            || ($newPermission === Permission::PERMISSION_D) )
        {
            $retHigherPermission = Permission::PERMISSION_D;
        }
        else if ( ($origPermission === Permission::PERMISSION_H)
            || ($newPermission === Permission::PERMISSION_H) )
        {
            $retHigherPermission = Permission::PERMISSION_H;
        }
        return $retHigherPermission;
    }
}
