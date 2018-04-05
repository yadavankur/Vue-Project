<?php

namespace App\Models\Entities;

class CPMActivityUser extends BaseModel
{
    protected $table = 'cpm_activity_users';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->where('active', 1);
    }

}
