<?php


namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class tickettype2 extends BaseModel
{
    //

    public function tstatus()
    {
        return $this->belongsTo(ticketcnstatus::class, 'aa', 'id')->where('active', 1);
                             //column name in ticket_cs table//then column name in status table
    }

    public function auserid() //allocated user_id
    {
        return $this->belongsTo(user::class, 'bb', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
    public function agroupid()
    {
        return $this->belongsTo(Group::class, 'cc', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
}
