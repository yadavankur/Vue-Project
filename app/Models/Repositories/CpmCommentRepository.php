<?php

namespace App\Models\Repositories;


use App\Models\Entities\CPM_Comment;
use App\Models\Entities\Location;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use DB;

class CpmCommentRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\CPM_Comment';
    }
    public function saveNotes(Request $request, $type)
    {
        try {
            DB::beginTransaction();
            $comments = $request->notes;
            $quoteId = $request->quoteId;
//            $locationAbbr = $request->location;
//            $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
//            if (!$location instanceof Location)
//                throw new Exception('Invalid location!');
//            $locationId = $location->id;
            $locationId = $request->locationId;
            $orderId = $request->orderId;
            $activityId = $request->activityId;
            $deliveryDate = $request->deliveryDate;
            $orderMatrixId = $request->orderMatrixId;
            $result = DateTime::createFromFormat('!d/m/Y', $deliveryDate);
            if ($result == false)
            {
                $result = DateTime::createFromFormat('Y-m-d H:i:s.u', $deliveryDate);
                if ($result == false)
                {
                    throw new Exception('Invalid delivery date passed in!');
                }
                else
                    $deliveryDate = $result;
            }
            else
                $deliveryDate = $result;

            $statusId = $request->status;
            // update delivery date for the activity
            CpmOrderMatrixRepository::updateDeliveryDate($orderMatrixId, $orderId, $activityId, $deliveryDate, $statusId);
            CpmOrderMatrixRepository::updateSpecialTaskStatus($quoteId, $locationId, $orderId, $activityId, $statusId);
            $notes = new CPM_Comment();
            $notes->comment = $comments;
            $notes->quote_id = $quoteId;
            $notes->location_id = $locationId;
            $notes->order_id = $orderId;
            $notes->activity_id = $activityId;
            $notes->cpm_order_id = $orderMatrixId;
            $notes->type = $type;
            $notes->save();
            DB::commit();
            return $notes;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }

    public function getByPaginate($request, $type)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $quoteId = $request->quoteId;
//        $locationAbbr = $request->location;
//        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
//        if (!$location instanceof Location)
//            throw new Exception('Invalid location!');
        $locationId = $request->locationId;
        $orderId = $request->orderId;
        $activityId = trim($request->activityId);
        $search = $request->filter;

        $query = $this->model->select(['cpm_comments.*'])
            ->join('users', 'cpm_comments.created_by', '=', 'users.id')
            ->where('cpm_comments.quote_id', $quoteId)
            ->where('cpm_comments.location_id', $locationId)
            ->where('cpm_comments.order_id', $orderId)
            ->where('cpm_comments.active', 1)
            ->where('cpm_comments.type', $type)
            ->orderBy($sortBy, $sortDirection);

        if ($activityId) {
            $query = $query->where(function ($query) use ($activityId) {
                $query->where('cpm_comments.activity_id', $activityId);
            });
        }

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('users.name', 'LIKE', $like)
                    ->orWhere('cpm_comments.comment','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

    public function getLatestNotes($request)
    {
        $orderId = $request->orderId;
        $type = $request->type;
        $activityId = $request->activityId;
        //$cpmOrderId = $request->cpmOrderId;
        $quoteId = $request->quoteId;
        $locationId = $request->locationId;
//        $locationAbbr = $request->location;
//        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
//        if (!$location instanceof Location)
//            throw new Exception('Invalid location!');
//        $locationId = $location->id;

        if (!$orderId)
            throw new Exception('Invalid order!');
        if (!$type)
            throw new Exception('Invalid note type!');
        if (!$activityId)
            throw new Exception('Invalid activity!');

        $comment = $this->model->latest('updated_at')
            ->where('cpm_comments.quote_id', $quoteId)
            ->where('cpm_comments.location_id', $locationId)
            ->where('cpm_comments.order_id', $orderId)
            ->where('cpm_comments.activity_id', $activityId)
            ->where('cpm_comments.active', 1)
            ->where('cpm_comments.type', $type)
            ->first();

        return $comment;
    }
}