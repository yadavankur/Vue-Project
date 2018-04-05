<?php

namespace App\Models\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable, RecordSignature;
    protected $with=['createdBy', 'updatedBy'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'active', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];

    /**
     * Get the usergroups associated with the user.
     */
    public function usergroups()
    {
        return $this->hasMany(UserGroup::class)->where('active',1)->with('group');
    }
    /**
     * Get the groups associated with the user.
     */
    public function groups()
    {
        // return $this->hasManyThrough(Group::class, UserGroup::class, 'user_id', 'group_id', 'id');
        $groups = Group::join('user_group', 'groups.id', '=', 'user_group.group_id')
            ->whereRaw('user_group.user_id = ? and user_group.active = 1 and groups.active = 1', [$this->id])
            ->get(['groups.*']);
        return $groups;
    }
    /**
     * Get the roles associated with the user.
     */
    public function roles()
    {
        $roles = Role::join('groups', 'groups.role_id', '=', 'roles.id')
            ->join('user_group', 'groups.id', '=', 'user_group.group_id')
            ->whereRaw('user_group.user_id = ? and roles.active = 1 and groups.active = 1 and user_group.active = 1', [$this->id])
            ->orderBY('roles.id', 'asc')
            ->get(['roles.*']);
        return $roles;
    }
}
