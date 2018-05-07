<?php

namespace App\Models\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class Ticket_cs extends BaseModel
{
    //

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
    public function v6quotee()
    {
        return $this->belongsTo(V6Quote::class, 'QUOTE_ID', 'QUOTE_ID')
        ->join('V_V6_CUSTOMER', 'V_V6_CUSTOMER.CUST_ID', '=', 'V_V6_QUOTE.CUST_ID', 'left outer')
        ->join('V_V6_SALESPERSON', 'V_V6_SALESPERSON.USER_ID', '=', 'V_V6_QUOTE.SALES_PERSON', 'left outer')
        ->join('V_V6_CONTACT', 'V_V6_CONTACT.CONTACT_ID', '=', 'V_V6_QUOTE.SHIP_TO_CONTACT_ID', 'left outer')
        ->join('V_V6_ADDR', 'V_V6_ADDR.ADDR_ID', '=', 'V_V6_QUOTE.DEL_ADDR_ID', 'left outer')
        ->join('V_V6_STATUS', 'V_V6_STATUS.STATUS_ID', '=', 'V_V6_QUOTE.STATUS_ID', 'left outer')
       // ->leftjoin('V_V6_QUOTE_ITEM', function($join) {
          //  $join->on('V_V6_QUOTE_ITEM.QUOTE_ID', '=', 'V_V6_QUOTE.QUOTE_ID');
          //  $join->on('V_V6_QUOTE_ITEM.QUOTE_VERS_STOP', '=', 'V_V6_QUOTE.QUOTE_VERS');
       // })
        ->with('cash_rec')
        ->with('activity_logs')
        ;
    }
    public function v6items()
    {
       return $this->hasMany(V6Quote::class, 'QUOTE_ID', 'QUOTE_ID')
       ->leftjoin('V_V6_QUOTE_ITEM', function($join) 
            {   $join->on('V_V6_QUOTE_ITEM.QUOTE_ID', '=', 'V_V6_QUOTE.QUOTE_ID');
                $join->on('V_V6_QUOTE_ITEM.QUOTE_VERS_STOP', '=', 'V_V6_QUOTE.QUOTE_VERS');
            })  ;
       // ->join('V_V6_BOM_EXTN', 'V_V6_BOM_EXTN.QUOTE_ITEM_ID', '=', 'V_V6_QUOTE_ITEM.QUOTE_ITEM_ID');
    }
    public function bomfinish()
    {
       return $this->hasMany(V6Quote::class, 'QUOTE_ID', 'QUOTE_ID')
       ->leftjoin('V_V6_QUOTE_ITEM', function($join) 
            {   $join->on('V_V6_QUOTE_ITEM.QUOTE_ID', '=', 'V_V6_QUOTE.QUOTE_ID');
                $join->on('V_V6_QUOTE_ITEM.QUOTE_VERS_STOP', '=', 'V_V6_QUOTE.QUOTE_VERS');
            })  //;
        ->join('V_V6_BOM_EXTN', 'V_V6_BOM_EXTN.QUOTE_ITEM_ID', '=', 'V_V6_QUOTE_ITEM.QUOTE_ITEM_ID');
    }
    public function bomcomponent()
    {
       return $this->hasMany(V6Quote::class, 'QUOTE_ID', 'QUOTE_ID')
       ->leftjoin('V_V6_QUOTE_ITEM', function($join) 
            {   $join->on('V_V6_QUOTE_ITEM.QUOTE_ID', '=', 'V_V6_QUOTE.QUOTE_ID');
                $join->on('V_V6_QUOTE_ITEM.QUOTE_VERS_STOP', '=', 'V_V6_QUOTE.QUOTE_VERS');
            })  //;
        ->join('V_V6_BOM_COMP', 'V_V6_BOM_COMP.QUOTE_ITEM_ID', '=', 'V_V6_QUOTE_ITEM.QUOTE_ITEM_ID');
    }
    public function location()
    {  return $this->belongsTo(Location::class, 'location_id', 'id')->where('active', 1);
    }
    public function ttype()
    {  return $this->belongsTo(tickettype::class, 'ticket_type_id', 'id')->where('active', 1);
    }
    public function ttype1()
    {   return $this->hasMany(tickettype1::class, 'ticket_no', 'ticket_no')->where('active', 1);
    }
    public function ttype2a()
    {  return $this->hasMany(tickettype2::class, 'ticket_no', 'ticket_no')->where('active', 1)->with('auserid','tstatus','agroupid');
    }
    public function ttype3()
    {  return $this->hasMany(tickettype3::class, 'ticket_no', 'ticket_no')->where('active', 1)->with('auserid','tstatus','agroupid');
    }
    public function ttype4()
    {  return $this->hasMany(tickettype4::class, 'ticket_no', 'ticket_no')->where('active', 1)->with('auserid','tstatus','agroupid');
    }
    public function ttype5()
    {  return $this->hasMany(tickettype5::class, 'ticket_no', 'ticket_no')->where('active', 1)->with('auserid','tstatus','agroupid');
    }
    public function tstatus()
    {  return $this->belongsTo(ticketstatus::class, 'status', 'id')->where('active', 1);
    }
    public function userid()
    {
        return $this->belongsTo(user::class, 'user_id', 'id')->where('active', 1);
                                    //column name in ticket_cs table//then column name in status table
    }
    public function auserid() //allocated user_id
    {
        return $this->belongsTo(user::class, 'allocated_user_id', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
    public function buserid() //allocated user_id
    {
        return $this->belongsTo(user::class, 'managed_user_id', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
    public function agroupid()
    {
        return $this->belongsTo(Group::class, 'GROUP_ID', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
    }
    public function bgroupid()
    {
        return $this->belongsTo(Group::class, 'user_id', 'id')->where('active', 1);
                                                      //column name in ticket_cs table//then column name in status table
                                                      //->where('PRICE','QUOTE_VERS_STOP')
    }

    public function items() {
       // return $this->belongsTo(V6QuoteItem::class, 'QUOTE_ID', 'QUOTE_ID')->where('QUOTE_VERS_STOP',$this->PRICE);
     //  return $this->hasMany(tickettype1::class, 'ticket_no', 'ticket_no')->where('active', 1);
       // return $this->belongsTo(V6QuoteItem::class,'QUOTE_ID', 'QUOTE_ID')->whereRaw('V_V6_QUOTE_ITEM.QUOTE_VERS_STOP = ticket_cs.PRICE');
      //  return $this->hasMany(V6QuoteItem::class, 'QUOTE_ID', 'QUOTE_ID')->whereRaw('V_V6_QUOTE_ITEM.QUOTE_VERS_STOP=ticket_cs.PRICE');
      //  return $this->hasMany(V6QuoteItem::class, 'QUOTE_ID', 'QUOTE_ID')->whereRaw('[V_V6_QUOTE_ITEM].[QUOTE_VERS_STOP] = [ticket_cs].[PRICE]');
       return $this->hasMany(V6QuoteItem::class, 'QUOTE_ID', 'QUOTE_ID')->where('QUOTE_VERS_STOP', \DB::raw("ticket_cs.PRICE as PRICE"));
    //    return $this->hasMany(V6QuoteItem::class, 'QUOTE_ID', 'QUOTE_ID')->where('QUOTE_VERS_STOP', 4);  //---working
      //  return $this->belongsTo(ticketstatus::class, 'status', 'id')->where('active', 1);
    }
}
