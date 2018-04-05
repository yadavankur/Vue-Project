<?php

namespace App\Models\Entities;

class State extends BaseModel
{   /*** Get the locations associated with the state.  */
    public function locations()
    {    return $this->hasMany(Location::class)->where('active',1);    }
}
