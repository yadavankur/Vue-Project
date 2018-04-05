<?php

namespace App\Models\Repositories;

use App\Models\Entities\BookingConfirmation;
use App\Models\Entities\CPM_Activity;
use App\Models\Entities\CPM_Comment;
use App\Models\Entities\CPM_Order;
use App\Models\Entities\CPM_OrderMatrix;
use App\Models\Entities\CPM_Service;
use App\Models\Entities\CPMMethod;
use App\Models\Entities\Location;
use App\Models\Entities\Tab;
use App\Models\Entities\TaskMapping;
use App\Models\Entities\TmpCpmOrderMatrix;
use App\Models\Entities\User;
use App\Models\Services\BusinessCalendarHolidayService;
use App\Models\Utils\BusinessCalendar;
use Carbon\Carbon;
use Exception;
use DB;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Array_;

class CpmOrderMatrixRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CPM_OrderMatrix';
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
        $serviceGroupId = $request->serviceGroupId;
        $search = $request->filter;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $query = $this->model->select([
            'cpm_order_matrixes.*',
            'cpm_order_predecessor_matrix.predecessor_status_id'
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_order_matrixes.activity_id')
            ->join('cpm_order_predecessor_matrix', function($join) {
                $join->on('cpm_order_matrixes.quote_id', '=', 'cpm_order_predecessor_matrix.quote_id')
                    ->on('cpm_order_matrixes.location_id', '=', 'cpm_order_predecessor_matrix.location_id')
                    ->on('cpm_order_matrixes.order_id', '=', 'cpm_order_predecessor_matrix.order_id')
                    ->on('cpm_order_matrixes.service_id', '=', 'cpm_order_predecessor_matrix.service_id')
                    ->on('cpm_order_matrixes.activity_id', '=', 'cpm_order_predecessor_matrix.activity_id');
            })
            ->leftjoin('cpm_service_group_activities', function($join) {
                $join->on('cpm_service_group_activities.activity_id', '=', 'cpm_order_matrixes.activity_id')
                    ->where('cpm_service_group_activities.active', 1);
            })
            ->leftjoin('cpm_service_groups', function($join) {
                $join->on('cpm_service_groups.id', '=', 'cpm_service_group_activities.service_group_id')
                    ->where('cpm_service_groups.active', 1);
            })
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_order_matrixes.is_needed', 1)
            ->where('cpm_order_matrixes.quote_id', $quoteId)
            ->where('cpm_order_matrixes.location_id', $locationId)
            ->where('cpm_order_matrixes.order_id', $orderId)
            ->with('activity')
            ->with('service')
            ->with('location')
            ->lock('with(nolock)')
            ->orderBy($sortBy, $sortDirection);

        if ($serviceId)
        {
            $query->where('cpm_order_matrixes.service_id', $serviceId);
        }
        if ($serviceGroupId)
        {
            $query->where('cpm_service_group_activities.service_group_id', $serviceGroupId);
        }
        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_activities.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public static function detachAllActivities($quoteId, $locationId, $orderId, $serviceId)
    {
        $detachedActivities = CPM_OrderMatrix::where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)
            ->where('service_id', $serviceId)
            ->where('active', 1)->update(['active' => 0]);
        return $detachedActivities;
    }

    public static function newActivityMatrixForOrder($quoteId, $locationId, $order, $serviceId, $maxVersionId, $activities)
    {
        self::detachAllActivities($quoteId, $locationId, $order->UDF1, $serviceId);
        foreach($activities as $activity)
        {
            self::addActivityToMatrix($quoteId, $locationId, $order->UDF1, $activity, $activity->isNeeded, $maxVersionId);
        }
    }
    public static function createActivityMatrixForOrder($quoteId, $locationId, $order, $serviceId, $maxVersionId)
    {
        //$maxVersionId = self::getMaxVersionId($order->UDF1, $serviceId);
        self::detachAllActivities($quoteId, $locationId, $order->UDF1, $serviceId);
        $activities = CPM_Activity::where('active', 1)
            ->where('service_id', $serviceId)
            ->orderBy('id', 'asc')
            ->get();
        $orderActivities = [];
        foreach($activities as $activity)
        {
            $isNeeded = true; // for the time being set default to true
            $sqlStatement = trim($activity->sql_statement);
            if ($sqlStatement)
            {
                // if there is sql statement then use it
                // to judge whether the activity is needed or not
                $isNeeded = self::isNeeded($order, $sqlStatement);
                $orderActivities[$activity->id] =$isNeeded;
            }
            else
            {
                $orderActivities[$activity->id] =$isNeeded;
            }
            self::addActivityToMatrix($quoteId, $locationId, $order->UDF1, $activity, $isNeeded, $maxVersionId);
        }
        return $orderActivities;
    }
    public static function isNeeded($order, $sqlStatement)
    {
        $isNeed = false;

        $pattern = "/({{)([^{}]*)(}})/";
        $matches = null;
        $keys = [];
        $keyValues = [];
        preg_match_all($pattern, $sqlStatement, $matches);
        if (count($matches) > 0)
        {
            $fields = $matches[2];
            foreach($fields as $field)
            {
                $field = trim($field);
                if ($field)
                    $keys[] = trim($field);
                else
                    throw new Exception('Invalid parameter found in sql statement');
            }
        }
        foreach($keys as $key)
        {
            $keyValues[] = $order->{$key};
        }
        $sqlStatement = preg_replace($pattern, "?", $sqlStatement);
        $rets = DB::select(DB::raw($sqlStatement), $keyValues);
        if (count($rets) > 0)
        {
            $isNeed = intval($rets[0]->ISNEEDED)? true: false;
        }
        return $isNeed;
    }
    public static function addActivityToMatrix($quoteId, $locationId, $orderId, $activity, $isNeeded, $maxVersionId)
    {
        $cpmOrderActivity = new CPM_OrderMatrix();
        $cpmOrderActivity->quote_id = $quoteId;
        $cpmOrderActivity->location_id = $locationId;
        $cpmOrderActivity->order_id = $orderId;
        $cpmOrderActivity->service_id = $activity->service_id;
        $cpmOrderActivity->activity_id = $activity->id;
        // might be another method to get lead time
        $cpmOrderActivity->duration = $activity->duration;
        // need to re-confirm this
        $cpmOrderActivity->is_needed = $isNeeded;
        $cpmOrderActivity->status_id = CPM_OrderMatrix::STATUS_NEW;
        $cpmOrderActivity->version_id = $maxVersionId;
        $cpmOrderActivity->save();
        //self::LogEntity($cpmOrderActivity, 'attach activity: [' . $activity->id . '] to order: [' . $quoteId . ':' . $locationId . ':' . $order->UDF1 .'] success', __CLASS__ . '::' .__FUNCTION__);
        return $cpmOrderActivity;
    }
    public static function updateDeliveryDate($orderMatrixId, $orderId, $activityId, $deliveryDate, $statusId = '')
    {
        $cpmOrderMatrix = CPM_OrderMatrix::where('id', $orderMatrixId)
                                    ->where('active', 1)->first();
        if (!$cpmOrderMatrix instanceof CPM_OrderMatrix)
        {
            throw new Exception('Invalid order!');
        }
        $originalDeliveryDate = $cpmOrderMatrix->delivery_date;
        $originalStatus = CPM_OrderMatrix::STATUSES[$cpmOrderMatrix->status_id];
        $newStatus = CPM_OrderMatrix::STATUSES[$statusId];
        if (!$originalDeliveryDate)
            $originalDeliveryDate = '';
        else
            $originalDeliveryDate = $originalDeliveryDate->format('d/m/Y');
        $quoteId = $cpmOrderMatrix->quote_id;
        $locationId = $cpmOrderMatrix->location_id;
        self::LogEntity($cpmOrderMatrix, 'update delivery date [' . $originalDeliveryDate. '] => [' . $deliveryDate->format('d/m/Y') . ']' .
            ' status [' . $originalStatus . '] => [' . $newStatus . ']' .
            ' for activity: [' . $activityId . '] to order: [' . $quoteId . ':' . $locationId . ':'. $orderId .'] success',
            __CLASS__ . '::' .__FUNCTION__);
        $cpmOrderMatrix->delivery_date = $deliveryDate;
        // update the status
        $cpmOrderMatrix->status_id = $statusId;
        $cpmOrderMatrix->save();
        return $cpmOrderMatrix;
    }
    public function getAllActivitiesOfOrder($request)
    {
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
        $serviceId = $request->serviceId;
        $allActivities = $this->model->select([
            'cpm_order_matrixes.*'
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_order_matrixes.activity_id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_order_matrixes.is_needed', 1)
            ->where('cpm_order_matrixes.quote_id', $quoteId)
            ->where('cpm_order_matrixes.location_id', $locationId)
            ->where('cpm_order_matrixes.order_id', $orderId)
            ->where('cpm_order_matrixes.service_id', $serviceId)
            ->with('activity')
            ->orderBy('cpm_activities.id', 'asc')->get()->toArray();
        return $allActivities;
    }
    public function simulateCPM($request)
    {
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
        $orderId = $request->orderId;
        $serviceId = $request->serviceId;
        $orderActivities = $request->orderActivities;
        $baseStartDate = $request->baseStartDate;
        // create tmp data for recalculation
        $this->createTempDataForCPM($quoteId, $locationId, $orderId, $serviceId, $orderActivities);
        // TODO
        // need to tweak more
        $cpmInfo = null;
        // $cpmInfo = DB::select("SET NOCOUNT ON; EXEC dbo.CPM_Simulate ?,?,?;", array($quoteId, $serviceId, $baseStartDate));
        $this->deleteTempDataForCPM($quoteId, $locationId, $orderId, $serviceId);
        return $cpmInfo;
    }
    private function createTempDataForCPM($quoteId, $locationId, $orderId, $serviceId, $orderActivities)
    {
        $this->deleteTempDataForCPM($quoteId, $locationId, $orderId, $serviceId);
        $tmpActivities = [];
        $user = Auth::user();
        if ($user instanceof User)
            $userId = $user->id;
        else
            $userId = env('APP_SYSTEM_USER_ID');
        $currentDateTime = Carbon::now();
        foreach ($orderActivities as $orderActivity)
        {
            $tmpActivities[] = array(
                'quote_id' => $quoteId,
                'location_id' => $locationId,
                'order_id' => $orderId,
                'activity_id' => $orderActivity['id'],
                'service_id' => $serviceId,
//                'start_date' => $orderActivity['start_date'],
//                'end_date' => $orderActivity['end_date'],
                'duration' => doubleval($orderActivity['duration']),
                'active' => 1,
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime,
                'created_by' => $userId,
                'updated_by' => $userId
            );
        }
        TmpCpmOrderMatrix::insert($tmpActivities);
    }
    private function deleteTempDataForCPM($quoteId, $locationId, $orderId, $serviceId)
    {
        $results = TmpCpmOrderMatrix::where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)
            ->where('service_id', $serviceId)
            ->delete();
    }
    public function calculateCPM($request)
    {
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
        $orderId = $request->orderId;
        $serviceId = $request->serviceId;
        $orderActivities = $request->orderActivities;
        $baseStartDate = $request->baseStartDate;
        if ($baseStartDate)
            $baseStartDate = Carbon::createFromFormat('!d/m/Y', $baseStartDate)->format('Y-m-d H:i:s.u');
        if (count($orderActivities) == 0)
        {
            // get from cpm_order_matrixes
            $orderActivities = $this->getAllActivitiesOfOrder($request);
        }
        // get sequencing activities with predecessors and successors from cpm_orders
        $activities = CPM_Order::select([
            'cpm_orders.*'
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_orders.activity_id')
            ->where('cpm_orders.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_orders.order_id', $orderId)
            ->where('cpm_orders.quote_id', $quoteId)
            ->where('cpm_orders.location_id', $locationId)
            ->where('cpm_orders.service_id', $serviceId)
            ->with('activity')
            ->with('predecessor')
            ->with('successor')
            ->orderBy('cpm_orders.activity_id', 'asc')
            ->get();
        $service = CPM_Service::with('location')->where('id', $serviceId)->where('active', 1)->first();
        $cpmInfo = CPMMethod::calculateCPM($activities, $orderActivities, $service->location->state->id, $baseStartDate);
        return $cpmInfo;
    }
    public static function calculateDate($quoteId, $locationId, $orderId, $serviceId, $baseStartDate, $stateId)
    {
        // get sequencing activities with predecessors and successors from cpm_orders
        $activities = CPM_Order::select([
            'cpm_orders.*'
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_orders.activity_id')
            ->where('cpm_orders.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_orders.quote_id', $quoteId)
            ->where('cpm_orders.location_id', $locationId)
            ->where('cpm_orders.order_id', $orderId)
            ->where('cpm_orders.service_id', $serviceId)
            ->with('activity')
            ->with('predecessor')
            ->with('successor')
            ->orderBy('cpm_orders.activity_id', 'asc')
            ->get();

        $cpmInfo = CPMMethod::calculateCPM($activities, array(), $stateId, $baseStartDate);
        $freeWeekDays = array(BusinessCalendar::SATURDAY, BusinessCalendar::SUNDAY);
        $year = $baseStartDate->year;
        $publicHolidays = BusinessCalendarHolidayService::getPublicHolidays($stateId, $year);
        $businessCalendar = new BusinessCalendar($freeWeekDays, $publicHolidays);

        //$baseStartDate = Carbon::createFromFormat('!d/m/Y', Carbon::now());
        foreach($cpmInfo->allActivities as $index => $activity)
        {
            //$tmpDate = clone $baseStartDate;
            $businessCalendar->setStartDate($baseStartDate);
            $est = intval($activity->getEst());
            //$startDate = $tmpDate->addDays($est);
            $startDate = clone $businessCalendar->addBusinessDays($est)->getDate();
            //$duration = intval($activity->getDuration());
            //$tmpStartDate = clone $startDate;
            //$businessCalendar->setStartDate($startDate);
            //$endDate = $tmpStartDate->addDays($duration);
            //$endDate = clone $businessCalendar->addBusinessDays($duration)->getDate();
            $eet = intval($activity->getEet());
            $businessCalendar->setStartDate($baseStartDate);
            $endDate = clone $businessCalendar->addBusinessDays($eet)->getDate();
            $orderMatrix = CPM_OrderMatrix::where('cpm_order_matrixes.active', 1)
                ->where('cpm_order_matrixes.is_needed', 1)
                ->where('cpm_order_matrixes.quote_id', $quoteId)
                ->where('cpm_order_matrixes.location_id', $locationId)
                ->where('cpm_order_matrixes.order_id', $orderId)
                ->where('cpm_order_matrixes.service_id', $serviceId)
                ->where('cpm_order_matrixes.activity_id', $activity->getActivityId())->first();
            if ($orderMatrix instanceof CPM_OrderMatrix)
            {
                $orderMatrix->start_date = $startDate;
                $orderMatrix->end_date = $endDate;
                $orderMatrix->save();
            }
        }
        return $cpmInfo;
    }
//    public static function checkExisting($quoteId, $locationId, $orderId, $serviceId)
//    {
//        $matrixActivities = CPM_OrderMatrix::where('quote_id', $quoteId)
//            ->where('location_id', $locationId)->where('order_id', $orderId)
//            ->where('service_id', $serviceId)->where('active', 1)->get();
//        if ($matrixActivities->count() > 0)
//        {
//            // already in db
//            // which means that the order has been updated
//            // so need to regenerate and recalculate the critical path
//            // but what about those activities have already been completed?
//            // Marked as uncompleted? or Mark it as completed?
//            self::detachAllActivities($quoteId, $locationId, $orderId, $serviceId);
//            return true;
//        }
//        return false;
//    }
    public static function getMaxVersionId($quoteId, $locationId, $orderId, $serviceId)
    {
        $maxVersionId = CPM_OrderMatrix::select([DB::raw("MAX(IsNull(version_id, 0)+1) AS version_id")])
            ->where('order_id', $orderId)
            ->where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('service_id', $serviceId)->where('active', 1)->first();
        return $maxVersionId->version_id;
    }

    public function updateCPM($request)
    {
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
        $orderId = $request->orderId;
        $serviceId = $request->serviceId;

//        // re-calculate the CPM
//        $cpmInfo = $this->calculateCPM($request);
//
//        // update duration and dates
//        self::updateDurationDates($quoteId, $locationId, $orderId, $serviceId, $cpmInfo->allActivities);
//
//        return $cpmInfo;
        // create temp records

        // call stored procedure to update the CPM

    }
    private static function updateDurationDates($quoteId, $locationId, $orderId, $serviceId, $activities)
    {
        try {
            DB::beginTransaction();
            foreach($activities as $activity)
            {
                // find record in cpm_order_matrix
                // update duration and dates
                $orderMatrix = CPM_OrderMatrix::where('cpm_order_matrixes.active', 1)
                    ->where('cpm_order_matrixes.is_needed', 1)
                    ->where('cpm_order_matrixes.quote_id', $quoteId)
                    ->where('cpm_order_matrixes.location_id', $locationId)
                    ->where('cpm_order_matrixes.order_id', $orderId)
                    ->where('cpm_order_matrixes.activity_id', $activity->activity_id)
                    ->where('cpm_order_matrixes.service_id', $serviceId)->first();
                if ($orderMatrix instanceof CPM_OrderMatrix)
                {
                    $originalDuration = $orderMatrix->duration;
                    $originalStartDate = $orderMatrix->start_date;
                    $originalEndDate = $orderMatrix->end_date;
                    $newStartDate = Carbon::createFromFormat('Y-m-d H:i:s.u', $activity->start_date); //$activity->start_date;
                    $newEndDate = Carbon::createFromFormat('Y-m-d H:i:s.u', $activity->end_date); //$activity->end_date;
                    $newDuration = $activity->duration;
                    $orderMatrix->duration = $newDuration;
                    $orderMatrix->start_date = $newStartDate;
                    $orderMatrix->end_date = $newEndDate;
                    $orderMatrix->save();
                    $comment = "changed duration from [$originalDuration ] => [$newDuration], 
                                start date from [" . ($originalStartDate? $originalStartDate->format('d/m/Y'): '') . "] => [" . $newStartDate->format('d/m/Y') . "]," .
                                "end date from [" . ($originalEndDate? $originalEndDate->format('d/m/Y'): '') . "] => [" . $newEndDate->format('d/m/Y') ."]";

                    self::LogEntity($orderMatrix, $comment,  __CLASS__ . '::' .__FUNCTION__);
                    $notes = new CPM_Comment();
                    $notes->comment = $comment;
                    $notes->quote_id = $quoteId;
                    $notes->location_id = $locationId;
                    $notes->order_id = $orderId;
                    $notes->activity_id = $activity->activity_id;
                    $notes->cpm_order_id = $orderMatrix->id;
                    $notes->type = CPM_Comment::TYPE_DOWELL;
                    $notes->save();
                }
            }
            DB::commit();
        }
        catch (Exception $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
    public function createCPM($request)
    {
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
        $orderId = $request->orderId;
        $serviceId = $request->serviceId;
        $baseStartDate = $request->baseStartDate;
        if ($baseStartDate)
            $baseStartDate = Carbon::createFromFormat('!d/m/Y', $baseStartDate);
        else
            $baseStartDate = Carbon::today();
        $orderServiceActivities = CpmOrderRepository::generateCPMActivities($baseStartDate, $quoteId, $locationId, $orderId, $serviceId);
        if (count($orderServiceActivities) == 0)
        {
            throw new Exception('Sorry, no CPM activities are found, please ask admin for help!');
        }
        return $this->calculateCPM($request);
    }
//    public static function updateActivityInfo($quoteId, $locationId, $orderId, $serviceId, $activity)
//    {
//        $cpmActivity = CPM_OrderMatrix::where('quote_id', $quoteId)
//            ->where('location_id', $locationId)
//            ->where('order_id', $orderId)
//            ->where('service_id', $serviceId)
//            ->where('activity_id', $activity->getActivityId())
//            ->where('active', 1)->first();
//        if (!$cpmActivity instanceof CPM_OrderMatrix)
//        {
//            throw new Exception('Invalid order!');
//        }
//        $cpmActivity->duration = $activity->getDuration();
//        $cpmActivity->start_date = $activity->getStartDate();
//        $cpmActivity->end_date = $activity->getEndDate();
//        $cpmActivity->save();
//    }
    public function addAdhocActivity($request)
    {
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
        $orderId = $request->orderId;
        $serviceId = $request->serviceId;
        $activityId = $request->activityId;
        $position = $request->position;
        $adhocName = $request->adhocName;
        $duration = doubleval($request->duration);
        $sqlStatement = $request->sqlStatement;
        $comment = $request->comment;
        $orderMatrixId = $request->orderMatrixId;

        $orderMatrix = CPM_OrderMatrix::where('id', $orderMatrixId)->Active()->first();
        if (!$orderMatrix instanceof CPM_OrderMatrix)
        {
            throw new Exception('Invalid activity!');
        }

        try {
            DB::beginTransaction();

            // 1) add ad hoc to cpm_activities
            $adhocActivity = new CPM_Activity();
            $adhocActivity->name = $adhocName;
            $adhocActivity->comment = $comment;
            $adhocActivity->duration = $duration;
            $adhocActivity->sql_statement = $sqlStatement;
            $adhocActivity->service_id = $serviceId;
            $adhocActivity->save();
            $adhocActivityId = $adhocActivity->id;
            self::AddLog($quoteId, $locationId, $orderId, "Added ad hoc activity:[$adhocName]",
                'Customer Service',
                "Added a new ad hoc activity:[ $adhocName ], serviceId:[$serviceId]");
            // 2) add ad hoc to cpm_order_matrixes
            $isNeeded = true;
            $maxVersionId = $orderMatrix->version_id;
            self::addActivityToMatrix($quoteId, $locationId, $orderId, $adhocActivity, $isNeeded, $maxVersionId);
            // 3) based on position to delete old dependencies in cpm_orders

            //$userId = env('APP_SYSTEM_USER_ID');
            $user = Auth::user();
            if ($user instanceof User)
                $userId = $user->id;
            else
                $userId = env('APP_SYSTEM_USER_ID');
            $currentDateTime = Carbon::now();
            $newActivityDependencies = [];
            if ($position == 'after')
            {
                // get all data based on activity_id = $activityId
                // and get all predecessors of current activity
                $activityQuery = CPM_Order::where('quote_id', $quoteId)
                    ->where('location_id', $locationId)
                    ->where('order_id', $orderId)
                    ->where('service_id', $serviceId)
                    ->where('activity_id', $activityId)
                    ->Active();
                $predecessorIds = $activityQuery->get()->pluck('predecessor_id')->unique()->all();
                $successorIds = $activityQuery->get()->pluck('successor_id')->unique()->all();
                $deletedCount = $activityQuery->update(['active' => 0]);

                foreach($predecessorIds as $predecessorId)
                {
                    $newActivityDependencies[] = array(
                        'quote_id' => $quoteId,
                        'location_id' => $locationId,
                        'order_id' => $orderId,
                        'activity_id' => $activityId,
                        'service_id' => $serviceId,
                        'duration' => doubleval($orderMatrix->duration),
                        'status_id' => 1, // in fact not used
                        'predecessor_id' => $predecessorId,
                        'successor_id' => $adhocActivityId,
                        'version_id' => $maxVersionId,
                        'active' => 1,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }

                // 2) insert new $adhocActivityId to set its predecessor to current $activityId and
                //    set its successors to original successors of current activity
                // insert new ad hoc activity dependencies
                foreach($successorIds as $successorId)
                {
                    $newActivityDependencies[] = array(
                        'quote_id' => $quoteId,
                        'location_id' => $locationId,
                        'order_id' => $orderId,
                        'activity_id' => $adhocActivityId,
                        'service_id' => $serviceId,
                        'duration' => doubleval($adhocActivity->duration),
                        'status_id' => 1, // in fact not used
                        'predecessor_id' => $activityId,
                        'successor_id' => $successorId,
                        'version_id' => $maxVersionId,
                        'active' => 1,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }
                // 3)get all data based on predecessor_id = $activityId and delete them
                // modify original successors of current activity to set their predecessor to $adhocActivityId
                // insert after current activity
                $predecessorQuery = CPM_Order::where('quote_id', $quoteId)
                    ->where('location_id', $locationId)
                    ->where('order_id', $orderId)
                    ->where('service_id', $serviceId)
                    ->where('predecessor_id', $activityId)
                    ->Active();
                $items = $predecessorQuery->get();
                $deletedPredecessorCount = $predecessorQuery->update(['active' => 0]);
                foreach($items as $item)
                {
                    $newActivityDependencies[] = array(
                        'quote_id' => $quoteId,
                        'location_id' => $locationId,
                        'order_id' => $orderId,
                        'activity_id' => $item->activity_id,
                        'service_id' => $serviceId,
                        'duration' => doubleval($item->duration),
                        'status_id' => 1,
                        'predecessor_id' => $adhocActivityId,
                        'successor_id' => $item->successor_id,
                        'version_id' => $item->version_id,
                        'active' => 1,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }
            }
            else
            {
                // 1) get all its predecessors and delete them
                // then insert new ones by updating their successor_id to $adhocActivityId
                $predecessorQuery = CPM_Order::where('quote_id', $quoteId)
                    ->where('location_id', $locationId)
                    ->where('order_id', $orderId)
                    ->where('service_id', $serviceId)
                    ->where('successor_id', $activityId)
                    ->Active();
                $items = $predecessorQuery->get();
                $deletedPredecessorCount = $predecessorQuery->update(['active' => 0]);
                foreach($items as $item)
                {
                    $newActivityDependencies[] = array(
                        'quote_id' => $quoteId,
                        'location_id' => $locationId,
                        'order_id' => $orderId,
                        'activity_id' => $item->activity_id,
                        'service_id' => $serviceId,
                        'duration' => doubleval($item->duration),
                        'status_id' => 1,
                        'predecessor_id' => $item->predecessor_id,
                        'successor_id' => $adhocActivityId,
                        'version_id' => $item->version_id,
                        'active' => 1,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }

                // find current activity
                $activityQuery = CPM_Order::where('quote_id', $quoteId)
                    ->where('location_id', $locationId)
                    ->where('order_id', $orderId)
                    ->where('service_id', $serviceId)
                    ->where('activity_id', $activityId)
                    ->Active();
                $predecessorIds = $activityQuery->get()->pluck('predecessor_id')->unique()->all();
                $successorIds = $activityQuery->get()->pluck('successor_id')->unique()->all();
                $deletedCount = $activityQuery->update(['active' => 0]);

                // insert new $adhocActivityId to set its predecessor to original predecessor of current activity
                // and successor to current $activityId
                foreach($predecessorIds as $predecessorId)
                {
                    $newActivityDependencies[] = array(
                        'quote_id' => $quoteId,
                        'location_id' => $locationId,
                        'order_id' => $orderId,
                        'activity_id' => $adhocActivityId,
                        'service_id' => $serviceId,
                        'duration' => doubleval($adhocActivity->duration),
                        'status_id' => 1, // in fact not used
                        'predecessor_id' => $predecessorId,
                        'successor_id' => $activityId,
                        'version_id' => $maxVersionId,
                        'active' => 1,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }

                // insert current activity by updating its new predecessors
                foreach($successorIds as $successorId)
                {
                    $newActivityDependencies[] = array(
                        'quote_id' => $quoteId,
                        'location_id' => $locationId,
                        'order_id' => $orderId,
                        'activity_id' => $activityId,
                        'service_id' => $serviceId,
                        'duration' => doubleval($orderMatrix->duration),
                        'status_id' => 1,
                        'predecessor_id' => $adhocActivityId,
                        'successor_id' => $successorId,
                        'version_id' => $orderMatrix->version_id,
                        'active' => 1,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }
            }
            // 4) add new dependencies to cpm_orders
            CPM_Order::insert($newActivityDependencies);
            DB::commit();
            return $newActivityDependencies;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }

    public function getAllTasks($request)
    {
        $type = $request->type;
        if ($type == 'OWN')
        {
            return $this->getOwnTasks($request);
        }
        else
        {
            return $this->getGroupTasks($request);
        }
    }

    public function getOwnTasks($request)
    {
//        $rawSql = 'select cpm_order_matrixes.id, cpm_order_matrixes.order_id, cpm_activities.name, cpm_order_matrixes.status_id, cpm_activity_users.user_id
//        from
//        cpm_order_matrixes
//        inner join cpm_activities on cpm_order_matrixes.activity_id = cpm_activities.id
//        inner join cpm_activity_users on cpm_activities.id = cpm_activity_users.cpm_activity_id
//        where cpm_order_matrixes.status_id in (2,3) and cpm_activity_users.owner_type = 'USER'
//        and cpm_order_matrixes.active=1 and cpm_activities.active=1 and cpm_activity_users.active=1';
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $filter = $request->filter;
        if (is_array($filter))
        {
            $search = $filter['filterText'];
            $status = $filter['status'];
        }
        else
        {
            $search = $filter;
            $status = '';
        }

        $user = Auth::user();
        $query = $this->model->select([
            'cpm_order_matrixes.*',
            'cpm_activities.name',
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=', 'cpm_order_matrixes.activity_id')
            ->join('cpm_activity_users', 'cpm_activity_users.cpm_activity_id', '=', 'cpm_activities.id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_activity_users.active', 1)
            ->where('cpm_activity_users.owner_type', 'USER')
            ->where('cpm_activity_users.user_id', $user->id)
            ->with('location')
            ->with('order')
            ->orderBy($sortBy, $sortDirection);

        $dueType = $request->dueType;
        switch($dueType)
        {
            case 'OVERDUE':
                $query->wherein('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_DELAY,CPM_OrderMatrix::STATUS_DEFERRAL]);
                break;
            case 'DUETODAY':
                $today = Carbon::today();
                $query->where(function ($query) use($today) {
                    $query->where(function ($query) use($today) {
                        $query->whereNotNull('cpm_order_matrixes.delivery_date');
                        $query->where(function ($query) use($today) {
                            $query->where('cpm_order_matrixes.delivery_date', $today);
                        });
                    });
                    $query->orWhere(function ($query) use($today) {
                        $query->orWhereNull('cpm_order_matrixes.delivery_date');
                        $query->where(function ($query) use($today) {
                            $query->where('cpm_order_matrixes.end_date', $today);
                        });
                    });
                });
                break;
            case 'DUETOMORROW':
                $tomorrow = Carbon::tomorrow();
                $query->where(function ($query) use($tomorrow) {
                    $query->where(function ($query) use($tomorrow) {
                        $query->whereNotNull('cpm_order_matrixes.delivery_date');
                        $query->where(function ($query) use($tomorrow) {
                            $query->where('cpm_order_matrixes.delivery_date', $tomorrow);
                        });
                    });
                    $query->orWhere(function ($query) use($tomorrow) {
                        $query->orWhereNull('cpm_order_matrixes.delivery_date');
                        $query->where(function ($query) use($tomorrow) {
                            $query->where('cpm_order_matrixes.end_date', $tomorrow);
                        });
                    });
                });
                break;
        }
        if ($status) {
            $query->where('cpm_order_matrixes.status_id',$status);
        }
        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_order_matrixes.order_id', 'LIKE', $like)
                    ->orwhere('cpm_activities.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function getGroupTasks($request)
    {
//        select cpm_order_matrixes.id, cpm_order_matrixes.order_id, cpm_activities.name, cpm_order_matrixes.status_id, user_group.user_id
//        from
//        cpm_order_matrixes
//        inner join cpm_activities on cpm_order_matrixes.activity_id = cpm_activities.id
//        inner join cpm_activity_groups on cpm_activities.id = cpm_activity_groups.cpm_activity_id
//        inner join user_group on user_group.group_id = cpm_activity_groups.group_id
//        where cpm_order_matrixes.status_id in (2,3) and cpm_activity_groups.owner_type = 'USER'
//        and cpm_order_matrixes.active=1 and cpm_activities.active=1 and cpm_activity_groups.active=1
//        and user_group.active=1
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $search = $request->filter;

        $user = Auth::user();
        $query = $this->model->select([
            'cpm_order_matrixes.*',
            'cpm_activities.name',
        ])
            ->join('cpm_activities', 'cpm_activities.id', '=', 'cpm_order_matrixes.activity_id')
            ->join('cpm_activity_groups', 'cpm_activity_groups.cpm_activity_id', '=', 'cpm_activities.id')
            ->join('user_group', 'user_group.group_id', '=', 'cpm_activity_groups.group_id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('user_group.active', 1)
            ->where('cpm_activity_groups.active', 1)
            ->where('cpm_activity_groups.owner_type', 'USER')
            ->where('user_group.user_id', $user->id)
            ->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_order_matrixes.order_id', 'LIKE', $like)
                    ->orwhere('cpm_activities.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

    public static function getOverDueTaskCount()
    {
        $user = Auth::user();
        $query = CPM_OrderMatrix::select()
            ->join('cpm_activities', 'cpm_activities.id', '=', 'cpm_order_matrixes.activity_id')
            ->join('cpm_activity_users', 'cpm_activity_users.cpm_activity_id', '=', 'cpm_activities.id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_activity_users.active', 1)
            ->where('cpm_activity_users.owner_type', 'USER')
            ->where('cpm_activity_users.user_id', $user->id)
            ->wherein('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_DELAY,CPM_OrderMatrix::STATUS_DEFERRAL]);
        $count = $query->count();
        return $count;
    }
    public static function getTaskOverviewBarChart()
    {
        // get the latest three month
        // task type => Completed, Overdue, InProgress, Waiting
        $user = Auth::user();
        $query = CPM_OrderMatrix::select()
            ->join('cpm_activities', 'cpm_activities.id', '=', 'cpm_order_matrixes.activity_id')
            ->join('cpm_activity_users', 'cpm_activity_users.cpm_activity_id', '=', 'cpm_activities.id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_activity_users.active', 1)
            ->where('cpm_activity_users.owner_type', 'USER')
            ->where('cpm_activity_users.user_id', $user->id);

        $today = Carbon::today();
        $firstMonthFrom = $today->copy()->subMonth(2)->startOfMonth();
        $firstMonthEnd = $today->copy()->subMonth(2)->lastOfMonth();
        $firstMonthTaskInfo = self::getchartData(clone $query, $firstMonthFrom, $firstMonthEnd);

        $secondMonthFrom = $today->copy()->subMonth(1)->startOfMonth();
        $secondMonthEnd = $today->copy()->subMonth(1)->lastOfMonth();
        $secondMonthTaskInfo = self::getchartData(clone $query, $secondMonthFrom, $secondMonthEnd);

        $currentMonthFrom = $today->copy()->startOfMonth();
        $currentMonthEnd = $today->copy()->lastOfMonth();
        $currentMonthTaskInfo = self::getchartData(clone $query, $currentMonthFrom, $currentMonthEnd);

        $labels = array(
            $firstMonthFrom->format('M'),
            $secondMonthFrom->format('M'),
            $currentMonthFrom->format('M'),
        );

        $completedTasks = array(
            $firstMonthTaskInfo['completedTasks'],
            $secondMonthTaskInfo['completedTasks'],
            $currentMonthTaskInfo['completedTasks'],
        );
        $overDueTasks = array(
            $firstMonthTaskInfo['overDueTasks'],
            $secondMonthTaskInfo['overDueTasks'],
            $currentMonthTaskInfo['overDueTasks'],
        );
        $inProgressTasks = array(
            $firstMonthTaskInfo['inProgressTasks'],
            $secondMonthTaskInfo['inProgressTasks'],
            $currentMonthTaskInfo['inProgressTasks'],
        );
        $waitingTasks = array(
            $firstMonthTaskInfo['waitingTasks'],
            $secondMonthTaskInfo['waitingTasks'],
            $currentMonthTaskInfo['waitingTasks'],
        );
        return array(
            'labels' => $labels,
            'completedTasks' => $completedTasks,
            'overDueTasks' => $overDueTasks,
            'inProgressTasks' => $inProgressTasks,
            'waitingTasks' => $waitingTasks,
        );
    }
    public static function getTotalTaskByType($query, $type)
    {
        if ($type == 'Completed')
        {
            $query->wherein('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_COMPLETED]);
        }
        else if ($type == 'Overdue')
        {
            $query->wherein('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_DELAY, CPM_OrderMatrix::STATUS_DEFERRAL]);
        }
        else if ($type == 'InProgress')
        {
            $query->wherein('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_NEW, CPM_OrderMatrix::STATUS_ON_TIME]);
            $today = Carbon::today();
            $query->where(function ($query) use($today) {
                $query->where(function ($query) use($today) {
                    $query->whereNotNull('cpm_order_matrixes.delivery_date');
                    $query->where(function ($query) use($today) {
                        $query->where('cpm_order_matrixes.delivery_date', '>=', $today)
                            ->where('cpm_order_matrixes.delivery_date', '<=', $today);
                    });
                });
                $query->orWhere(function ($query) use($today) {
                    $query->orWhereNull('cpm_order_matrixes.delivery_date');
                    $query->where(function ($query) use($today) {
                        $query->where('cpm_order_matrixes.start_date', '<=', $today)
                            ->where('cpm_order_matrixes.end_date', '>=', $today);
                    });
                });
            });
        }
        else if ($type == 'Waiting')
        {
            $query->wherein('cpm_order_matrixes.status_id', [CPM_OrderMatrix::STATUS_NEW, CPM_OrderMatrix::STATUS_ON_TIME]);
            // TODO
            // need to add the following when offical release it
            // $today = Carbon::today();
            // $query->where('cpm_order_matrixes.start_date', '>', $today);;
        }
        $count = $query->count();
        return $count;
    }
    public static function setQueryPeriod($query, $dateFrom, $dateEnd)
    {
        $query->where(function ($query) use($dateFrom, $dateEnd) {
            $query->where(function ($query) use($dateFrom, $dateEnd) {
                $query->whereNotNull('cpm_order_matrixes.delivery_date');
                $query->where(function ($query) use($dateFrom, $dateEnd) {
                    $query->where('cpm_order_matrixes.delivery_date', '<=', $dateEnd)
                        ->where('cpm_order_matrixes.delivery_date', '>=', $dateFrom);
                });
            });
            $query->orWhere(function ($query) use($dateFrom, $dateEnd) {
                $query->orWhereNull('cpm_order_matrixes.delivery_date');
                $query->where(function ($query) use($dateFrom, $dateEnd) {
                    $query->where('cpm_order_matrixes.start_date', '>=', $dateFrom)
                        ->where('cpm_order_matrixes.end_date', '<=', $dateEnd);
                });
            });
        });
        return $query;
    }
    public static function getchartData($query, $dateFrom, $dateEnd)
    {
        $query = self::setQueryPeriod($query, $dateFrom, $dateEnd);
        $totalTasks = $query->count();
        $completedTasks = self::getTotalTaskByType(clone $query, 'Completed');
        $overDueTasks = self::getTotalTaskByType(clone $query, 'Overdue');
        $inProgressTasks = self::getTotalTaskByType(clone $query, 'InProgress');
        $waitingTasks = self::getTotalTaskByType(clone $query, 'Waiting');

        return array(
            'month' => $dateFrom->format('M'),
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'overDueTasks' => $overDueTasks,
            'inProgressTasks' => $inProgressTasks,
            'waitingTasks' => $waitingTasks,
        );
    }
    public static function getTaskOverviewDoughnutChart()
    {
        // get data info of current month
        $user = Auth::user();
        $query = CPM_OrderMatrix::select()
            ->join('cpm_activities', 'cpm_activities.id', '=', 'cpm_order_matrixes.activity_id')
            ->join('cpm_activity_users', 'cpm_activity_users.cpm_activity_id', '=', 'cpm_activities.id')
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_activity_users.active', 1)
            ->where('cpm_activity_users.owner_type', 'USER')
            ->where('cpm_activity_users.user_id', $user->id);

        $today = Carbon::today();
        //$currentMonthFrom = $today->copy()->startOfMonth();
        //$firstMonthFrom = $today->copy()->subMonth(2)->startOfMonth();
        $firstMonthFrom = $today->copy()->startOfMonth();
        $currentMonthEnd = $today->copy()->lastOfMonth();
        $currentMonthTaskInfo = self::getchartData($query, $firstMonthFrom, $currentMonthEnd);
        return array(
            'taskData' => [
                $currentMonthTaskInfo['completedTasks'],
                $currentMonthTaskInfo['overDueTasks'],
                $currentMonthTaskInfo['inProgressTasks'],
                $currentMonthTaskInfo['waitingTasks'],
            ]
        );
    }
    /**
     * there are three special tasks at the moment
     * 1) booking
     * 2) confirmation
     * 3) full payment check
     * 1) 2) are done on booking and confirmation screen
     * @param $quoteId
     * @param $locationId
     * @param $orderId
     * @param $activityId
     * @param int $statusId
     */
    public static function updateSpecialTaskStatus($quoteId, $locationId, $orderId, $activityId, $statusId = CPM_OrderMatrix::STATUS_COMPLETED)
    {
        // check whether activity id is in task_mappings
        // if yes then update special task
        $taskMapping = TaskMappingRepository::getMappedTaskByActivity($activityId, $locationId);
        if (!$taskMapping instanceof TaskMapping)
        {
            return;
        }
        $taskName = $taskMapping->task_name;
        switch ($taskName) {
            case TaskMapping::TASK_FULL_PAYMENT_CONFIRMATION:
                $bookingConfirmation = BookingConfirmation::where('quote_id', $quoteId)
                    ->where('order_id', $orderId)->where('location_id', $locationId)->active()->first();
                if ($bookingConfirmation instanceof BookingConfirmation)
                {
                    if ($statusId == CPM_OrderMatrix::STATUS_COMPLETED)
                    {
                        $bookingConfirmation->fully_paid = true;
                        $bookingConfirmation->save();
                    }
                    else
                    {
                        $bookingConfirmation->fully_paid = false;
                        $bookingConfirmation->save();
                    }
                    self::AddLog($quoteId, $locationId, $orderId, $taskName, "$taskName task completed",
                        "$taskName task has been completed.");
                }
                break;
        }
    }
    public static function updateTaskStatus($quoteId, $locationId, $orderId, $taskName, $statusId = CPM_OrderMatrix::STATUS_COMPLETED)
    {
        // check whether the task has been mapped
        $taskMapping = TaskMappingRepository::getMappedTask($taskName, $locationId);
        if (!$taskMapping instanceof TaskMapping)
        {
            return;
        }
        $activityId = $taskMapping->activity_id;
        $activity = CPM_OrderMatrix::where('order_id', $orderId)
            ->where('quote_id', $quoteId)
            ->where('location_id', $locationId)
            ->where('activity_id', $activityId)
            ->active()
            ->first();
        if ($activity instanceof CPM_OrderMatrix)
        {
            $activity->status_id = $statusId;
            $activity->delivery_date = Carbon::today();
            $activity->save();
            self::AddLog($quoteId, $locationId, $orderId, $taskName, "$taskName task completed",
                "$taskName task has been completed.");
        }
    }
    public function getAssociatedTasksByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $tabLink = $request->tab;
        $tab = Tab::where('link', $tabLink)->where('active' ,1)->first();
        if (!$tab instanceof Tab)
            throw new Exception('Invalid Tab!');
        $tabId = $tab->id;

        $activityId = $request->activityId;

        $perPage = $request->per_page;
        $orderId = $request->orderId;
        $search = $request->filter;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $query = $this->model->select([
            'cpm_order_matrixes.*',
            'cpm_order_predecessor_matrix.predecessor_status_id'
        ])
            ->join('screen_activity_mappings', 'screen_activity_mappings.activity_id', '=',  'cpm_order_matrixes.activity_id', 'left outer')
            ->join('cpm_activities', 'cpm_activities.id', '=',  'cpm_order_matrixes.activity_id')
            ->join('cpm_order_predecessor_matrix', function($join) {
                $join->on('cpm_order_matrixes.quote_id', '=', 'cpm_order_predecessor_matrix.quote_id')
                    ->on('cpm_order_matrixes.location_id', '=', 'cpm_order_predecessor_matrix.location_id')
                    ->on('cpm_order_matrixes.order_id', '=', 'cpm_order_predecessor_matrix.order_id')
                    ->on('cpm_order_matrixes.service_id', '=', 'cpm_order_predecessor_matrix.service_id')
                    ->on('cpm_order_matrixes.activity_id', '=', 'cpm_order_predecessor_matrix.activity_id');
            })
            ->leftjoin('cpm_service_group_activities', function($join) {
                $join->on('cpm_service_group_activities.activity_id', '=', 'cpm_order_matrixes.activity_id')
                    ->where('cpm_service_group_activities.active', 1);
            })
            ->leftjoin('cpm_service_groups', function($join) {
                $join->on('cpm_service_groups.id', '=', 'cpm_service_group_activities.service_group_id')
                    ->where('cpm_service_groups.active', 1);
            })
            ->where('cpm_order_matrixes.active', 1)
            ->where('cpm_activities.active', 1)
            ->where('cpm_order_matrixes.is_needed', 1)
            ->where('cpm_order_matrixes.quote_id', $quoteId)
            ->where('cpm_order_matrixes.location_id', $locationId)
            ->where('cpm_order_matrixes.order_id', $orderId)
            ->with('activity')
            ->with('location')
            ->with('service')
            ->lock('with(nolock)')
            ->orderBy($sortBy, $sortDirection);

        if ($tabId && $tabLink != Tab::TAB_LINK_MY_ACTIVITIES)
        {
            $query->where('screen_activity_mappings.tab_id', $tabId);
        }
        else if ($activityId)
        {
            $query->where('cpm_order_matrixes.activity_id', $activityId);
        }

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('cpm_activities.name', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

}