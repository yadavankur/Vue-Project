<?php

namespace App\Models\Entities;

class Page extends BaseModel
{
    /**
     * Get all of the page's resources.
     */
    public function resources()
    {
        return $this->morphMany(GroupResourcePermission::class, 'resource', 'resource_type_id', 'resource_id')->where('active',1);
    }
    public function components()
    {
        return $this->hasMany(Component::class, 'page_id', 'id')->where('active',1)->where('parent_id', 0)->with('processes')->with('child_components');
    }
    public function tabs()
    { return $this->hasMany(Tab::class, 'page_id', 'id')->where('active',1);   }
}
