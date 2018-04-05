<?php

namespace App\Models\Entities;

class CPM_Master extends BaseModel
{
    protected $table = 'cpm_masters';

    public function predecessor()
    {
        return $this->belongsTo(CPM_Activity::class, 'predecessor_id', 'id')->where('active', 1);

    }
    public function successor()
    {
        return $this->belongsTo(CPM_Activity::class, 'successor_id', 'id')->where('active', 1);
    }
    public function activity()
    {
        return $this->belongsTo(CPM_Activity::class, 'activity_id', 'id')->where('active', 1);
    }
    public function service()
    {
        return $this->belongsTo(CPM_Service::class, 'service_id', 'id')->where('active', 1)->with('location');
    }
}