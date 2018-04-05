<?php

namespace App\Models\Entities;

class OrderActivityLog extends BaseModel
{
    protected $table = 'order_activity_logs';
    protected $fillable = ['quote_id', 'location_id', 'order_id', 'activity', 'comment', 'action_detail'];

    public function order()
    {
        return $this->belongsTo(V6Quote::class, 'quote_id', 'QUOTE_ID');
    }
    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}