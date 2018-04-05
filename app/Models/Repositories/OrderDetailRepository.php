<?php

namespace App\Models\Repositories;

use App\Models\Entities\Location;
use DB;
use Exception;

class OrderDetailRepository extends BaseRepository
{
    public function model() { return 'App\Models\Entities\V6Quote';  }

    public function getOrderDetails($request)
    {
        $orderNo = $request->orderNo;
        $quoteId = $request->quoteId;
        $location = $request->location;
        $detail = $this->model
            ->where('UDF1', $orderNo)
            ->where('QUOTE_NUM_PREF', $location)
            ->where('QUOTE_ID',$quoteId)->with('activity_logs')->with('booking_confirmation')->first();
        $vasOdporhd = DB::select("SET NOCOUNT ON; EXEC dbo.GET_AS400_OrderData ?, ?;", array($detail->QUOTE_NUM_PREF, $orderNo));
        $odporhd = array_shift($vasOdporhd);
        $color = $detail->color();
        return array( 'order' => $detail, 'odporhd' => $odporhd, 'color' => $color );
    }
    public function getAllAssociatedJobsByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $search = $request->filter;

        $query = $this->model->select(
            [
                'V_V6_QUOTE.QUOTE_ID',
                'V_V6_QUOTE.QUOTE_NUM',
                'V_V6_QUOTE.QUOTE_NUM_PREF',
                'V_V6_QUOTE.QUOTE_NUM_SUFF',
                'V_V6_QUOTE.UDF1',
                'V_V6_QUOTE.UDF5',
                'V_V6_QUOTE.DELIVERY_DATE',
                'booking_confirmation.agreed_date',
                DB::raw("CONCAT(V_V6_QUOTE.QUOTE_NUM, '.', V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT, '.', V_V6_QUOTE.QUOTE_NUM_SUFF COLLATE DATABASE_DEFAULT) as jobNo")
            ])
            //->join('booking_confirmation', DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'), 'left outer')
            ->leftjoin('booking_confirmation', function($join) 
            {   $join->on(DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'));
                $join->on('V_V6_QUOTE.QUOTE_ID', '=', 'booking_confirmation.QUOTE_ID');
            })
            ->whereRaw('V_V6_QUOTE.QUOTE_ID NOT IN 
                (SELECT associated_jobs.quote_id FROM associated_jobs 
                    WHERE associated_jobs.active = 1 and 
                        associated_jobs.order_quote_id = ? 
                        and associated_jobs.order_id = ? 
                        and associated_jobs.location_id = ? )',
                array($quoteId, $orderId, $locationId))
            ->where('V_V6_QUOTE.QUOTE_ID', '<>', $quoteId)
            ->where('V_V6_QUOTE.QUOTE_NUM_PREF', $locationAbbr)
            ->orderBy($sortBy, $sortDirection);

        if ($search) 
        {   $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) 
            {   $query->where('V_V6_QUOTE.UDF5', 'LIKE', $like);
                $query->orWhere('V_V6_QUOTE.QUOTE_NUM', 'LIKE', $like);
                $query->orWhere('V_V6_QUOTE.QUOTE_ID', 'LIKE', $like);
                $query->orWhere('V_V6_QUOTE.UDF1', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
}