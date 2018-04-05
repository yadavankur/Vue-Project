<?php

namespace App\Models\Repositories;

use App\Models\Entities\Location;
use App\Models\Entities\V6Status;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderListRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\V6Quote';
    }

    /**
     * @param $request
     * @param $userService
     * @return mixed
     */
    public function getByPaginate($request, $userService)
    {
        $sort = $request->sort;    $sort = explode('|', $sort);
        $sortBy = $sort[0];        $sortDirection = $sort[1];
        $perPage = $request->per_page;
        $search = $request->filter;
        $query = $this->model->select([
            'V_V6_QUOTE.*',
            DB::raw('(V_V6_QUOTE.NETT_SELL_PRICE + V_V6_QUOTE.NETT_SELL_PRC_LAB) AS TOTALAMOUNT'),
            'V_V6_CUSTOMER.CUST_NAME',
            'V_V6_STATUS.DESCR',
        ])
            ->join('V_V6_CUSTOMER', 'V_V6_CUSTOMER.CUST_ID', '=', 'V_V6_QUOTE.CUST_ID', 'left outer')
            ->join('V_V6_SALESPERSON', 'V_V6_SALESPERSON.USER_ID', '=', 'V_V6_QUOTE.SALES_PERSON', 'left outer')
            ->join('V_V6_CONTACT', 'V_V6_CONTACT.CONTACT_ID', '=', 'V_V6_QUOTE.SHIP_TO_CONTACT_ID', 'left outer')
            ->join('V_V6_ADDR', 'V_V6_ADDR.ADDR_ID', '=', 'V_V6_QUOTE.DEL_ADDR_ID', 'left outer')
            ->join('V_V6_STATUS', 'V_V6_STATUS.STATUS_ID', '=', 'V_V6_QUOTE.STATUS_ID', 'left outer')
            ->join('V_V6_CASH_REC', 'V_V6_CASH_REC.QUOTE_ID', '=', 'V_V6_QUOTE.QUOTE_ID', 'left outer')
            ->leftjoin('V_V6_FINCOL', function($join) {
                $join->on('V_V6_FINCOL.FINCOL_ID', '=', 'V_V6_QUOTE.FINCOL_ID');
                $join->on('V_V6_FINCOL.FINCOL_LIB_ID', '=', 'V_V6_QUOTE.FINCOL_LIB_ID');
            })
            //->join('V_AS_ODPORHD', 'V_AS_ODPORHD.HORDNO', '=', 'V_V6_QUOTE.UDF1') // extremely slow thus comment for now
            ->where('V_V6_STATUS.AREA_ID', 3)
            ->with('customer')
            ->with('sales_person')
            ->with('contact')
            ->with('deliver_address')
            ->with('cash_rec')
            //->with('odporhd')
            //->with('color')
            ->with('activity_logs') // maybe not to get here if the performance is not good
            ->with('cs_ticketss')
           // ->with('cs_ticketno')
            ->with('status')
            ->with('loccation')
            ->orderBy($sortBy, $sortDirection);

        $salesContact = trim($search['salesContact']);
        $salesOrderNumber = trim($search['salesOrderNumber']);
        $superVisorUpdated = trim($search['superVisorUpdated']);
        $customerAddress = trim($search['customerAddress']);
        $customerName = trim($search['customer']['name']);
        $customerCode = trim($search['customer']['code']);
        $orderStatus = trim($search['orderStatus']);
        $locationCode = trim($search['orderLocation']);
//        $dateRangeFrom = trim($search['dateRange'][0]);
//        $dateRangeTo = trim($search['dateRange'][1]);
        if (count($search['dateRange']) == 2)
        {
            $dateRangeFrom = trim($search['dateRange'][0]);
            $dateRangeTo = trim($search['dateRange'][1]);
        }
        else
        {
            $dateRangeFrom = '';
            $dateRangeTo = '';
        }
        // get location code transformed to abbreviation
        if ($locationCode)
        {
            $query->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
                ->where('locations.id', $locationCode);
        }
        else
        {
            // get all assigned location ids   // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user, $userService);
            $query->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
                ->wherein('locations.id', $locationIds);
        }
        if ($orderStatus)  {   $like = "%{$orderStatus}%";  $query->where('V_V6_STATUS.DESCR', 'LIKE', $like) ->where('V_V6_STATUS.AREA_ID',3);}
        if ($customerName) {   $like = "%{$customerName}%";  $query->where('V_V6_CUSTOMER.CUST_NAME', 'LIKE', $like);   }
        if ($customerCode) {   $like = "%{$customerCode}%";   $query->where('V_V6_CUSTOMER.CUST_CODE', 'LIKE', $like);  }
        if ($salesContact) {   $like = "%{$salesContact}%"; $query->where('V_V6_SALESPERSON.USER_NAME', 'LIKE', $like);  }
        if ($salesOrderNumber) {   $like = "%{$salesOrderNumber}%";  $query->where('V_V6_QUOTE.UDF1', 'LIKE', $like);    }
        if ($superVisorUpdated) {   $like = "%{$superVisorUpdated}%"; $query->where('V_V6_CONTACT.CONTACT_NAME', 'LIKE', $like); }
        if ($customerAddress)
        {
            $like = "%{$customerAddress}%";
            $query->where('V_V6_ADDR.ADDR_1', 'LIKE', $like)
                  ->orwhere('V_V6_ADDR.ADDR_3', 'LIKE', $like);
        }
        if ($dateRangeFrom)
        {
            $dateRangeFrom = Carbon::createFromFormat('!d/m/Y', $dateRangeFrom);
            $query->where('V_V6_QUOTE.DELIVERY_DATE', '>=', $dateRangeFrom);
        }
        if ($dateRangeTo)
        {
            $dateRangeTo = Carbon::createFromFormat('!d/m/Y', $dateRangeTo);
            $query->where('V_V6_QUOTE.DELIVERY_DATE', '<=', $dateRangeTo);
        }

        return $query->paginate($perPage);
    }
    public function getStatusOptions($request)
    {  $status = V6Status::where('AREA_ID', 3)->get();   return $status; }
    public function getOrderDetailOnSearch($request, $userService)
    {
        $orderNo = $request->orderNo;
        $locationAbbr = $request->location;
        $query = $this->model->select(['V_V6_QUOTE.*', DB::raw('(V_V6_QUOTE.NETT_SELL_PRICE + V_V6_QUOTE.NETT_SELL_PRC_LAB) AS TOTALAMOUNT'), 'V_V6_CUSTOMER.CUST_NAME', 'V_V6_STATUS.DESCR'])
            ->join('V_V6_CUSTOMER', 'V_V6_CUSTOMER.CUST_ID', '=', 'V_V6_QUOTE.CUST_ID', 'left outer')
            ->join('V_V6_SALESPERSON', 'V_V6_SALESPERSON.USER_ID', '=', 'V_V6_QUOTE.SALES_PERSON', 'left outer')
            ->join('V_V6_CONTACT', 'V_V6_CONTACT.CONTACT_ID', '=', 'V_V6_QUOTE.SHIP_TO_CONTACT_ID', 'left outer')
            ->join('V_V6_ADDR', 'V_V6_ADDR.ADDR_ID', '=', 'V_V6_QUOTE.DEL_ADDR_ID', 'left outer')
            ->join('V_V6_STATUS', 'V_V6_STATUS.STATUS_ID', '=', 'V_V6_QUOTE.STATUS_ID', 'left outer')
            ->join('V_V6_CASH_REC', 'V_V6_CASH_REC.QUOTE_ID', '=', 'V_V6_QUOTE.QUOTE_ID', 'left outer')
            ->leftjoin('V_V6_FINCOL', function($join) {
                $join->on('V_V6_FINCOL.FINCOL_ID', '=', 'V_V6_QUOTE.FINCOL_ID');
                $join->on('V_V6_FINCOL.FINCOL_LIB_ID', '=', 'V_V6_QUOTE.FINCOL_LIB_ID');
            })
            //->join('V_AS_ODPORHD', 'V_AS_ODPORHD.HORDNO', '=', 'V_V6_QUOTE.UDF1') // extremely slow thus comment for now
            ->where('V_V6_STATUS.AREA_ID', 3)
            ->with('customer')
            ->with('sales_person')
            ->with('contact')
            ->with('deliver_address')
            ->with('cash_rec')
            ->with('activity_logs') // maybe not to get here if the performance is not good
            ->with('status')
            ->with('location');
        $query->where('V_V6_QUOTE.UDF1', $orderNo)->where('V_V6_QUOTE.QUOTE_NUM_PREF', $locationAbbr);
        $order = $query->first();
        return $order;
    }
}