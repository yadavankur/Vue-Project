<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class ticketerrortype extends BaseModel
{
    //

   public function ttype()
    {  return $this->belongsTo(tickettype::class, 'ticket_type_id', 'id')->where('active', 1);
    }
}
