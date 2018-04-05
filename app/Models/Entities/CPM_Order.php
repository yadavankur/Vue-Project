<?php

namespace App\Models\Entities;

class CPM_Order extends BaseModel
{
    protected $table = 'cpm_orders';
    public function service()
    {
        return $this->belongsTo(CPM_Service::class, 'service_id', 'id')->with('location')->where('active', 1);
    }
    public function order()
    {
        //return $this->belongsTo(V6Quote::class, 'order_id', 'UDF1')
        return $this->belongsTo(V6Quote::class, 'quote_id', 'QUOTE_ID')
            ->with('customer')
            ->with('sales_person')
            ->with('contact')
            ->with('status')
            ->with('deliver_address');
    }
    public function activity()
    {
        return $this->belongsTo(CPM_Activity::class, 'activity_id', 'id')->where('active', 1);
    }
    public function predecessor()
    {
        return $this->belongsTo(CPM_Activity::class, 'predecessor_id', 'id')->where('active', 1);
    }
    public function successor()
    {
        return $this->belongsTo(CPM_Activity::class, 'successor_id', 'id')->where('active', 1);
    }

}