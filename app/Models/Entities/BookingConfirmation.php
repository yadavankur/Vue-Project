<?php

namespace App\Models\Entities;


class BookingConfirmation extends BaseModel
{
    protected $dates = ['created_at', 'updated_at',
        'requested_date', 'agreed_date',
        'expedite_date', 'sys_generated_date',
        'confirmed_date'
    ];
    protected $with=['createdBy', 'updatedBy', 'order'];

    protected $fillable = ['quote_id', 'location_id', 'order_id', 'requested_date', 'sys_generated_date', 'active' , 'fully_paid'];

    protected $table = 'booking_confirmation';
    protected $casts = [
        'fully_paid' => 'boolean',
    ];

    const DATE_TYPE_CUSTOMER = 'CUSTOMER';
    const DATE_TYPE_EXPEDITE = 'EXPEDITE';
    const DATE_TYPE_AGREED = 'AGREED';
    const DATE_TYPE_SYSTEM = 'SYSTEM';

    const EMAIL_SINGLE_CONFIRMATION_TYPE_TELEPHONE_CALL = 'SINGLE_EMAIL_TELEPHONE_CALL';
    const EMAIL_SINGLE_CONFIRMATION_TYPE_REQUEST_CALL = 'SINGLE_EMAIL_REQUEST_CALL';
    const EMAIL_LIST_CONFIRMATION_TYPE_TELEPHONE_CALL = 'LIST_EMAIL_TELEPHONE_CALL';

    const TEXT_SINGLE_CONFIRMATION_TYPE_ORDER_CONFIRMATION = 'SINGLE_TEXT_ORDER_CONFIRMATION';
    const TEXT_SINGLE_CONFIRMATION_TYPE_REQUEST_CALL = 'SINGLE_TEXT_REQUEST_CALL';
    const TEXT_SINGLE_CONFIRMATION_TYPE_MONEY_OWING = 'SINGLE_TEXT_MONEY_OWING';

    public function order() {
        //return $this->belongsTo(V6Quote::class, 'order_id', 'UDF1')
        return $this->belongsTo(V6Quote::class, 'quote_id', 'QUOTE_ID')
            ->with('customer')
            ->with('sales_person')
            ->with('contact')
            ->with('status')
            ->with('deliver_address');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
}