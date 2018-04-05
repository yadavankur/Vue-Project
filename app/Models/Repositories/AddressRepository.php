<?php

namespace App\Models\Repositories;


use App\Models\Entities\Location;
use App\Models\Entities\V6Address;
use Exception;
use DB;

class AddressRepository extends BaseRepository
{
    public function model()
    {  return 'App\Models\Entities\V6Address';   }
    public function changeAddress($request)
    {
        $quoteId = $request->quoteId;
        $orderId = $request->orderId;
        $quoteVers = $request->quoteVers;
        $delAddrId = $request->delAddrId;
        $locationAbbr = $request->location;

        $addr1 = $request->address1;        $addr2 = $request->address2;
        $addr3 = $request->address3;        $addr4 = $request->address4;
        $addr5 = $request->address5;        $addr6 = $request->address6;

        $delAddr = V6Address::where('ADDR_ID', $delAddrId)->first();
        if (!$delAddr instanceof V6Address)
        {     throw new Exception('Invalid delivery address!');    }
        $location = Location::where('abbreviation', $locationAbbr)->active()->first();
        if (!$location instanceof Location)     throw new Exception('Invalid location!');
        $locationId = $location->id;

        $originalAddress = '['. $delAddr->ADDR_1 . ']' .
            ' ['. $delAddr->ADDR_2 . ']' .
            ' ['. $delAddr->ADDR_3 . ']' .
            ' ['. $delAddr->ADDR_4 . ']' .
            ' ['. $delAddr->ADDR_5 . ']' .
            ' ['. $delAddr->ADDR_6 . ']';
        $newAddress = "[$addr1] [$addr2] [$addr3] [$addr4] [$addr5] [$addr6]";
        self::AddLog($quoteId, $locationId, $orderId, 'Changed delivery address', 'Booking',
            "Changed address for quoteId: [$quoteId ], addressId: [$delAddrId] FROM $originalAddress TO $newAddress");

        // haven't been created yet
        // need to be tweaked further
        // TODO need to test and confirm further between V6 and OMS
       // $ret = true;
        $ret = DB::select("SET NOCOUNT ON; DECLARE @intRet INT; EXEC dbo.CHANGE_V6_DEL_ADDR_ID ?,?,?,?,?,?,?,?,?, @intRet OUTPUT; SELECT @intRet;",array($quoteId, $quoteVers, $delAddrId, $addr1, $addr2, $addr3, $addr4, $addr5, $addr6));
        return $ret;

      //  $ret = DB::select("SET NOCOUNT ON; DECLARE @intRet INT; EXEC dbo.NEW_V6_Contact_ID ?, ?, ?, ?, ?, @intRet OUTPUT; SELECT @intRet;",
      //  array($quoteId, $quoteVers, $customerId, $contactName, $phone));
    }
}