<?php

namespace App\Models\Entities;

class Group extends BaseModel
{
    /**
     * Get the roles that has the group.
     */
    public function role()
    {
        return $this->belongsTo(Role::class)->where('active',1);
    }

    /**
     * Get the group resource permission associated with the group.
     */
    public function groupResourcePermissions()
    {
        return $this->hasMany(GroupResourcePermission::class)->where('active',1)
            ->with('permission')
            ->with('resource')
            ->with('resourceType');
    }
    /**
     * Get the group resource permission associated with the group.
     * @param $resTypeId
     * @return array
     */
    public function groupResourcePermissionsByType($resTypeId)
    {
        $resType = [];
        foreach($this->groupResourcePermissions as $item)
        {
            if ($item->resource_type_id == $resTypeId)
                $resType[] = $item;
        }
        return $resType;
    }

    /**
     * Get the group menu resource permission associated with the group.
     */
    public function groupMenuResourcePermissions()
    {
        $resMenus = [];
        foreach($this->groupResourcePermissions as $item)
        {
            if ($item->resource_type_id == ResourceType::MENU)
                $resMenus[] = $item;
        }
        return $resMenus;
    }
    /**
     * Get the group tab resource permission associated with the group.
     */
    public function groupTabResourcePermissions()
    {
        $resTabs = [];
        foreach($this->groupResourcePermissions as $item)
        {
            if ($item->resource_type_id == ResourceType::TAB)
                $resTabs[] = $item;
        }
        return $resTabs;
    }
    /**
     * Get the group component resource permission associated with the group.
     */
    public function groupComponentResourcePermissions()
    {
        $resComponents = [];
        foreach($this->groupResourcePermissions as $item)
        {
            if ($item->resource_type_id == ResourceType::COMPONENT)
                $resComponents[] = $item;
        }
        return $resComponents;
    }

    /**
     * Get the users associated with the group.
     */
    public function users()
    {
        // return $this->hasManyThrough(Group::class, UserGroup::class, 'user_id', 'group_id', 'id');
        $users = User::join('user_group', 'groups.id', '=', 'user_group.group_id')
            ->whereRaw('user_group.group_id = ? and user_group.active = 1 and groups.active = 1', [$this->id])
            ->where('users.active', 1)
            ->get(['users.*']);
        return $users;
    }

    public function usergroups1()
    {
        return $this->hasMany(UserGroup::class)->where('active',1)->with('user');
    }
}
