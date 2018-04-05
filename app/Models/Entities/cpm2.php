<?php

//namespace App;
namespace App\Models\Entities;

//use Illuminate\Database\Eloquent\Model;

class cpm2 extends BaseModel
{
    //

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
}
