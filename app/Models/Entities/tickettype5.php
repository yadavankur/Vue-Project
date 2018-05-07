<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class tickettype5 extends BaseModel
{
    //
    public function tstatus()
    {
        return $this->belongsTo(ticketcnstatus::class, 'astatus', 'id')->where('active', 1);
                             //column name in ticket_cs table//then column name in status table
    }

    public function auserid() //allocated user_id
    {
        return $this->belongsTo(user::class, 'auser', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
    public function agroupid()
    {
        return $this->belongsTo(Group::class, 'agroup', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
}
