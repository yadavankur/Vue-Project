<?php

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;
use App\CS_ticket;

class V6Quote extends Model
{
    protected $table = 'V_V6_QUOTE';

    public function customer()
    {
        // select dbp_cust is too slow thus comment out for the time being
        // return $this->belongsTo(V6Customer::class, 'CUST_ID', 'CUST_ID')->with('dbp_cust');
        // return $this->belongsTo(V6Customer::class, 'CUST_ID', 'CUST_ID')->with('dbp_cust');
        return $this->belongsTo(V6Customer::class, 'CUST_ID', 'CUST_ID')->with('dbp_cust');
    }
    public function sales_person()
    { return $this->belongsTo(V6SalesPerson::class, 'SALES_PERSON', 'USER_ID');  }
    public function contact()
    { return $this->belongsTo(V6Contact::class, 'SHIP_TO_CONTACT_ID', 'CONTACT_ID'); }
    public function deliver_address()
    { return $this->belongsTo(V6Address::class, 'DEL_ADDR_ID', 'ADDR_ID');  }
    public function status()
    { return $this->belongsTo(V6Status::class, 'STATUS_ID', 'STATUS_ID'); }

    public function loccation()
    { return $this->belongsTo(location::class, 'QUOTE_NUM_PREF', 'abbreviation'); }

    public function color()
    {
        //return $this->belongsTo(V6Color::class, 'FINCOL_ID', 'FINCOL_ID');
        $color = $this->leftjoin('V_V6_FINCOL', function($join) {
                    $join->on('V_V6_FINCOL.FINCOL_ID', '=', 'V_V6_QUOTE.FINCOL_ID');
                    $join->on('V_V6_FINCOL.FINCOL_LIB_ID', '=', 'V_V6_QUOTE.FINCOL_LIB_ID');
            })->where('V_V6_QUOTE.FINCOL_ID', $this->FINCOL_ID)
            ->where('V_V6_QUOTE.FINCOL_LIB_ID', $this->FINCOL_LIB_ID)
            ->get(['V_V6_FINCOL.*'])->first();
        return $color;
    }
//    public function odporhd()
//    {
//        return $this->belongsTo(VAsOdporHd::class, 'UDF1', 'HORDNO');
//    }
    public function cash_rec()
    { return $this->belongsTo(V6CashRec::class, 'QUOTE_ID', 'QUOTE_ID');   }
    public function activity_logs()
    {
        return $this->hasMany(OrderActivityLog::class, 'quote_id', 'QUOTE_ID')->where('active',1)->orderBy('updated_at', 'desc');
    }
    public function cs_ticketss()
    {   //return $this->belongsTo(CS_ticket::class, 'QUOTE_ID', 'QUOTE_ID'); 
       // return $this->belongsTo(CS_ticket::class, 'order_id', 'QUOTE_ID');
       // return $this->belongsTo(Ticket_cs::class, 'QUOTE_ID', 'QUOTE_ID'); //---working
       return $this->hasMany(Ticket_cs::class, 'QUOTE_ID', 'QUOTE_ID')->where('active',1);
      // $tktno = Ticket_cs::orderBy('id', 'desc')->first();
     //  return $tktno;
      
       
    }
    public function cs_ticketno() //---not used
    {   $tktno = Ticket_cs::orderBy('id', 'desc')->first();
       // $delAddr = V6Address::where('ADDR_ID', $delAddrId)->first();
      // return $this->belongsTo(Ticket_cs::class)->orderBy('id', 'desc')->first();
      return $tktno;
       
    }
    public function customer_tier()
    {
        return $this->belongsTo(CustomerTier::class, 'CUST_ID', 'customer_id')->where('active',1)->with('customer_tier_level');
    }

    public function associated_jobs()
    {
        //return $this->hasMany(AssociatedJob::class, 'order_id', 'UDF1')->where('active',1);
        return $this->hasMany(AssociatedJob::class, 'order_quote_id', 'QUOTE_ID')->where('active',1);
    }

    public function booking_confirmation() {
        //return $this->belongsTo(BookingConfirmation::class, 'UDF1', 'order_id')->where('active',1);
        return $this->belongsTo(BookingConfirmation::class, 'QUOTE_ID', 'quote_id')->where('active',1);
    }

    public function location() {
        return $this->belongsTo(Location::class, 'QUOTE_NUM_PREF', 'abbreviation')->where('active',1)->with('services');
    }
}