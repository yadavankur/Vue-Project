<?php

namespace App\Models\Repositories;

use App\Models\Entities\AssociatedJob;
use App\Models\Entities\BookingConfirmation;
use App\Models\Entities\Comment;
use App\Models\Entities\Location;
use App\Models\Entities\TaskMapping;
use App\Models\Entities\V6Quote;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\View;

class AssociatedJobRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\AssociatedJob';
    }

    public function getByPaginate($request)
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

        // automatically link associated jobs
        $associatedJobs = $this->autoLinkAssociatedJobs($quoteId, $orderId, $locationId, $locationAbbr);

        if (!$orderId)
        {
            throw new Exception('The order is invalid!');
        }
        $search = $request->filter;

        $query = $this->model->select([
                'associated_jobs.id',
                'associated_jobs.quote_id',
                'associated_jobs.order_id',
                'associated_jobs.order_quote_id',
                'associated_jobs.location_id',
                'associated_jobs.created_by',
                'associated_jobs.updated_by',
                'V_V6_QUOTE.QUOTE_NUM',
                'V_V6_QUOTE.QUOTE_NUM_PREF',
                'V_V6_QUOTE.QUOTE_NUM_SUFF',
                'V_V6_QUOTE.UDF1',
                'V_V6_QUOTE.UDF5',
                'V_V6_QUOTE.DELIVERY_DATE',
                'booking_confirmation.agreed_date',
                'booking_confirmation.booked',
                'booking_confirmation.confirmed',
                'booking_confirmation.confirmed_date',
                DB::raw("CONCAT(V_V6_QUOTE.QUOTE_NUM, '.', V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT, '.', V_V6_QUOTE.QUOTE_NUM_SUFF COLLATE DATABASE_DEFAULT) as jobNo")
            ])
            ->join('V_V6_QUOTE', 'V_V6_QUOTE.QUOTE_ID', '=', 'associated_jobs.quote_id')
            //->join('booking_confirmation', DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'), 'left outer')
            ->leftjoin('booking_confirmation', function($join) {
                $join->on(DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'));
                $join->on('V_V6_QUOTE.QUOTE_ID', '=', 'booking_confirmation.quote_id');
            })
            ->where('associated_jobs.order_id', $orderId)
            ->where('associated_jobs.order_quote_id', $quoteId)
            ->where('associated_jobs.location_id', $locationId)
            ->where('V_V6_QUOTE.QUOTE_NUM_PREF', $locationAbbr)
            ->where('associated_jobs.active', 1)
            ->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('V_V6_QUOTE.UDF5', 'LIKE', $like)
                    ->orwhere('V_V6_QUOTE.QUOTE_NUM', 'LIKE', $like)
                    ->orwhere('V_V6_QUOTE.UDF1', 'LIKE', $like)
                    ->orwhere('V_V6_QUOTE.QUOTE_NUM_SUFF', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

    public function add($request)
    {
        try {
            $associatedJobs = [];
            $orderId = trim($request->input('orderId'));
            $order_quoteId = trim($request->input('quoteId'));
            $locationAbbr = trim($request->input('location'));
            $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
            if (!$location instanceof Location)
                throw new Exception('Invalid location!');
            $locationId = $location->id;
            $quoteIds = $request->input('quoteIds');
            $order = V6Quote::where('UDF1', $orderId)
                ->where('QUOTE_NUM_PREF', $locationAbbr)
                ->where('QUOTE_ID', $order_quoteId)->first();
            if (!$orderId || (!$order instanceof V6Quote))
            {
                throw new Exception('The order is invalid!');
            }
            DB::beginTransaction();
//            foreach($quoteIds as $quoteId)
//            {
//                $associatedOrder = V6Quote::where('QUOTE_ID', $quoteId)->first();
//                if (!$associatedOrder instanceof V6Quote)
//                {
//                    throw new Exception('The associated job is invalid! QUOTE_ID=', $quoteId);
//                }
//                $associatedJob = $this->addAssociatedJob($order_quoteId, $locationId, $orderId, $quoteId);
//                // add two way binding
//                // get order detail from V6Quote based on $quoteId
//                // then add $orderId as the associated job to it
//                $this->addAssociatedJob($quoteId, $locationId, $associatedOrder->UDF1, $order_quoteId);
//                $associatedJobs[] = $associatedJob;
//            }
            $associatedJobs = $this->addJobs($quoteIds, $order_quoteId, $orderId, $locationId);
            DB::commit();
            return $associatedJobs;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    private function addAssociatedJob($order_quoteId, $locationId, $orderId, $quoteId)
    {
        // first check whether it is already in the table
        $findJob =  $this->model->where('order_quote_id', $order_quoteId)
            ->where('location_id', $locationId)
            ->where('order_id', $orderId)
            ->where('quote_id', $quoteId)->where('active', 1)->first();
        if ($findJob instanceof AssociatedJob)
        {
            // already in the table
            // do nothing
            self::AddLog($order_quoteId, $locationId, $orderId, 'Added associated job', 'Booking',
                'Added a existing quoteId: ['. $quoteId . '] in ' .  get_class($findJob));
            return $findJob;
        }
        $associatedJob =  new AssociatedJob();
        $associatedJob->order_quote_id = $order_quoteId;
        $associatedJob->location_id = $locationId;
        $associatedJob->order_id = $orderId;
        $associatedJob->quote_id = $quoteId;
        $associatedJob->save();
        self::AddLog($order_quoteId, $locationId, $orderId, 'Added associated job', 'Booking',
            'Added a new quoteId: ['. $quoteId . '] in ' .  get_class($associatedJob));
        return $associatedJob;
    }
    public function delete($request)
    {
        $associatedJob =  $this->model->findOrFail($request->input('id'));
        $associatedJob->active = 0;
        $associatedJob->save();
        self::AddLog($associatedJob->order_quote_id, $associatedJob->location_id, $associatedJob->order_id, 'Deleted associated job', 'Booking',
            'Deleted a quoteId: ['. $associatedJob->quote_id . '] in ' .  get_class($associatedJob));
        // need to delete the opposite (because of two way binding)
        $findOppositeJob =  $this->model->where('order_quote_id', $associatedJob->quote_id)
            ->where('location_id', $associatedJob->location_id)
            ->where('quote_id', $associatedJob->order_quote_id)
            ->where('active', 1)->first();
        if ($findOppositeJob instanceof AssociatedJob)
        {
            $findOppositeJob->active = 0;
            $findOppositeJob->save();
            self::AddLog($findOppositeJob->order_quote_id, $findOppositeJob->location_id, $findOppositeJob->order_id, 'Deleted associated job', 'Booking',
                'Deleted a quoteId: ['. $findOppositeJob->quote_id . '] in ' .  get_class($findOppositeJob));
        }
        return $associatedJob;
    }
    public function save($request) {
        // this actually updates the booking_confirmation table
        // first try to find whether this job is in the booking_confirmation table
        // if found update its agreed_delivery date
        // if not found add a new record to it.
        // actually this is the agreed delivery date
        $agreed_date = Carbon::createFromFormat('!d/m/Y', $request->input('newAgreedDate'));
        $id = $request->input('id');
        if ($id != '')
        {
            $associatedJob =  $this->model->findOrFail($id);
            $bookingConfirmation = $associatedJob->booking;
            $quoteId =  $associatedJob->quote_id;
            $locationId = $associatedJob->location_id;
            $orderId = $associatedJob->quote->UDF1;
        }
        else
        {
            $orderId =  $request->input('orderId');
            $quoteId = $orderQuoteId =  $request->input('quoteId');
            $locationAbbr = trim($request->input('location'));
            $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
            if (!$location instanceof Location)
                throw new Exception('Invalid location!');
            $locationId = $location->id;
            $bookingConfirmation = BookingConfirmation::where('order_id', $orderId)
                                                        ->where('quote_id', $quoteId)
                                                        ->where('location_id', $locationId)
                                                        ->where('active', 1)->first();
        }
        if (!$bookingConfirmation instanceof BookingConfirmation)
        {
            // the associated job has not been booked yet
            // prompt to book it first
            throw new Exception('The associated job has not been booked yet, please ask booking staff to book it first!');
            // create a new record
//            $bookingConfirmation = new BookingConfirmation();
//            $bookingConfirmation->order_id = $associatedJob->quote->UDF1;
//            $bookingConfirmation->quote_id = $associatedJob->quote->QUOTE_ID;
//            $bookingConfirmation->location_id = $associatedJob->quote->location->id;
        }
        $confirmed_date = Carbon::now();
        $originalDeliveryDate = $bookingConfirmation->agreed_date;

        self::AddLog($quoteId, $locationId, $orderId, 'Confirmed', 'Confirmation',
            'Agreed Delivery date from ' . ($originalDeliveryDate? $originalDeliveryDate->format('d/m/Y'): '') . ' => ' .  $agreed_date->format('d/m/Y') .
            ', Confirmed date from ' . ($bookingConfirmation->confirmed_date? $bookingConfirmation->confirmed_date->format('d/m/Y H:i:s') : '') . ' => ' . $confirmed_date->format('d/m/Y  H:i:s') . ' in ' .  get_class($bookingConfirmation));
        $bookingConfirmation->agreed_date = $agreed_date;
        $bookingConfirmation->confirmed = true;
        $bookingConfirmation->confirmed_date = $confirmed_date;
        $bookingConfirmation->save();

        // check whether the confirmation task has been mapped
        CpmOrderMatrixRepository::updateTaskStatus($quoteId ,$locationId, $orderId, TaskMapping::TASK_CONFIRMATION);

        $noteTypeId = $request->input('noteTypeId');
        $dowellNotes = $request->input('dowellNotesContent');
        if ($noteTypeId != '' && $dowellNotes != '')
        {
            // TODO
            // need transaction??
            $notes = new Comment();
            $notes->comments = $dowellNotes;
            $notes->order_id = $orderId;
            $notes->quote_id = $quoteId;
            $notes->location_id = $locationId;
            $notes->note_type_id = $noteTypeId;
            $notes->save();
        }

        // check whether delivery date has been changed
        if ($originalDeliveryDate !== $agreed_date)
        {
            // TODO
            // need to re-calculate the CPM passing quoteID and delivery date
            $ret = DB::select("SET NOCOUNT ON; DECLARE @intRet INT; EXEC dbo.CPM_RecalculateCriticalPath ?,? @intRet OUTPUT; SELECT @intRet;",
                                array($quoteId, $agreed_date->format('d/m/Y')));
        }

        return $bookingConfirmation;
    }

    public function printConfirmationList($request)
    {
        // how to print the jobs? There are two possibilities.
        // 1) for one selected job, it has lots of associated jobs
        // thus print info of all those associated jobs
        // 2) Simply print info of all selected jobs
        // here need to tweak and confirm with MG, DL and DD.

        // get customer info of the main order
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $associatedJobIds = $request->selectedJobIds;
        $viewType = $request->viewType;
        $view = 'pdf.confirmation-print-list';
        switch ($viewType)
        {
            case 'EMAIL_LIST':
                $view = 'pdf.confirmation-email-list';
                break;
            case 'PRINT_LIST':
                $view = 'pdf.confirmation-print-list';
                break;
        }
        return EmailRepository::viewAttachedPDF($quoteId, $locationId, $orderId, $associatedJobIds, $view);
    }

    public function autoLinkAssociatedJobs($order_quoteId, $orderId, $locationId, $locationAbbr)
    {
        // 1) first find all associated jobs in V6_Quote Table
        $query = V6Quote::select([
            'V_V6_QUOTE.QUOTE_ID',
            'V_V6_QUOTE.QUOTE_NUM',
            'V_V6_QUOTE.QUOTE_NUM_PREF',
            'V_V6_QUOTE.QUOTE_NUM_SUFF',
            'V_V6_QUOTE.UDF1'
        ])
            ->whereRaw('V_V6_QUOTE.QUOTE_ID NOT IN 
                (SELECT associated_jobs.quote_id FROM associated_jobs 
                    WHERE 
                        associated_jobs.order_quote_id = ? 
                        and associated_jobs.order_id = ? 
                        and associated_jobs.location_id = ? )',
                array($order_quoteId, $orderId, $locationId))
            ->where('V_V6_QUOTE.QUOTE_ID', '<>', $order_quoteId)
            ->where('V_V6_QUOTE.QUOTE_NUM_PREF', $locationAbbr)
            ->whereRaw('V_V6_QUOTE.QUOTE_NUM = (SELECT V_V6_QUOTE.QUOTE_NUM FROM V_V6_QUOTE WHERE V_V6_QUOTE.UDF1 = ? and V_V6_QUOTE.QUOTE_NUM_PREF = ?)', array($orderId, $locationAbbr));

        // 2) check whether they are in associated_jobs table with ignoring active flag
        $associatedJobs = $query->get();
        $quoteIds = $associatedJobs->pluck('QUOTE_ID');

        try {
            DB::beginTransaction();
            $associatedJobs = $this->addJobs($quoteIds, $order_quoteId, $orderId, $locationId);
            DB::commit();
            return $associatedJobs;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function addJobs($quoteIds, $order_quoteId, $orderId, $locationId)
    {
        $associatedJobs = [];
        foreach($quoteIds as $quoteId)
        {
            $associatedOrder = V6Quote::where('QUOTE_ID', $quoteId)->first();
            if (!$associatedOrder instanceof V6Quote)
            {
                throw new Exception('The associated job is invalid! QUOTE_ID=', $quoteId);
            }
            $associatedJob = $this->addAssociatedJob($order_quoteId, $locationId, $orderId, $quoteId);
            // add two way binding
            // get order detail from V6Quote based on $quoteId
            // then add $orderId as the associated job to it
            $this->addAssociatedJob($quoteId, $locationId, $associatedOrder->UDF1, $order_quoteId);
            $associatedJobs[] = $associatedJob;
        }
        return $associatedJobs;
    }
}