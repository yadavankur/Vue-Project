<?php

namespace App\Models\Repositories;



use App\Models\Entities\BookingConfirmation;
use App\Models\Entities\CPM_Activity;
use App\Models\Entities\CPM_OrderMatrix;
use App\Models\Entities\CPM_Service;
use App\Models\Entities\Location;
use App\Models\Entities\TaskMapping;
use App\Models\Utils\UtilsAbstract;
use Carbon\Carbon;
use Exception;
use DateTime;
use DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class BookingConfirmationRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\BookingConfirmation';
    }
    public function updateRequestedDeliveryDate($request)
    {
        try {
            DB::beginTransaction();

            $deliveryDate = $request->deliveryDate;
            $type = $request->type;
            $orderId = $request->orderId;
            $quoteId = $request->quoteId;
            $locationAbbr = $request->location;
            $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
            if (!$orderId || !$quoteId)
                throw new Exception('Invalid order!');
            if (!$type)
                throw new Exception('Invalid type!');
            if (!$location instanceof Location)
                throw new Exception('Invalid location!');
            $locationId = $location->id;
            //  -> xx/xx/xxx 00:00:00
            $deliveryDate = DateTime::createFromFormat('!d/m/Y', $deliveryDate);
            //$deliveryDate = DateTime::createFromFormat('d/m/Y', $deliveryDate);
            $bookingConfirmation = $this->model
                    ->where('order_id', $orderId)
                    ->where('quote_id', $quoteId)
                    ->where('location_id', $locationId)
                    ->where('active', 1)->first();
            if (!$bookingConfirmation instanceof BookingConfirmation)
            {
                $bookingConfirmation = new BookingConfirmation();
                $bookingConfirmation->order_id = $orderId;
                $bookingConfirmation->location_id = $locationId;
                $bookingConfirmation->quote_id = $quoteId;
            }
            switch ($type)
            {
                case BookingConfirmation::DATE_TYPE_AGREED:
                    $confirmed_date = Carbon::now();
                    self::AddLog($quoteId, $locationId, $orderId, 'Updated agreed date', 'Booking',
                        ($bookingConfirmation->agreed_date? $bookingConfirmation->agreed_date->format('d/m/Y') : '') . ' => ' .  $deliveryDate->format('d/m/Y') . ' ' .
                        ($bookingConfirmation->confirmed_date? $bookingConfirmation->confirmed_date->format('d/m/Y') : '') . ' => ' .  $confirmed_date->format('d/m/Y') .
                        ' in ' .  get_class($bookingConfirmation) . ' and set booked flag and clear expedite date and flag');
                    $bookingConfirmation->agreed_date = $deliveryDate;
                    $bookingConfirmation->expedite_date = null;
                    $bookingConfirmation->can_expedite = false;
                    $bookingConfirmation->booked = true;
                    $bookingConfirmation->confirmed_date = $confirmed_date;
                    // trigger book action
                    // TODO
                    // 1) create CPM
                    // be careful to those activities that has already been completed
                    // all these action are done in stored procedure
                    $execFlag = 3;
                    $offset = 0;
                    $baseStartDate = '';
                    $requiredDate = $deliveryDate->format('d/m/Y');
                    $service = CPM_Service::where('name', CPM_Service::MAIN_SERVICE)
                        ->where('location_id', $locationId)->active()->first();
                    $serviceId = $service->id;
                    $activities = DB::select("SET NOCOUNT ON; EXEC dbo.CMP_CalculateCriticalPath ?,?,?,?,?,?;",
                        array($quoteId, $execFlag, $baseStartDate, $offset, $requiredDate, $serviceId));

                    // 2) check the booking task mapping to set the status of booking activity to complete
                    CpmOrderMatrixRepository::updateTaskStatus($quoteId, $locationId, $orderId, TaskMapping::TASK_BOOKING);
                    break;
                case BookingConfirmation::DATE_TYPE_CUSTOMER:
                    self::AddLog($quoteId, $locationId, $orderId, 'Updated requested date', 'Booking',
                        ($bookingConfirmation->requested_date? $bookingConfirmation->requested_date->format('d/m/Y') : '') . ' => ' .  $deliveryDate->format('d/m/Y') . ' in ' .  get_class($bookingConfirmation));
                    $bookingConfirmation->requested_date = $deliveryDate;
                    break;
                case BookingConfirmation::DATE_TYPE_EXPEDITE:
                    self::AddLog($quoteId, $locationId, $orderId, 'Updated expedite date', 'Booking',
                        ($bookingConfirmation->expedite_date? $bookingConfirmation->expedite_date->format('d/m/Y') : '') . ' => ' .  $deliveryDate->format('d/m/Y') . ' in ' .  get_class($bookingConfirmation));
                    $bookingConfirmation->expedite_date = $deliveryDate;
                    $bookingConfirmation->can_expedite = true;
                    break;
                case BookingConfirmation::DATE_TYPE_SYSTEM:
                    self::AddLog($quoteId, $locationId, $orderId, 'Updated system generated date', 'Booking',
                        ($bookingConfirmation->sys_generated_date? $bookingConfirmation->sys_generated_date->format('d/m/Y') : '') . ' => ' .  $deliveryDate->format('d/m/Y') . ' in ' .  get_class($bookingConfirmation));
                    $bookingConfirmation->sys_generated_date = $deliveryDate;
                    break;
                default:
                    throw new Exception('Invalid type!');
                    break;
            }
            $bookingConfirmation->save();
            DB::commit();
            return $bookingConfirmation;
        }
        catch (Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
    public function getByPaginate($request, $userService)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        //$orderId = $request->orderId;

        $search = $request->filter;

        $query = $this->model->select([
            'booking_confirmation.*',
//            'V_V6_QUOTE.QUOTE_ID',
            'V_V6_QUOTE.QUOTE_NUM',
            'V_V6_QUOTE.QUOTE_NUM_SUFF',
            'V_V6_QUOTE.QUOTE_NUM_PREF',
            'V_V6_QUOTE.UDF1',
            'V_V6_QUOTE.UDF5',
            'V_V6_QUOTE.DELIVERY_DATE',
            'V_V6_CONTACT.CONTACT_NAME',
            // DB::raw("CONCAT(V_V6_QUOTE.QUOTE_NUM, '.', V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT, '.', V_V6_QUOTE.QUOTE_NUM_SUFF COLLATE DATABASE_DEFAULT) as jobNo")
        ])
            ->join('V_V6_QUOTE', DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'))
            //->where('booking_confirmation.order_id', $orderId)
            ->join('V_V6_CUSTOMER', 'V_V6_CUSTOMER.CUST_ID', '=', 'V_V6_QUOTE.CUST_ID', 'left outer')
            ->join('V_V6_SALESPERSON', 'V_V6_SALESPERSON.USER_ID', '=', 'V_V6_QUOTE.SALES_PERSON', 'left outer')
            ->join('V_V6_CONTACT', 'V_V6_CONTACT.CONTACT_ID', '=', 'V_V6_QUOTE.SHIP_TO_CONTACT_ID', 'left outer')
            ->join('V_V6_ADDR', 'V_V6_ADDR.ADDR_ID', '=', 'V_V6_QUOTE.DEL_ADDR_ID', 'left outer')
            ->join('V_V6_STATUS', 'V_V6_STATUS.STATUS_ID', '=', 'V_V6_QUOTE.STATUS_ID', 'left outer')
            ->where('booking_confirmation.active', 1)
            ->where('V_V6_STATUS.AREA_ID', 3)
            ->orderBy($sortBy, $sortDirection);

        $salesContactName = trim($search['salesContact']['name']);
        $salesContactCode = trim($search['salesContact']['code']);
        $salesOrderNumber = trim($search['salesOrderNumber']);
        $superVisorUpdatedPhone = trim($search['superVisorUpdated']['phone']);
        $superVisorUpdatedName = trim($search['superVisorUpdated']['name']);
        $customerAddress = trim($search['customerAddress']);
        $customerName = trim($search['customer']['name']);
        $customerCode = trim($search['customer']['code']);
        $orderStatus = trim($search['orderStatus']);
        $locationCode = trim($search['orderLocation']);
        $confirmedStatusCode = trim($search['confirmedStatus']);
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
            // get all assigned location ids
            // get accessible locations
            $user = JWTAuth::parseToken()->authenticate();
            $locationIds = LocationRepository::getAccessibleLocationIds($user, $userService);
            $query->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
                ->wherein('locations.id', $locationIds);
        }
        if ($orderStatus)
        {
            $like = "%{$orderStatus}%";
            $query->where('V_V6_STATUS.DESCR', 'LIKE', $like)
                ->where('V_V6_STATUS.AREA_ID',3);
        }
        if ($customerName)
        {
            $like = "%{$customerName}%";
            $query->where('V_V6_CUSTOMER.CUST_NAME', 'LIKE', $like);
        }
        if ($customerCode)
        {
            $like = "%{$customerCode}%";
            $query->where('V_V6_CUSTOMER.CUST_CODE', 'LIKE', $like);
        }
        if ($salesContactName)
        {
            $like = "%{$salesContactName}%";
            $query->where('V_V6_SALESPERSON.USER_NAME', 'LIKE', $like);
        }
        if ($salesContactCode)
        {
            $like = "%{$salesContactCode}%";
            $query->where('V_V6_SALESPERSON.USER_CODE', 'LIKE', $like);
        }
        if ($salesOrderNumber)
        {
            $like = "%{$salesOrderNumber}%";
            $query->where('booking_confirmation.order_id', 'LIKE', $like);
        }
        if ($superVisorUpdatedName)
        {
            $like = "%{$superVisorUpdatedName}%";
            $query->where('V_V6_CONTACT.CONTACT_NAME', 'LIKE', $like);
        }
        if ($superVisorUpdatedPhone)
        {
            $like = "%{$superVisorUpdatedPhone}%";
            $query->where('V_V6_CONTACT.MOBILE', 'LIKE', $like)
                    ->orwhere('V_V6_CONTACT.PHONE', 'LIKE', $like);
        }
        if ($customerAddress)
        {
            $like = "%{$customerAddress}%";
            $query->where('V_V6_ADDR.ADDR_1', 'LIKE', $like)
                ->orwhere('V_V6_ADDR.ADDR_3', 'LIKE', $like);
        }
        if ($dateRangeFrom)
        {
            $dateRangeFrom = Carbon::createFromFormat('!d/m/Y', $dateRangeFrom);
            $query->where('booking_confirmation.agreed_date', '>=', $dateRangeFrom);
        }
        if ($dateRangeTo)
        {
            $dateRangeTo = Carbon::createFromFormat('!d/m/Y', $dateRangeTo);
            $query->where('booking_confirmation.agreed_date', '<=', $dateRangeTo);
        }
        if ($confirmedStatusCode != '')
        {   $query->where('booking_confirmation.confirmed', $confirmedStatusCode);    }
        return $query->paginate($perPage);
    }

    public function getBookingInformation($request)
    {
        $quoteId = $request->quoteId;
        $orderId = $request->orderId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $booking = $this->model->select([
            'booking_confirmation.*',
            'V_V6_QUOTE.QUOTE_NUM',
            'V_V6_QUOTE.QUOTE_NUM_SUFF',
            'V_V6_QUOTE.QUOTE_NUM_PREF',
            'V_V6_QUOTE.UDF1',
            'V_V6_QUOTE.UDF5',
            'V_V6_QUOTE.DELIVERY_DATE',
        ])
            ->join('V_V6_QUOTE', DB::raw('V_V6_QUOTE.UDF1 COLLATE DATABASE_DEFAULT'), '=', DB::raw('booking_confirmation.order_id COLLATE DATABASE_DEFAULT'))
            ->where('booking_confirmation.quote_id', $quoteId)
            ->where('booking_confirmation.location_id', $locationId)
            ->where('booking_confirmation.order_id', $orderId)
            ->where('booking_confirmation.active', 1)->first();
        return $booking;
    }

    public function getOrderConfirmationEmailTemplate($request)
    {
        $customerType = $request->customerType;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        // get booking information, customer info and order info
        $booking = $this->model
            ->where('booking_confirmation.quote_id', $quoteId)
            ->where('booking_confirmation.location_id', $locationId)
            ->where('booking_confirmation.order_id', $orderId)
            ->where('booking_confirmation.active', 1)->first();
        if (!$booking instanceof BookingConfirmation)
        {
            throw new Exception('This order has not been booked yet, please book first!');
        }
        $tempDeliveryDate = $booking->agreed_date ?? $booking->order->DELIVERY_DATE;
        $deliveryDate = Carbon::parse($tempDeliveryDate);
        $content = [
            'customerName' => $booking->order->customer->CUST_NAME,
            'orderNo' => $orderId,
            'deliveryDate' => $deliveryDate->format('d/m/Y'),
        ];
        view()->share('content',$content);
        switch ($customerType)
        {
            case 'CS':
                // cash customer
                // return view html to front end
                return view('emails.order.cash-customer');
                break;
            case 'AC':
            default:
                // account customer
                return view('emails.order.account-customer');
                break;
        }
    }

    public function getSingleConfirmationEmailTemplate($request)
    {
        $emailType = $request->emailType;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        // get booking information, customer info and order info
        $booking = $this->model
            ->where('booking_confirmation.quote_id', $quoteId)
            ->where('booking_confirmation.location_id', $locationId)
            ->where('booking_confirmation.order_id', $orderId)
            ->where('booking_confirmation.active', 1)->first();
        if (!$booking instanceof BookingConfirmation)
        {
            throw new Exception('This order has not been booked yet, please book first!');
        }
        $tempDeliveryDate = $booking->agreed_date ?? $booking->order->DELIVERY_DATE;
        $deliveryDate = Carbon::parse($tempDeliveryDate);
        //$deliveryDate = Carbon::parse($booking->agreed_date);
        $deliveryAddress = '';
        if ($booking->order->deliver_address)
        {
            $address1 = $booking->order->deliver_address->ADDR_1 ?? '';
            $address2 = $booking->order->deliver_address->ADDR_2 ? ' ' . $booking->order->deliver_address->ADDR_2 : '';
            $address3 = $booking->order->deliver_address->ADDR_3 ? ', ' . $booking->order->deliver_address->ADDR_3 : '';
            $address4 = $booking->order->deliver_address->ADDR_4 ? ' ' . $booking->order->deliver_address->ADDR_4 : '';
            $address5 = $booking->order->deliver_address->ADDR_5 ? ' ' . $booking->order->deliver_address->ADDR_5 : '';
            $deliveryAddress = $address1 . $address2 . $address3 . $address4 .$address5;
        }
        $content = [
            'customerName' => $booking->order->customer->CUST_NAME,
            'orderNo' => $orderId,
            'deliveryAddress' => $deliveryAddress,
            'deliveryDate' => $deliveryDate->format('d/m/Y'),
        ];
        view()->share('content',$content);
        switch ($emailType)
        {
            case BookingConfirmation::EMAIL_SINGLE_CONFIRMATION_TYPE_TELEPHONE_CALL:
                // to confirm a telephone call
                // return view html to front end
                return view('emails.order.single-confirmation-email-telephone-call');
                break;
            case BookingConfirmation::EMAIL_SINGLE_CONFIRMATION_TYPE_REQUEST_CALL:
            default:
                // to request a call to confirm if couldn't get them on the phone
                return view('emails.order.single-confirmation-email-request-call');
                break;
        }
    }
    public function getConfirmationEmailTemplate($request)
    {
        $emailType = $request->emailType;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        // get booking information, customer info and order info
        $booking = $this->model
            ->where('booking_confirmation.quote_id', $quoteId)
            ->where('booking_confirmation.location_id', $locationId)
            ->where('booking_confirmation.order_id', $orderId)
            ->where('booking_confirmation.active', 1)->first();
        if (!$booking instanceof BookingConfirmation)
        {
            throw new Exception('This order has not been booked yet, please book first!');
        }
        //$deliveryDate = Carbon::parse($booking->agreed_date);
        $tempDeliveryDate = $booking->agreed_date ?? $booking->order->DELIVERY_DATE;
        $deliveryDate = Carbon::parse($tempDeliveryDate);
        $deliveryAddress = '';
        if ($booking->order->deliver_address)
        {
            $address1 = $booking->order->deliver_address->ADDR_1 ?? '';
            $address2 = $booking->order->deliver_address->ADDR_2 ? ' ' . $booking->order->deliver_address->ADDR_2 : '';
            $address3 = $booking->order->deliver_address->ADDR_3 ? ', ' . $booking->order->deliver_address->ADDR_3 : '';
            $address4 = $booking->order->deliver_address->ADDR_4 ? ' ' . $booking->order->deliver_address->ADDR_4 : '';
            $address5 = $booking->order->deliver_address->ADDR_5 ? ' ' . $booking->order->deliver_address->ADDR_5 : '';
            $deliveryAddress = $address1 . $address2 . $address3 . $address4 .$address5;
        }
        $content = [
            'customerName' => $booking->order->customer->CUST_NAME,
            'orderNo' => $orderId,
            'deliveryAddress' => $deliveryAddress,
            'deliveryDate' => $deliveryDate->format('d/m/Y'),
        ];
        view()->share('content',$content);
        switch ($emailType)
        {
            case BookingConfirmation::EMAIL_SINGLE_CONFIRMATION_TYPE_TELEPHONE_CALL:
                // to confirm a telephone call
                // return view html to front end
                return view('emails.order.single-confirmation-email-telephone-call');
                break;
            case BookingConfirmation::EMAIL_SINGLE_CONFIRMATION_TYPE_REQUEST_CALL:
                // to request a call to confirm if couldn't get them on the phone
                return view('emails.order.single-confirmation-email-request-call');
                break;
            case BookingConfirmation::EMAIL_LIST_CONFIRMATION_TYPE_TELEPHONE_CALL:
                // to confirm a telephone call
                return view('emails.order.list-confirmation-email-telephone-call');
                break;
            default:
                // to request a call to confirm if couldn't get them on the phone
                return view('emails.order.single-confirmation-email-request-call');
                break;
        }
    }

    public function getSingleConfirmationTextTemplate($request)
    {
        $textType = $request->textType;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        // get booking information, customer info and order info
        $booking = $this->model
            ->where('booking_confirmation.quote_id', $quoteId)
            ->where('booking_confirmation.location_id', $locationId)
            ->where('booking_confirmation.order_id', $orderId)
            ->where('booking_confirmation.active', 1)->first();
        if (!$booking instanceof BookingConfirmation)
        {
            throw new Exception('This order has not been booked yet, please book first!');
        }
        //$deliveryDate = Carbon::parse($booking->agreed_date);
        $tempDeliveryDate = $booking->agreed_date ?? $booking->order->DELIVERY_DATE;
        $deliveryDate = Carbon::parse($tempDeliveryDate);
        $deliveryAddress = '';
        if ($booking->order->deliver_address)
        {
            $address1 = $booking->order->deliver_address->ADDR_1 ?? '';
            $address2 = $booking->order->deliver_address->ADDR_2 ? ' ' . $booking->order->deliver_address->ADDR_2 : '';
            $address3 = $booking->order->deliver_address->ADDR_3 ? ', ' . $booking->order->deliver_address->ADDR_3 : '';
            $deliveryAddress = $address1 . $address2 . $address3;
            $deliveryAddress = UtilsAbstract::truncate($deliveryAddress, 40);
        }
        $content = [
            'customerName' => $booking->order->customer->CUST_NAME,
            'orderNo' => $orderId,
            'deliveryAddress' => $deliveryAddress,
            'deliveryDate' => $deliveryDate->format('d/m/Y'),
        ];
        view()->share('content',$content);
        switch ($textType)
        {
            case BookingConfirmation::TEXT_SINGLE_CONFIRMATION_TYPE_ORDER_CONFIRMATION:
                // to confirm a telephone call
                // return view html to front end
                return view('emails.order.single-confirmation-text-for-order-confirmation');
                break;
            case BookingConfirmation::TEXT_SINGLE_CONFIRMATION_TYPE_MONEY_OWING:
                // to customers who not fully paid
                // return view html to front end
                return view('emails.order.money-owing-text');
                break;
            case BookingConfirmation::TEXT_SINGLE_CONFIRMATION_TYPE_REQUEST_CALL:
            default:
                // to request a call to confirm if couldn't get them on the phone
                return view('emails.order.single-confirmation-text-for-request-call');
                break;
        }
    }
    public function getExpediteEnquiryTemplate($request)
    {
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        // get booking information, customer info and order info
        $booking = $this->model
            ->where('booking_confirmation.quote_id', $quoteId)
            ->where('booking_confirmation.location_id', $locationId)
            ->where('booking_confirmation.order_id', $orderId)
            ->where('booking_confirmation.active', 1)->first();
        if (!$booking instanceof BookingConfirmation)
        {
            throw new Exception('This order has not been booked yet, please book first!');
        }
        $tempDeliveryDate = $booking->agreed_date ?? $booking->order->DELIVERY_DATE;
        $deliveryDate = Carbon::parse($tempDeliveryDate);
        $deliveryAddress = '';
        if ($booking->order->deliver_address)
        {
            $address1 = $booking->order->deliver_address->ADDR_1 ?? '';
            $address2 = $booking->order->deliver_address->ADDR_2 ? ' ' . $booking->order->deliver_address->ADDR_2 : '';
            $address3 = $booking->order->deliver_address->ADDR_3 ? ', ' . $booking->order->deliver_address->ADDR_3 : '';
            $deliveryAddress = $address1 . $address2 . $address3;
        }
        $content = [
            'schedulerName' => 'Scheduler',
            'customerName' => $booking->order->customer->CUST_NAME,
            'orderNo' => $orderId,
            'deliveryAddress' => $deliveryAddress,
            'deliveryDate' => $deliveryDate->format('d/m/Y'),
            'requestedDeliveryDate' => $deliveryDate->format('d/m/Y'),
        ];
        view()->share('content',$content);
        return view('emails.expedite.enquiry-template');
    }
}