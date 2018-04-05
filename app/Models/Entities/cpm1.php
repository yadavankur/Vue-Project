<?php

//namespace App;
namespace App\Models\Entities;

//use Illuminate\Database\Eloquent\Model;

class cpm1 extends BaseModel
{
    //

    public function service_group_activity()
    {
        return $this->belongsTo(CPM_ServiceGroupActivity::class, 'id', 'activity_id')->where('active', 1)->with('service_group');
    }
    public function service()
    {
        return $this->belongsTo(CPM_Service::class, 'service_id', 'id')->where('active', 1)->with('location');
    }

}
