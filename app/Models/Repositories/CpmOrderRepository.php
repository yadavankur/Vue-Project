<?php

namespace App\Models\Repositories;


use App\Models\Entities\BookingConfirmation;
use App\Models\Entities\CPM_Activity;
use App\Models\Entities\CPM_Master;
use App\Models\Entities\CPM_Order;
use App\Models\Entities\CPM_OrderMatrix;
use App\Models\Entities\CPM_Service;
use App\Models\Entities\CPMMethod;
use App\Models\Entities\Location;
use App\Models\Entities\V6Quote;
use Carbon\Carbon;
use Exception;
use DB;

class CpmOrderRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CPM_Order';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $orderId = $request->orderId;
        $serviceId = $request->serviceId;
        $search = $request->filter;

        $query = $this->model->distinct()->select([
            'cpm_orders.order_id',
            'cpm_orders.service_id',
            'cpm_orders.activity_id',
            'cpm_orders.start_date',
            'cpm_orders.end_date',
            'cpm_orders.duration',
            'cpm_orders.status_id',
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_orders.activity_id')
            ->where('cpm_orders.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_orders.order_id', $orderId)
            ->where('cpm_orders.service_id', $serviceId)
            ->with('activity')
            ->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_activities.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    //------------------------------------------------
    public function getSystemDeliveryDate($request)
    {
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $serviceId = $request->serviceId;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        // based on sequencing activities
        // calculate the critical path
        // then get the longest date needed to accomplish this order
        // 1) first check whether it is in the booking table
        // 2) second check whether it is in the cpm_order table if not create new
        $systemGeneratedDate = null;
        // check booking table
        $bookingInfo = BookingConfirmation::where('quote_id', $quoteId)
            ->where('location_id', $locationId)->where('order_id', $orderId)
            ->where('active', 1)->first();
        if (($bookingInfo instanceof BookingConfirmation) && ($bookingInfo->sys_generated_date))
        {
            $systemGeneratedDate = $bookingInfo->sys_generated_date;
        }
        else
        {
            // not in the booking table yet
            // 1) first check cpm_order_matrixes table
            $systemGeneratedDate = CPM_OrderMatrix::where('quote_id', $quoteId)
                ->where('location_id', $locationId)
                ->where('order_id', $orderId)
                ->where('service_id', $serviceId)
                ->where('is_needed', 1)
                ->where('active', 1)->max('end_date');
            if (is_null($systemGeneratedDate))
            {
                // 2) not imported yet thus create CPM
//                $baseStartDate = Carbon::today();
//                $orderServiceActivities = self::generateCPMActivities($baseStartDate, $quoteId, $locationId, $orderId, $serviceId);
//                if (count($orderServiceActivities) == 0)
//                {
//                    throw new Exception('Sorry, no CPM activities are found, please ask admin for help!');
//                }
//                // 3) try to get max end date again
//                $systemGeneratedDate = CPM_OrderMatrix::where('quote_id', $quoteId)
//                    ->where('location_id', $locationId)
//                    ->where('order_id', $orderId)
//                    ->where('service_id', $serviceId)
//                    ->where('is_needed', 1)
//                    ->where('active', 1)->max('end_date');
//                if (is_null($systemGeneratedDate))
//                {
//                    throw new Exception('Cannot get system generated date, please ask admin for help!');
//                }
                // get the first available earliest delivery date
                $systemGeneratedDate = DB::select("SET NOCOUNT ON; EXEC dbo.CMP_CalculateCriticalPath ?,?,?,?,?,?;", array($quoteId, 0, '', 1, '', $serviceId));
                $systemGeneratedDate = current(array_shift($systemGeneratedDate));
            }
            DB::transaction(function () use ($quoteId, $locationId, $orderId, $systemGeneratedDate) {
                $sysDate = Carbon::createFromFormat('Y-m-d H:i:s.u', $systemGeneratedDate);
                // update or create system generated date
                $bookingInfo = BookingConfirmation::updateOrCreate(
                    ['quote_id' => $quoteId, 'location_id' => $locationId, 'order_id' => $orderId, 'active' => 1],
                    ['sys_generated_date' => $systemGeneratedDate, 'active' => 1]
                );
                self::AddLog($quoteId, $locationId, $orderId, 'Updated system generated date', 'Booking',
                    '' . ' => ' .  $sysDate->format('d/m/Y') . ' in ' .  get_class($bookingInfo));
            });
        }
        return $systemGeneratedDate;
    }
    //-----------------------------------------------------------------
    public function getSystemGeneratedDateList($request)
    {
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        //$serviceId = $request->serviceId;
        $location = Location::where('abbreviation', $locationAbbr)->where('active', 1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        // flag: 0 -> get earliest available date
        // flag: 1 -> get the list of all needed activities
        // flag: 2 offset  ->  get the list of available dates
        $execFlag = 2;
        $offset = 5; // get available 5 dates
        $baseStartDate = '';
        $requiredDate = '';
        $service = CPM_Service::where('name', CPM_Service::MAIN_SERVICE)
            ->where('location_id', $locationId)->active()->first();
        $serviceId = $service->id;

        $systemGeneratedDates = DB::select("SET NOCOUNT ON; EXEC dbo.CMP_CalculateCriticalPath ?,?,?,?,?,?;",
            array($quoteId, $execFlag, $baseStartDate, $offset, $requiredDate, $serviceId));
        $dateList = [];
        foreach($systemGeneratedDates as $systemGeneratedDate)
        {
            $dateList[] = array('date' => current($systemGeneratedDate));
        }

        return $dateList;
    }
//----------------------------------------------------------------------------
    public function createOrderActivities($request)
    {
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $orderId = $request->orderId;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $baseStartDate = Carbon::today();
        return self::generateCPMActivities($baseStartDate, $quoteId, $locationId, $orderId);
    }
    public static function UpdatedActivityStatus($quoteId, $locationId, $orderId, $serviceTypeId, $activityTypeId, $status_id)
    {
        // 1) get order info and serviceId
        $order = V6Quote::where('QUOTE_ID', $quoteId)
            ->where('UDF1', $orderId)
            ->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
            ->join('cpm_services', 'cpm_services.location_id', '=', 'locations.id', 'left outer')
            ->where('locations.id', $locationId)
            ->where('locations.active', 1)
            ->where('cpm_services.active', 1)
            ->with('location')
            ->first();
        if (!$order instanceof V6Quote)
        {
            // throw exception
            throw new Exception('Invalid order!');
        }
        $services = $order->location->services;
        $serviceId = 0;
        foreach($services as $service)
        {
            if ($service->type_id == $serviceTypeId)
            {
                $serviceId = $service->id;
                break;
            }
        }
        // 2) get activity id
        $activity = CPM_Activity::where('service_id', $serviceId)
            ->where('type_id', $activityTypeId)
            ->where('active', 1)->first();
        if (!$activity instanceof CPM_Activity)
        {
            throw new Exception('Invalid activity!');
        }
        $activityId = $activity->id;

        // 3) get order activity and update status
        $cpmOrderActivity = CPM_Order::where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)
            ->where('service_id', $serviceId)
            ->where('activity_id', $activityId)
            ->where('active', 1)
            ->first();
        if (!$cpmOrderActivity instanceof CPM_Order)
        {
            throw new Exception('Invalid order activity!');
        }
        $cpmOrderActivity->status_id = $status_id;
        $cpmOrderActivity->end_date = Carbon::now();
        $cpmOrderActivity->save();
        self::LogEntity($cpmOrderActivity, 'update status ['.  $cpmOrderActivity->status_id . '] => ['. $status_id . '] of activity:['. $activityId . '], service: ['. $serviceId .'], order: [' . $quoteId . ':' . $locationId . ':' . $orderId .'] success', __CLASS__ . '::' .__FUNCTION__);
        return $cpmOrderActivity;
    }


    //-------------------------------------------------------------------------------
    public static function newCPMActivities($baseStartDate, $quoteId, $locationId, $orderId, $serviceId)
    {
        try {

            DB::beginTransaction();

            $order = V6Quote::where('QUOTE_ID', $quoteId)
                ->where('locations.id', $locationId)
                ->where('UDF1', $orderId)
                ->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
                ->join('cpm_services', 'cpm_services.location_id', '=', 'locations.id', 'left outer')
                ->with('location')
                ->first();
            if (!$order instanceof V6Quote)
            {
                // throw exception
                throw new Exception('Invalid order id!');
            }

            // get all master dependencies along with activity info
            $masterActivities = CPM_Master::with('activity')
                ->with('predecessor')
                ->with('successor')
                ->where('active', 1)
                ->where('service_id', $serviceId)
                ->orderBy('id','asc')
                ->get();
            // re-order activities according to dependencies
            $uniqueActivityList = CPMMethod::getUniqueActivities($masterActivities);
            $orderedList = CPMMethod::orderByDependencies($uniqueActivityList);

            if ($baseStartDate == null)
                $baseStartDate = Carbon::today();
            else
            {
                if (!$baseStartDate instanceof Carbon)
                    $baseStartDate = Carbon::createFromFormat('Y-m-d H:i:s.u', $baseStartDate);
            }

            $items = CPMMethod::calculateEstAndEet($order, $orderedList);
            $items = CPMMethod::calculateLstAndLet($order, $items);

            $maxVersionId = CpmOrderMatrixRepository::getMaxVersionId($quoteId, $locationId, $orderId, $serviceId);
            CpmOrderMatrixRepository::newActivityMatrixForOrder($quoteId, $locationId, $order, $serviceId, $maxVersionId, $items);
            $orderActivities = self::newServiceActivities($quoteId, $locationId, $order, $serviceId, $items, $maxVersionId);
            CpmOrderMatrixRepository::calculateDate($quoteId, $locationId, $orderId, $serviceId, $baseStartDate, $order->location->state->id);
            $orderServiceActivities[] = array(
                $serviceId => $orderActivities
            );

            DB::commit();
            return $orderServiceActivities;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public static function generateCPMActivities($baseStartDate, $quoteId, $locationId, $orderId, $serviceId = null)
    {
        try {
            //DB::select('SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
            DB::beginTransaction();
            // maybe need to get loaction based on order
            // then get all services from db
            $order = V6Quote::where('QUOTE_ID', $quoteId)
                ->where('locations.id', $locationId)
                ->where('UDF1', $orderId)
                ->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
                ->join('cpm_services', 'cpm_services.location_id', '=', 'locations.id', 'left outer')
                ->with('location')
                ->first();
            if (!$order instanceof V6Quote)
            {
                // throw exception
                throw new Exception('Invalid order id!');
            }
            $services = $order->location->services;
            $filteredServices = [];
            if ($serviceId)
            {
                foreach($services as $service)
                {
                    if ($serviceId == $service->id)
                    {
                        $filteredServices[] = $service;
                        break;
                    }
                }
            }
            else
                $filteredServices = $services;
            $orderServiceActivities = [];
            foreach($filteredServices as $service)
            {
                $maxVersionId = CpmOrderMatrixRepository::getMaxVersionId($quoteId, $locationId, $orderId, $service->id);
                $allNeededActivitiesForOrder = CpmOrderMatrixRepository::createActivityMatrixForOrder($quoteId, $locationId, $order, $service->id, $maxVersionId);
                $orderActivities = self::createServiceActivities($quoteId, $locationId, $order, $service->id, $allNeededActivitiesForOrder, $maxVersionId);
                // calculate the critical path and
                // fill out the start and end date
                //$baseStartDate = Carbon::today();
                CpmOrderMatrixRepository::calculateDate($quoteId, $locationId, $orderId, $service->id, $baseStartDate, $order->location->state->id);
                $orderServiceActivities[] = array(
                    $service->id => $orderActivities
                );
            }
            DB::commit();
            return $orderServiceActivities;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
//    public function getAllNeededActivities($order, $serviceId)
//    {
//        $activities = CPM_Activity::where('active', 1)
//            ->where('service_id', $serviceId)
//            ->get();
//        $orderActivities = [];
//        $isNeeded = true;
//        foreach($activities as $activity)
//        {
//            $isNeeded = true; // for the time being set default to true
//            $sqlStatement = trim($activity->sql_statement);
//            if ($sqlStatement)
//            {
//                // if there is sql statement then use it
//                // to judge whether the activity is needed or not
//                $isNeeded = $this->isNeeded($order, $sqlStatement);
//                $orderActivities[$activity->id] =$isNeeded;
//            }
//            else
//            {
//                $orderActivities[$activity->id] =$isNeeded;
//            }
//            CpmOrderMatrixRepository::createMaxtrixActivity($order, $activity, $isNeeded);
//        }
//        return $orderActivities;
//    }
    private static function createServiceActivities($quoteId, $locationId, $order, $serviceId, $allNeededActivities, $maxVersionId)
    {
        //$allNeededActivities = $this->getAllNeededActivities($order, $serviceId);
        // based on the master order cpm activity
        // create all needed activity for a particular order
        $masterActivities = CPM_Master::with('activity')
            ->with('predecessor')
            ->with('successor')
            ->where('active', 1)
            ->where('service_id', $serviceId)
            ->orderBy('id','asc')
            ->get();
        $orderActivities = [];

        // detach all first
        self::detachAllActivities($quoteId, $locationId, $order->UDF1, $serviceId);
        //$baseStartDate = Carbon::createFromFormat('!d/m/Y', Carbon::now());
        foreach($masterActivities as $masterActivity)
        {
            // check whether the activity
            // is needed or not
            // if the activity is a must
            // then input the select 1 AS ISNEEDED in the sql statement
            $activity = $masterActivity->activity;
            $predecessor = $masterActivity->predecessor;
            $successor = $masterActivity->successor;
            if (($allNeededActivities[$activity->id]))
            {
                if ( (!$predecessor && !$successor)
                    || (!$predecessor && $successor && $allNeededActivities[$successor->id])
                    || (!$successor && $predecessor && $allNeededActivities[$predecessor->id])
                    || ($predecessor && $successor && $allNeededActivities[$predecessor->id] && $allNeededActivities[$successor->id])
                    )
                {
                    // attach it to the order
                    $orderActivities[] = self::attachActivity($quoteId, $locationId, $order, $masterActivity, $activity, $maxVersionId);
                }
            }
        }
        return $orderActivities;
    }
    private static function attachActivity($quoteId, $locationId, $order, $masterActivity, $activity, $maxVersionId)
    {
        $cpmOrder = new CPM_Order();
        $cpmOrder->quote_id = $quoteId;
        $cpmOrder->location_id = $locationId;
        $cpmOrder->order_id = $order->UDF1;
        $cpmOrder->service_id = $activity->service_id;
        $cpmOrder->activity_id = $activity->id;
        // might be another method to get lead time
        $cpmOrder->duration = $activity->duration;
        $cpmOrder->predecessor_id = $masterActivity->predecessor_id;
        $cpmOrder->successor_id = $masterActivity->successor_id;
        $cpmOrder->version_id = $maxVersionId;
//        // assume the start date is today
//        $startDate = Carbon::now();
//        $cpmOrder->start_date = $startDate;
        $cpmOrder->status_id = CPM_OrderMatrix::STATUS_NEW;
        $cpmOrder->save();
        //self::LogEntity($cpmOrder, 'attach activity to order: [' . $quoteId . ':' . $locationId . ':' . $order->UDF1 .'] success', __CLASS__ . '::' .__FUNCTION__);
        return $cpmOrder;
    }
//    public function isNeeded($order, $sqlStatement)
//    {
//        $isNeed = false;
//
//        $pattern = "/({{)([^{}]*)(}})/";
//        $matches = null;
//        $keys = [];
//        $keyValues = [];
//        preg_match_all($pattern, $sqlStatement, $matches);
//        if (count($matches) > 0)
//        {
//            $fields = $matches[2];
//            foreach($fields as $field)
//            {
//                $field = trim($field);
//                if ($field)
//                    $keys[] = trim($field);
//                else
//                    throw new Exception('Invalid parameter found in sql statement');
//            }
//        }
//        foreach($keys as $key)
//        {
//            $keyValues[] = $order->{$key};
//        }
//        $sqlStatement = preg_replace($pattern, "?", $sqlStatement);
//        $rets = DB::select(DB::raw($sqlStatement), $keyValues);
//        if (count($rets) > 0)
//        {
//            $isNeed = intval($rets[0]->ISNEEDED)? true: false;
//        }
//        return $isNeed;
//    }
    private static function detachAllActivities($quoteId, $locationId, $orderId, $serviceId)
    {
        $detachedActivities = CPM_Order::where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)
            ->where('service_id', $serviceId)
            ->where('active', 1)->update(['active' => 0]);
        return $detachedActivities;
    }
//    public static function UpdatedActivityStatus($quoteId, $locationId, $orderId, $serviceTypeId, $activityTypeId, $status_id)
//    {
//        // 1) get order info and serviceId
//        $order = V6Quote::where('QUOTE_ID', $quoteId)
//            ->where('UDF1', $orderId)
//            ->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
//            ->join('cpm_services', 'cpm_services.location_id', '=', 'locations.id', 'left outer')
//            ->where('locations.id', $locationId)
//            ->where('locations.active', 1)
//            ->where('cpm_services.active', 1)
//            ->with('location')
//            ->first();
//        if (!$order instanceof V6Quote)
//        {
//            // throw exception
//            throw new Exception('Invalid order!');
//        }
//        $services = $order->location->services;
//        $serviceId = 0;
//        foreach($services as $service)
//        {
//            if ($service->type_id == $serviceTypeId)
//            {
//                $serviceId = $service->id;
//                break;
//            }
//        }
//        // 2) get activity id
//        $activity = CPM_Activity::where('service_id', $serviceId)
//            ->where('type_id', $activityTypeId)
//            ->where('active', 1)->first();
//        if (!$activity instanceof CPM_Activity)
//        {
//            throw new Exception('Invalid activity!');
//        }
//        $activityId = $activity->id;
//
//        // 3) get order activity and update status
//        $cpmOrderActivity = CPM_Order::where('quote_id', $quoteId)
//            ->where('location_id', $locationId)
//            ->where('order_id', $orderId)
//            ->where('service_id', $serviceId)
//            ->where('activity_id', $activityId)
//            ->where('active', 1)
//            ->first();
//        if (!$cpmOrderActivity instanceof CPM_Order)
//        {
//            throw new Exception('Invalid order activity!');
//        }
//        $cpmOrderActivity->status_id = $status_id;
//        $cpmOrderActivity->end_date = Carbon::now();
//        $cpmOrderActivity->save();
//        self::LogEntity($cpmOrderActivity, 'update status ['.  $cpmOrderActivity->status_id . '] => ['. $status_id . '] of activity:['. $activityId . '], service: ['. $serviceId .'], order: [' . $quoteId . ':' . $locationId . ':' . $orderId .'] success', __CLASS__ . '::' .__FUNCTION__);
//        return $cpmOrderActivity;
//    }
//

    private static function newServiceActivities($quoteId, $locationId, $order, $serviceId, $activities, $maxVersionId)
    {
        // create all needed activity for a particular order
        $masterActivities = CPM_Master::with('activity')
            ->with('predecessor')
            ->with('successor')
            ->where('active', 1)
            ->where('service_id', $serviceId)
            ->orderBy('id','asc')
            ->get();
        $orderActivities = [];
        // detach all first
        self::detachAllActivities($quoteId, $locationId, $order->UDF1, $serviceId);
        foreach($masterActivities as $masterActivity)
        {
            // check whether the activity
            // is needed or not
            $activity = $masterActivity->activity;
            $predecessor = $masterActivity->predecessor;
            $successor = $masterActivity->successor;
            if (($activities[$activity->id]->isNeeded))
            {
                if ( (!$predecessor && !$successor)
                    || (!$predecessor && $successor && $activities[$successor->id]->isNeeded)
                    || (!$successor && $predecessor && $activities[$predecessor->id]->isNeeded)
                    || ($predecessor && $successor && $activities[$predecessor->id]->isNeeded && $activities[$successor->id]->isNeeded)
                )
                {
                    // attach it to the order
                    $orderActivities[] = self::attachActivity($quoteId, $locationId, $order, $masterActivity, $activities[$activity->id], $maxVersionId);
                }
            }
        }
        return $orderActivities;
    }
}