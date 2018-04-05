<?php

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

class V6Customer extends Model
{
    protected $table = 'V_V6_CUSTOMER';

    public function dbp_cust()
    {
        return $this->belongsTo(VAsDbpCust::class, 'CUST_CODE', 'CSCUST');
    }
}