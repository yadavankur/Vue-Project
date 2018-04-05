<?php

namespace App\Models\Entities;

class Location extends BaseModel
{
    protected $with=['state','services', 'createdBy', 'updatedBy'];
    /**
     * Get all of the location's resources.
     */
    public function resources()
    {
        return $this->morphMany(GroupResourcePermission::class, 'resource', 'resource_type_id', 'resource_id')
            ->where('active',1)->with('state');
    }
    public function state()
    {
        return $this->belongsTo(State::class)->where('active',1);
    }
    public function services()
    {
        return $this->hasMany(CPM_Service::class)->where('active', 1);
    }
}
