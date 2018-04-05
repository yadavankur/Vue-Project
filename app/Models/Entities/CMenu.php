<?php

namespace App\Models\Entities;

class CMenu extends BaseModel
{
    const MENU_HOME = 'Home';
    protected $with=['parent', 'createdBy', 'updatedBy'];
    protected $table = 'cmenus';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'created_by', 'updated_by', 'updated_at', 'active'
    ];
    public function parent()
    {
        return $this->belongsTo(CMenu::class, 'parent_id')->where('active',1);
    }
    /**
     * Get all of the menu's resources.
     */
    public function resources()
    {
        return $this->morphMany(GroupResourcePermission::class, 'resource', 'resource_type_id', 'resource_id')->where('active',1);
    }
    public function children()
    {
        return $this->hasMany(CMenu::class, 'parent_id', 'id')->where('active',1)->with('children');
    }
}
