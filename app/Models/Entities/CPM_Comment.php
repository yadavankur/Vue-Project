<?php

namespace App\Models\Entities;


class CPM_Comment extends BaseModel
{
    const TYPE_CUSTOMER = 'CUSTOMER_NOTES';
    const TYPE_DOWELL = 'DOWELL_NOTES';

    protected $table = 'cpm_comments';
    protected $fillable = ['quote_id', 'location_id', 'order_id', 'activity_id', 'cpm_order_id',
                            'type', 'comment', 'created_at', 'updated_at', 'created_by', 'updated_by'];

}