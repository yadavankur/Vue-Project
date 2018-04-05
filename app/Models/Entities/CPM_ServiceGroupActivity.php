<?php

namespace App\Models\Entities;


class CPM_ServiceGroupActivity extends BaseModel
{
    protected $table = 'cpm_service_group_activities';

    public function service_group()
    {
        return $this->belongsTo(CPM_ServiceGroup::class, 'service_group_id', 'id')->active();
    }
    public function activity()
    {
        return $this->belongsTo(CPM_Activity::class, 'activity_id', 'id')->active();
    }
}