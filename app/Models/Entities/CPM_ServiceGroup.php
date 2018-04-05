<?php

namespace App\Models\Entities;


class CPM_ServiceGroup extends BaseModel
{
    protected $table = 'cpm_service_groups';

    public function service()
    {
        return $this->belongsTo(CPM_Service::class, 'service_id', 'id')->active()->with('location');
    }
    public function service_group_activities()
    {
        return $this->hasMany(CPM_ServiceGroupActivity::class, 'service_group_id', 'id')->active();
    }
}