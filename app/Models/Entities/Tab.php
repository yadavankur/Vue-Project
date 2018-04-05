<?php

namespace App\Models\Entities;
class Tab extends BaseModel
{   const TAB_DASHBOARD_NAME = 'Dashboard';
    const TAB_LINK_MY_ACTIVITIES = '/myactivities';
    protected $casts = [  'active' => 'boolean', 'isActive' => 'boolean', 'isGShow' => 'boolean', ];
    /*** Get all of the tab's resources.  */
    public function resources()
    {
        return $this->morphMany(GroupResourcePermission::class, 'resource', 'resource_type_id', 'resource_id')->where('active',1);
    }
    public function page()
    {   return $this->belongsTo(Page::class, 'page_id')->where('active',1);   }
}
