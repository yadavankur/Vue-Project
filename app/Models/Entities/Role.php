<?php

namespace App\Models\Entities;

class Role extends BaseModel
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'active', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];
    public function parent()
    {
        return $this->belongsTo(Role::class, 'parent_id')->where('active',1);
    }
    /**
     * Get the groups associated with the user.
     */
    public function groups()
    {
        return $this->hasMany(Group::class)->where('active',1);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->where('active',1);
    }

}
