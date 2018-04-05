<?php

namespace App\Models\Entities;

class CPMActivityGroup extends BaseModel
{
    protected $table = 'cpm_activity_groups';

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id')->where('active', 1);
    }
}
