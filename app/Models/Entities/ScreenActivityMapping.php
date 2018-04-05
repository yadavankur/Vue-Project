<?php

namespace App\Models\Entities;


class ScreenActivityMapping extends BaseModel
{
    protected $table = 'screen_activity_mappings';

    public function activity()
    {
        return $this->belongsTo(CPM_Activity::class, 'activity_id', 'id')->where('active', 1);
    }

}