<?php

namespace App\Models\Entities;

class AssociatedJob extends BaseModel
{
    protected $table = 'associated_jobs';
    protected $with=['createdBy', 'updatedBy', 'quote', 'booking'];

    public function quote()
    {
        return $this->belongsTo(V6Quote::class, 'quote_id', 'QUOTE_ID')
            ->with('location')
            ->with('customer');
    }
    public function booking()
    {
        //return $this->belongsTo(BookingConfirmation::class, 'order_id', 'order_id');
        return $this->belongsTo(BookingConfirmation::class, 'quote_id', 'quote_id')->where('active', 1);
    }
}
