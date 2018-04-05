<?php

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;

class V6CashRec extends Model
{
    protected $table = 'V_V6_CASH_REC';

    public function paid_amount()
    {
        return $this->belongsTo(V6Quote::class, 'QUOTE_ID', 'QUOTE_ID');
    }
}