<?php

namespace App\Models\Entities;

class Component extends BaseModel
{
    protected $with=['page', 'parent', 'createdBy', 'updatedBy'];
    /**
     * Get all of the component's resources.
     */
    public function resources()
    {
        return $this->morphMany(GroupResourcePermission::class, 'resource', 'resource_type_id', 'resource_id')
            ->with('page')
            ->with('parent')
            ->where('active',1);
    }
    /**
     * Get the process associated with the component.
     */
    public function processes()
    {
        return $this->hasMany(Process::class)->where('active',1);
    }
    public function page()
    {
        return $this->belongsTo(Page::class)->where('active',1);
    }
    public function parent()
    {
        return $this->belongsTo(Component::class, 'parent_id')->where('active',1);
    }
    public function child_components()
    {
        return $this->hasMany(Component::class, 'parent_id', 'id')->where('active',1)->with('processes');
    }
}
